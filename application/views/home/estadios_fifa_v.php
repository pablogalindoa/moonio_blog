<div class="min-h-screen bg-gradient-to-br from-slate-900 via-green-900 to-slate-800 p-8 text-white">

    <!-- Contenedor Principal -->
    <div class="max-w-7xl mx-auto bg-slate-900/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-green-700">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-yellow-400">
                    Estadios Sede 🏟
                </h1>
                <p class="text-gray-400 mt-1">
                    Sedes oficiales del Mundial 2026
                </p>
            </div>

            <button class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-3 rounded-full shadow-md font-semibold transition">
                + Agregar Estadio
            </button>
        </div>

        <!-- Filtros -->
        <div class="flex gap-4 mb-8">
            <select class="bg-slate-800 border border-green-700 px-4 py-2 rounded-full text-white">
                <option>Todos los Países</option>
                <option>Estados Unidos 🇺🇸</option>
                <option>México 🇲🇽</option>
                <option>Canadá 🇨🇦</option>
            </select>

            <input 
                type="text"
                placeholder="Buscar estadio..."
                class="bg-slate-800 border border-green-700 px-4 py-2 rounded-full text-white placeholder-gray-400"
            >
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Estadio Azteca -->
            <div class="relative group bg-slate-950 border border-green-700 rounded-2xl overflow-hidden shadow-lg hover:scale-105 transition">

                <!-- Imagen Hover -->
                <img src="https://via.placeholder.com/500x300"
                     class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-40 transition duration-500">

                <div class="relative z-10 p-6">

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-yellow-400">
                            Estadio Azteca 🇲🇽
                        </h2>
                        <span class="text-xs bg-yellow-500 text-black px-3 py-1 rounded-full">
                            🏆 Sede Histórica
                        </span>
                    </div>

                    <p class="text-sm text-gray-400 mb-2">
                        Ciudad: Ciudad de México
                    </p>

                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-300">Capacidad:</span>
                        <span class="text-green-400 font-semibold">87,000</span>
                    </div>

                    <div class="flex justify-between text-sm mb-4">
                        <span class="text-gray-300">Tipo de partidos:</span>
                        <span class="text-yellow-400 font-semibold">Inauguración</span>
                    </div>

                    <!-- Estadísticas -->
                    <div class="bg-slate-800 p-4 rounded-xl text-sm mb-4">
                        <p><span class="text-gray-400">Mundiales previos:</span> 1970, 1986</p>
                        <p><span class="text-gray-400">Finales disputadas:</span> 2</p>
                    </div>

                    <!-- Partidos -->
                    <div class="border-t border-green-700 pt-4 mb-4 text-sm">
                        <p class="text-gray-400 mb-2">Partidos asignados:</p>
                        <ul class="space-y-1 text-gray-200">
                            <li>🇲🇽 México vs 🇧🇷 Brasil</li>
                            <li>🇦🇷 Argentina vs 🇫🇷 Francia</li>
                        </ul>
                    </div>

                    <!-- Botón Mapa -->
                    <button class="w-full bg-slate-800 border border-green-600 hover:bg-green-700 transition rounded-xl py-2 text-sm text-green-400">
                        🗺 Ver ubicación en el mapa
                    </button>

                </div>
            </div>


            <!-- MetLife Stadium -->
            <div class="relative group bg-slate-950 border border-green-700 rounded-2xl overflow-hidden shadow-lg hover:scale-105 transition">

                <img src="https://via.placeholder.com/500x300"
                     class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-40 transition duration-500">

                <div class="relative z-10 p-6">

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-yellow-400">
                            MetLife Stadium 🇺🇸
                        </h2>
                        <span class="text-xs bg-blue-600 text-white px-3 py-1 rounded-full">
                            Estados Unidos
                        </span>
                    </div>

                    <p class="text-sm text-gray-400 mb-2">
                        Ciudad: Nueva York / Nueva Jersey
                    </p>

                    <div class="flex justify-between text-sm mb-4">
                        <span class="text-gray-300">Capacidad:</span>
                        <span class="text-green-400 font-semibold">82,500</span>
                    </div>

                    <div class="bg-slate-800 p-4 rounded-xl text-sm mb-4">
                        <p><span class="text-gray-400">Mundiales previos:</span> 1994</p>
                        <p><span class="text-gray-400">Evento destacado:</span> Final proyectada 2026</p>
                    </div>

                    <div class="border-t border-green-700 pt-4 mb-4 text-sm">
                        <p class="text-gray-400 mb-2">Partidos asignados:</p>
                        <ul class="space-y-1 text-gray-200">
                            <li>🇺🇸 USA vs 🇩🇪 Alemania</li>
                            <li>Semifinal</li>
                        </ul>
                    </div>

                    <button class="w-full bg-slate-800 border border-green-600 hover:bg-green-700 transition rounded-xl py-2 text-sm text-green-400">
                        🗺 Ver ubicación en el mapa
                    </button>

                </div>
            </div>


            <!-- BMO Field -->
            <div class="relative group bg-slate-950 border border-green-700 rounded-2xl overflow-hidden shadow-lg hover:scale-105 transition">

                <img src="https://via.placeholder.com/500x300"
                     class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-40 transition duration-500">

                <div class="relative z-10 p-6">

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-yellow-400">
                            BMO Field 🇨🇦
                        </h2>
                        <span class="text-xs bg-red-600 text-white px-3 py-1 rounded-full">
                            Canadá
                        </span>
                    </div>

                    <p class="text-sm text-gray-400 mb-2">
                        Ciudad: Toronto
                    </p>

                    <div class="flex justify-between text-sm mb-4">
                        <span class="text-gray-300">Capacidad:</span>
                        <span class="text-green-400 font-semibold">45,000</span>
                    </div>

                    <div class="bg-slate-800 p-4 rounded-xl text-sm mb-4">
                        <p><span class="text-gray-400">Primera sede mundialista:</span> 2026</p>
                        <p><span class="text-gray-400">Renovación:</span> 2016</p>
                    </div>

                    <div class="border-t border-green-700 pt-4 mb-4 text-sm">
                        <p class="text-gray-400 mb-2">Partidos asignados:</p>
                        <ul class="space-y-1 text-gray-200">
                            <li>🇨🇦 Canadá vs 🇯🇵 Japón</li>
                            <li>Fase de grupos</li>
                        </ul>
                    </div>

                    <button class="w-full bg-slate-800 border border-green-600 hover:bg-green-700 transition rounded-xl py-2 text-sm text-green-400">
                        🗺 Ver ubicación en el mapa
                    </button>

                </div>
            </div>

        </div>

    </div>

</div>
