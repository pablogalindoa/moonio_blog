<div class="min-h-screen bg-gradient-to-br from-slate-900 via-green-900 to-slate-800 p-8 text-white">

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto bg-slate-900/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-green-700">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-yellow-400">
                    Tablero táctico ⚽
                </h1>
                <p class="text-gray-400 mt-1">
                    Gestiona las jugadas estratégicas rumbo al Mundial 🏆
                </p>
            </div>

            <a href="<?= base_url('index.php/tasks/create') ?>"
            class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-3 rounded-full shadow-md font-semibold transition no-underline">
                + Nueva Estrategia
            </a>
        </div>

        <!-- Kanban Board -->
        <div class="grid grid-cols-3 gap-6">

            <!-- Pre-Match Column -->
            <div class="bg-slate-950 rounded-2xl p-6 shadow-inner border border-green-700">
                <h2 class="text-lg font-semibold text-green-400 mb-4">
                    Pre-Match 🏁
                </h2>

                <div class="flex flex-col gap-4">

                    <?php foreach($bench_tasks as $task): ?>

                        <div class="bg-slate-800 rounded-2xl p-4 shadow-md border border-green-600">

                            <h3 class="font-semibold text-yellow-400">
                                <?= $task->title ?>
                            </h3>

                            <p class="text-sm text-gray-300 mt-2">
                                <?= $task->description ?>
                            </p>

                            <?php
                            $priorityClass = '';

                            switch($task->priority)
                            {
                                case 'friendly':
                                    $priorityClass = 'bg-green-600';
                                    break;

                                case 'regular':
                                    $priorityClass = 'bg-yellow-400 text-black';
                                    break;

                                case 'decisive':
                                    $priorityClass = 'bg-red-600';
                                    break;
                            }
                            ?>

                            <span class="inline-block mt-3 text-xs text-white px-3 py-1 rounded-full <?= $priorityClass ?>">
                                <?= ucfirst($task->priority) ?>
                            </span>
                            <div class="flex gap-2 mt-4">

                                <a href="<?= base_url('index.php/tasks/advance/'.$task->id_task) ?>"
                                class="bg-green-600 hover:bg-green-500 text-white text-xs px-3 py-1 rounded">

                                    ⚽ Avanzar
                                </a>

                                <a href="<?= base_url('index.php/tasks/edit/'.$task->id_task) ?>"
                                class="bg-yellow-500 hover:bg-yellow-400 text-black text-xs px-3 py-1 rounded">

                                    ✏️ Editar
                                </a>

                                <a href="<?= base_url('index.php/tasks/delete/'.$task->id_task) ?>"
                                onclick="return confirm('¿Eliminar esta estrategia?')"
                                class="bg-red-600 hover:bg-red-500 text-white text-xs px-3 py-1 rounded">

                                    🗑 Eliminar
                                </a>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>

            <!-- First Half Column -->
            <div class="bg-slate-950 rounded-2xl p-6 shadow-inner border border-green-700">
                <h2 class="text-lg font-semibold text-green-400 mb-4">
                    First Half ⚽
                </h2>

                <div class="flex flex-col gap-4">

                    <?php foreach($training_tasks as $task): ?>

                        <div class="bg-green-800 rounded-2xl p-4 shadow-lg border border-yellow-400">

                            <h3 class="font-semibold text-white">
                                <?= $task->title ?>
                            </h3>

                            <p class="text-sm text-green-100 mt-2">
                                <?= $task->description ?>
                            </p>

                            <span class="inline-block mt-3 text-xs bg-slate-900 text-yellow-400 px-3 py-1 rounded-full border border-yellow-400">
                                <?= ucfirst($task->priority) ?>
                            </span>

                            <div class="flex gap-2 mt-4">

                                <a href="<?= base_url('index.php/tasks/advance/'.$task->id_task) ?>"
                                class="bg-green-600 hover:bg-green-500 text-white text-xs px-3 py-1 rounded">

                                    ⚽ Avanzar
                                </a>

                                <a href="<?= base_url('index.php/tasks/edit/'.$task->id_task) ?>"
                                class="bg-yellow-500 hover:bg-yellow-400 text-black text-xs px-3 py-1 rounded">

                                    ✏️ Editar
                                </a>

                                <a href="<?= base_url('index.php/tasks/delete/'.$task->id_task) ?>"
                                onclick="return confirm('¿Eliminar esta estrategia?')"
                                class="bg-red-600 hover:bg-red-500 text-white text-xs px-3 py-1 rounded">

                                    🗑 Eliminar
                                </a>
                                

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>

            <!-- Victory Column -->
            <div class="bg-slate-950 rounded-2xl p-6 shadow-inner border border-green-700">
                <h2 class="text-lg font-semibold text-green-400 mb-4">
                    Victory 🏆
                </h2>

                <div class="flex flex-col gap-4">

                    <?php foreach($goal_tasks as $task): ?>

                        <div class="bg-slate-800 rounded-2xl p-4 shadow-sm opacity-80 border border-green-600">

                            <h3 class="font-semibold text-gray-400 line-through">
                                <?= $task->title ?>
                            </h3>

                            <p class="text-sm text-gray-500 mt-2">
                                <?= $task->description ?>
                            </p>

                            <span class="inline-block mt-3 text-xs bg-green-600 text-white px-3 py-1 rounded-full">
                                Completado
                            </span>

                            <div class="flex gap-2 mt-4">

                                <a href="<?= base_url('index.php/tasks/edit/'.$task->id_task) ?>"
                                class="bg-yellow-500 hover:bg-yellow-400 text-black text-xs px-3 py-1 rounded">

                                    ✏️ Editar
                                </a>

                                <a href="<?= base_url('index.php/tasks/delete/'.$task->id_task) ?>"
                                onclick="return confirm('¿Eliminar esta estrategia?')"
                                class="bg-red-600 hover:bg-red-500 text-white text-xs px-3 py-1 rounded">

                                    🗑 Eliminar
                                </a>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>

        </div>

    </div>

</div>
