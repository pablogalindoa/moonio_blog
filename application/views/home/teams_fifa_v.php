<div class="min-h-screen bg-gradient-to-br from-slate-900 via-green-900 to-slate-800 p-8 text-white">

    <!-- Contenedor Principal -->
    <div class="max-w-7xl mx-auto bg-slate-900/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-green-700">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-yellow-400">
                    Selecciones Participantes 🌍⚽
                </h1>
                <p class="text-gray-400 mt-1">
                    Equipos clasificados a la Copa Mundial 2026
                </p>
            </div>

            <button class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-3 rounded-full shadow-md font-semibold transition">
                + Agregar Selección
            </button>
        </div>

        <!-- Filtros -->
        <div class="flex gap-4 mb-8">
            <select class="bg-slate-800 border border-green-700 px-4 py-2 rounded-full text-white">
                <option>Todas las Confederaciones</option>
                <option>CONCACAF</option>
                <option>CONMEBOL</option>
                <option>UEFA</option>
                <option>AFC</option>
                <option>CAF</option>
                <option>OFC</option>
            </select>

            <input 
                type="text"
                placeholder="Buscar selección..."
                class="bg-slate-800 border border-green-700 px-4 py-2 rounded-full text-white placeholder-gray-400"
            >
        </div>

        <!-- Grid de Equipos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- México -->
            <div class="bg-slate-950 border border-green-700 rounded-2xl p-6 shadow-lg hover:scale-105 transition">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-yellow-400">
                        México 🇲🇽
                    </h2>
                    <span class="text-xs bg-green-600 text-white px-3 py-1 rounded-full">
                        Anfitrión
                    </span>
                </div>

                <p class="text-sm text-gray-400 mb-3">
                    Confederación: CONCACAF
                </p>

                <div class="flex justify-between text-sm mb-3">
                    <span class="text-gray-300">Títulos:</span>
                    <span class="text-yellow-400 font-semibold">0</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-300">Ranking FIFA:</span>
                    <span class="text-green-400 font-semibold">#15</span>
                </div>

            </div>

            <!-- Argentina -->
            <div class="bg-slate-950 border border-green-700 rounded-2xl p-6 shadow-lg hover:scale-105 transition">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-yellow-400">
                        Argentina 🇦🇷
                    </h2>
                    <span class="text-xs bg-yellow-500 text-black px-3 py-1 rounded-full">
                        Campeón Defensor
                    </span>
                </div>

                <p class="text-sm text-gray-400 mb-3">
                    Confederación: CONMEBOL
                </p>

                <div class="flex justify-between text-sm mb-3">
                    <span class="text-gray-300">Títulos:</span>
                    <span class="text-yellow-400 font-semibold">3</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-300">Ranking FIFA:</span>
                    <span class="text-green-400 font-semibold">#1</span>
                </div>

            </div>

            <!-- Estados Unidos -->
            <div class="bg-slate-950 border border-green-700 rounded-2xl p-6 shadow-lg hover:scale-105 transition">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-yellow-400">
                        Estados Unidos 🇺🇸
                    </h2>
                    <span class="text-xs bg-green-600 text-white px-3 py-1 rounded-full">
                        Anfitrión
                    </span>
                </div>

                <p class="text-sm text-gray-400 mb-3">
                    Confederación: CONCACAF
                </p>

                <div class="flex justify-between text-sm mb-3">
                    <span class="text-gray-300">Títulos:</span>
                    <span class="text-yellow-400 font-semibold">0</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-300">Ranking FIFA:</span>
                    <span class="text-green-400 font-semibold">#11</span>
                </div>

            </div>

        </div>

    </div>

</div>
