<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Initie le paiement et redirige vers Flutterwave
     */
    public function pay(Request $request)
    {
        $request->validate([
            'groupId' => 'required',
            'amount' => 'required|numeric|min:1',
        ]);

        // Génération d'une référence unique
        $tx_ref = 'tontine_' . auth()->id() . '_' . time();

        $group = \App\Models\Group::findOrFail($request->groupId);

        // Création de la contribution en attente
        Contribution::create([
            'user_id' => auth()->id(),
            'group_id' => $request->groupId,
            'turn_number' => $group->current_turn,
            'amount' => $request->amount,
            'status' => 'pending',
            'tx_ref' => $tx_ref,
        ]);

        try {
            $payload = [
                "tx_ref" => $tx_ref,
                "amount" => $request->amount,
                "currency" => "XOF",
                "redirect_url" => route('flutterwave.callback'),
                "customer" => [
                    "email" => auth()->user()->email,
                    "name" => auth()->user()->name,
                ],
                "payment_options" => "card, mobilemoney, ussd",
                "customizations" => [
                    "title" => "Cotisation Tontine",
                    "description" => "Paiement de votre cotisation",

                ]
            ];

            Log::info('Tentative de paiement Flutterwave', ['payload' => $payload]);

            $response = Http::withToken(config('services.flutterwave.secret_key'))
                ->post('https://api.flutterwave.com/v3/payments', $payload);

            $res = $response->json();

            if ($response->successful() && isset($res['data']['link'])) {
                Log::info('Lien de paiement généré avec succès', ['url' => $res['data']['link']]);
                return redirect()->away($res['data']['link']);
            }

            Log::error('Erreur Flutterwave Payments API', [
                'status' => $response->status(),
                'response' => $res,
                'payload' => $payload
            ]);
            return back()->with('error', 'Erreur de génération du lien : ' . ($res['message'] ?? 'Inconnue'));

        } catch (\Exception $e) {
            Log::error('Exception lors de l\'appel Flutterwave', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Impossible de contacter le service de paiement.');
        }
    }

    /**
     * Gère le retour de l'utilisateur après le paiement (Client-side)
     */
    public function callback(Request $request)
    {
        Log::info('Callback Flutterwave reçu', ['params' => $request->all()]);

        // Gestion de l'annulation explicite par l'utilisateur sur Flutterwave
        if ($request->status === 'cancelled') {
            return redirect()->route('dashboard')
                ->with('info', 'Vous avez annulé le paiement. Votre cotisation n\'a pas été prélevée.');
        }

        $transactionId = $request->transaction_id ?? $request->id;

        if (!$transactionId) {
            return redirect()->route('dashboard')
                ->with('info', 'Le paiement n\'a pas été finalisé ou a été interrompu.');
        }

        // redirection vers page de statut pour vérification officielle
        return redirect()->route('payment.status', [
            'transaction_id' => $transactionId
        ]);
    }
    /**
     * Gère les notifications asynchrones de Flutterwave (Server-side)
     */
//    public function webhook(Request $request)
//    {
//        // Vérification du hash secret pour sécuriser le webhook
//        $signature = $request->header('verif-hash');
//        $secretHash = config('services.flutterwave.webhook_hash');
//
//        if ($secretHash && $signature !== $secretHash) {
//            Log::warning('Webhook Flutterwave : Signature invalide', ['header' => $signature]);
//            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
//        }
//
//        $payload = $request->all();
//        Log::info('Webhook Flutterwave reçu', ['payload' => $payload]);
//
//        if (isset($payload['data']['status']) && $payload['data']['status'] === 'successful') {
//            $this->verifyAndProcess($payload['data']['id']);
//        }
//
//        return response()->json(['status' => 'success'], 200);
//    }

    /**
     * Vérifie la transaction auprès de Flutterwave et met à jour la base de données
     */
    public function status(Request $request)
    {
        $transactionId = $request->transaction_id;

        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify")
            ->json();

        if (
            isset($response['status']) &&
            $response['status'] === 'success' &&
            $response['data']['status'] === 'successful'
        ) {
            $tx_ref = $response['data']['tx_ref'];

            $contribution = Contribution::where('tx_ref', $tx_ref)->first();

            if ($contribution && $contribution->status !== 'paid') {
                $contribution->forceFill([
                    'status' => 'paid',
                    'transaction_id' => $transactionId,
                    'paid_at' => now(),
                ])->save();

                return redirect()->route('dashboard')
                    ->with('success', 'Paiement réussi');
            }

            return redirect()->route('dashboard')
                ->with('info', 'Déjà payé');
        }

        return redirect()->route('dashboard')
            ->with('error', 'Paiement non confirmé');
    }
}
