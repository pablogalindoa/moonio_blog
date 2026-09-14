<div class="min-h-screen bg-gradient-to-br from-slate-900 via-green-900 to-slate-800 p-8 text-gray">

    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-10">

            <div class="text-6xl mb-4">
                🏆
            </div>

            <h1 class="text-5xl font-extrabold text-yellow-400">
                Editar Estrategia ⚽
            </h1>

            <p class="text-green-200 mt-3 text-lg">
                Modifica la jugada antes del próximo partido
            </p>

        </div>

        <!-- Mini Stats -->
        <div class="grid grid-cols-3 gap-4 mb-8">

            <div class="bg-green-800 rounded-2xl p-4 text-center shadow-lg border border-green-500">
                <div class="text-3xl mb-2">⚽</div>
                <p class="font-semibold">Jugadas</p>
                <p class="text-xs text-green-200">Nuevas tareas</p>
            </div>

            <div class="bg-yellow-600 rounded-2xl p-4 text-center shadow-lg">
                <div class="text-3xl mb-2">🏆</div>
                <p class="font-semibold text-black">Objetivos</p>
                <p class="text-xs text-black">Metas del torneo</p>
            </div>

            <div class="bg-red-700 rounded-2xl p-4 text-center shadow-lg">
                <div class="text-3xl mb-2">🔥</div>
                <p class="font-semibold">Prioridad</p>
                <p class="text-xs text-red-200">Nivel de importancia</p>
            </div>

        </div>

        <!-- Form Card -->
        <div class="bg-slate-900/90 backdrop-blur-lg border-2 border-yellow-400 rounded-3xl shadow-2xl p-10">

            <form action="<?= base_url('index.php/tasks/update/' . $task->id_task) ?>" method="POST">

                <!-- Title -->
                <div class="mb-6">

                    <label class="block mb-3 font-semibold text-yellow-400">
                        Nombre de la Estrategia
                    </label>

                    <input
                        type="text"
                        name="title"
                        required
                        value="<?= $task->title ?>"
                        placeholder="Ej. Implementar sistema de trofeos">

                </div>

                <!-- Description -->
                <div class="mb-6">

                    <label class="block mb-3 font-semibold text-yellow-400">
                        Descripción
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        placeholder="Describe la estrategia o tarea..."
                        class="..."><?= $task->description ?></textarea>

                </div>

                <!-- Priority -->
                <div class="mb-6">

                    <label class="block mb-3 font-semibold text-yellow-400">
                        Nivel del Partido
                    </label>

                    <select
                        name="priority"
                        class="w-full p-4 rounded-xl bg-slate-800 border border-green-500 text-gray">

                        <option value="friendly"
                            <?= $task->priority == 'friendly' ? 'selected' : '' ?>>
                            🟢 Partido Amistoso
                        </option>

                        <option value="regular"
                            <?= $task->priority == 'regular' ? 'selected' : '' ?>>
                            🟡 Partido Oficial
                        </option>

                        <option value="decisive"
                            <?= $task->priority == 'decisive' ? 'selected' : '' ?>>
                            🔴 Final del Mundial
                        </option>

                    </select>

                </div>

                <!-- Status -->
                <div class="mb-6">

                    <label class="block mb-3 font-semibold text-yellow-400">
                        Fase del Partido
                    </label>

                    <select
                        name="status"
                        class="w-full p-4 rounded-xl bg-slate-800 border border-green-500 text-gray">

                        <option value="bench"
                            <?= $task->status == 'bench' ? 'selected' : '' ?>>
                            🏁 Pre-Match
                        </option>

                        <option value="training"
                            <?= $task->status == 'training' ? 'selected' : '' ?>>
                            ⚽ Primer Tiempo
                        </option>

                        <option value="goal"
                            <?= $task->status == 'goal' ? 'selected' : '' ?>>
                            🏆 Victoria
                        </option>

                    </select>

                </div>

                <!-- Assigned Player -->
                <div class="mb-8">

                    <label class="block mb-3 font-semibold text-yellow-400">
                        Jugador Asignado
                    </label>

                    <select
                        name="assigned_to"
                        class="w-full p-4 rounded-xl bg-slate-800 border border-green-500 text-gray">

                        <option value="">
                            Sin asignar
                        </option>

                        <?php foreach($employees as $employee): ?>

                            <option
                                value="<?= $employee->id ?>"
                                <?= $task->assigned_to == $employee->id ? 'selected' : '' ?>>

                                <?= $employee->first_name ?>

                                <?php if(!empty($employee->last_name)): ?>
                                    <?= ' ' . $employee->last_name ?>
                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Buttons -->
                <div class="flex justify-center gap-4">

                    <a href="<?= base_url('index.php/task_manager') ?>"
                       class="bg-red-200 hover:bg-red-500 px-8 py-3 rounded-full font-semibold text-gray transition">

                        Volver
                    </a>

                    <button
                        type="submit"
                        class="bg-yellow-400 hover:bg-yellow-300 text-black font-bold px-10 py-3 rounded-full shadow-lg transform hover:scale-105 transition">

                        ⚽ Actualizar Estrategia

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>