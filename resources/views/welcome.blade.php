<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<!-- 🔥 SIDEBAR -->
<aside class="fixed top-0 left-0 w-64 h-full bg-gray-900 text-white p-4">
    <h2 class="text-xl font-bold mb-6">Mon Dashboard</h2>

    <ul class="space-y-3">
        <li><a href="#" class="block hover:bg-gray-700 p-2 rounded">Accueil</a></li>
        <li><a href="#" class="block hover:bg-gray-700 p-2 rounded">Utilisateurs</a></li>
        <li><a href="#" class="block hover:bg-gray-700 p-2 rounded">Projets</a></li>
        <li><a href="#" class="block hover:bg-gray-700 p-2 rounded">Paramètres</a></li>
    </ul>
</aside>

<!-- 🔥 MAIN CONTENT -->
<div class="ml-64">

    <!-- TOPBAR -->
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold">Dashboard</h1>

        <!-- Dropdown Flowbite -->
        <div class="relative">
            <button id="userMenuButton" data-dropdown-toggle="userMenu"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                User
            </button>

            <div id="userMenu" class="hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow">
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Profil</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="p-6">

        <div class="grid grid-cols-3 gap-6">

            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-gray-500">Utilisateurs</h3>
                <p class="text-2xl font-bold">120</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-gray-500">Projets</h3>
                <p class="text-2xl font-bold">35</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-gray-500">Revenus</h3>
                <p class="text-2xl font-bold">$5,200</p>
            </div>

        </div>

    </div>

</div>

</body>
</html>
