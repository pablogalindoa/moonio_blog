<div class="min-h-screen bg-gradient-to-br from-purple-100 via-pink-100 to-purple-200 p-8">

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto bg-white/60 backdrop-blur-lg rounded-3xl shadow-2xl p-8">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-purple-700">
                    Gestión de Tareas 📋
                </h1>
                <p class="text-purple-500 mt-1">
                    Mantente organizado, mantente cómodo ✨
                </p>
            </div>

            <button class="bg-purple-400 hover:bg-purple-500 text-white px-6 py-3 rounded-full shadow-md transition">
                + Agregar Tarea
            </button>
        </div>

        <!-- Kanban Board -->
        <div class="grid grid-cols-3 gap-6">

            <!-- To Do Column -->
            <div class="bg-purple-50 rounded-2xl p-6 shadow-inner">
                <h2 class="text-lg font-semibold text-purple-700 mb-4">
                    Pendiente 📝
                </h2>

                <div class="flex flex-col gap-4">

                    <div class="bg-white rounded-2xl p-4 shadow-sm">
                        <h3 class="font-semibold text-purple-700">
                            Diseñar Página Principal
                        </h3>
                        <p class="text-sm text-purple-500 mt-2">
                            Crear borrador de modelo.
                        </p>
                        <span class="inline-block mt-3 text-xs bg-pink-200 text-pink-700 px-3 py-1 rounded-full">
                            Alta Prioridad
                        </span>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-sm">
                        <h3 class="font-semibold text-purple-700">
                            Preparar notas para la junta.
                        </h3>
                        <p class="text-sm text-purple-500 mt-2">
                            Resumen de última junta.
                        </p>
                        <span class="inline-block mt-3 text-xs bg-yellow-200 text-yellow-700 px-3 py-1 rounded-full">
                            Media Prioridad
                        </span>
                    </div>

                </div>
            </div>

            <!-- In Progress Column -->
            <div class="bg-purple-50 rounded-2xl p-6 shadow-inner">
                <h2 class="text-lg font-semibold text-purple-700 mb-4">
                    En Proceso 🚀
                </h2>

                <div class="flex flex-col gap-4">

                    <div class="bg-white rounded-2xl p-4 shadow-md">
                        <h3 class="font-semibold text-purple-700">
                            Video Llamada con cliente Caleb.
                        </h3>
                        <p class="text-sm text-purple-500 mt-2">
                            Finalizar base de datos de cliente Fran.
                        </p>
                        <span class="inline-block mt-3 text-xs bg-purple-200 text-purple-700 px-3 py-1 rounded-full">
                            Diseño
                        </span>
                    </div>

                </div>
            </div>

            <!-- Done Column -->
            <div class="bg-purple-50 rounded-2xl p-6 shadow-inner">
                <h2 class="text-lg font-semibold text-purple-700 mb-4">
                    Completado ✅
                </h2>

                <div class="flex flex-col gap-4">

                    <div class="bg-white rounded-2xl p-4 shadow-sm opacity-80">
                        <h3 class="font-semibold text-purple-700 line-through">
                            Estructura de página web.
                        </h3>
                        <p class="text-sm text-purple-500 mt-2">
                            Repositorio inicial completo.
                        </p>
                        <span class="inline-block mt-3 text-xs bg-green-200 text-green-700 px-3 py-1 rounded-full">
                            Completado
                        </span>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>
