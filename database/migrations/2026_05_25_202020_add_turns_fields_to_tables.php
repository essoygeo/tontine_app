<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->integer('current_turn')->default(1)->after('cotisation_fixe');
            $table->string('turn_status')->default('collecting')->after('current_turn'); // collecting, completed
        });

        Schema::table('group_user', function (Blueprint $table) {
            $table->integer('turn_order')->nullable()->after('role');
        });

        Schema::table('contributions', function (Blueprint $table) {
            $table->integer('turn_number')->default(1)->after('group_id');
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['current_turn', 'turn_status']);
        });

        Schema::table('group_user', function (Blueprint $table) {
            $table->dropColumn('turn_order');
        });

        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn('turn_number');
        });
    }
};
