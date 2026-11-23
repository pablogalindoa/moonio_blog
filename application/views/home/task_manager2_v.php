<div class="min-h-screen bg-gradient-to-br from-slate-950 via-indigo-950 to-purple-950 p-8 text-white">

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto bg-slate-900/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-purple-700">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-purple-300">
                    Centro de Tareas 🚀
                </h1>

                <p class="text-gray-400 mt-1">
                    Organiza, prioriza y completa tus actividades
                </p>
            </div>

            <a href="<?= base_url('index.php/tasks/create') ?>"
               class="bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-400 hover:to-blue-400 text-white px-6 py-3 rounded-full shadow-md font-semibold transition no-underline">
                + Nueva Tarea
            </a>
        </div>

        <!-- Kanban Board -->
        <div class="grid grid-cols-3 gap-6">

            <!-- Pending Column -->
            <div class="bg-slate-950 rounded-2xl p-6 shadow-inner border border-indigo-700">

                <h2 class="text-lg font-semibold text-blue-300 mb-4">
                    Pendientes 🌑
                </h2>

                <div class="flex flex-col gap-4">

                    <?php foreach($bench_tasks as $task): ?>

                        <div class="bg-slate-800 rounded-2xl p-4 shadow-md border border-indigo-600">

                            <h3 class="font-semibold text-purple-300">
                                <?= html_escape($task->title) ?>
                                <small style="display:block"><?= html_escape($task->group_name ?: 'Tarea anterior sin grupo') ?> · <?= html_escape($task->assignee_name ?: 'Sin asignar') ?></small>
                            </h3>

                            <p class="text-sm text-gray-300 mt-2">
                                <?= $task->description ?>
                            </p>

                            <?php
                            $priorityClass = '';

                            switch($task->priority)
                            {
                                case 'friendly':
                                    $priorityClass = 'bg-blue-600';
                                    break;

                                case 'regular':
                                    $priorityClass = 'bg-purple-600';
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
                                   class="bg-blue-600 hover:bg-blue-500 text-white text-xs px-3 py-1 rounded">

                                    ➜ Avanzar
                                </a>

                                <a href="<?= base_url('index.php/tasks/edit/'.$task->id_task) ?>"
                                   class="bg-purple-600 hover:bg-purple-500 text-white text-xs px-3 py-1 rounded">

                                    ✏️ Editar
                                </a>

                                <a href="<?= base_url('index.php/tasks/delete/'.$task->id_task) ?>"
                                   onclick="return confirm('¿Eliminar esta tarea?')"
                                   class="bg-red-600 hover:bg-red-500 text-white text-xs px-3 py-1 rounded">

                                    🗑 Eliminar
                                </a>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>

            <!-- In Progress Column -->
            <div class="bg-slate-950 rounded-2xl p-6 shadow-inner border border-purple-700">

                <h2 class="text-lg font-semibold text-purple-300 mb-4">
                    En Progreso 🌌
                </h2>

                <div class="flex flex-col gap-4">

                    <?php foreach($training_tasks as $task): ?>

                        <div class="bg-indigo-950 rounded-2xl p-4 shadow-lg border border-purple-500">

                            <h3 class="font-semibold text-white">
                                <?= html_escape($task->title) ?>
                                <small style="display:block"><?= html_escape($task->group_name ?: 'Tarea anterior sin grupo') ?> · <?= html_escape($task->assignee_name ?: 'Sin asignar') ?></small>
                            </h3>

                            <p class="text-sm text-indigo-200 mt-2">
                                <?= $task->description ?>
                            </p>

                            <span class="inline-block mt-3 text-xs bg-slate-900 text-purple-300 px-3 py-1 rounded-full border border-purple-500">
                                <?= ucfirst($task->priority) ?>
                            </span>

                            <div class="flex gap-2 mt-4">

                                <a href="<?= base_url('index.php/tasks/advance/'.$task->id_task) ?>"
                                   class="bg-blue-600 hover:bg-blue-500 text-white text-xs px-3 py-1 rounded">

                                    ➜ Avanzar
                                </a>

                                <a href="<?= base_url('index.php/tasks/edit/'.$task->id_task) ?>"
                                   class="bg-purple-600 hover:bg-purple-500 text-white text-xs px-3 py-1 rounded">

                                    ✏️ Editar
                                </a>

                                <a href="<?= base_url('index.php/tasks/delete/'.$task->id_task) ?>"
                                   onclick="return confirm('¿Eliminar esta tarea?')"
                                   class="bg-red-600 hover:bg-red-500 text-white text-xs px-3 py-1 rounded">

                                    🗑 Eliminar
                                </a>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>

            <!-- Completed Column -->
            <div class="bg-slate-950 rounded-2xl p-6 shadow-inner border border-blue-700">

                <h2 class="text-lg font-semibold text-blue-300 mb-4">
                    Completadas ✨
                </h2>

                <div class="flex flex-col gap-4">

                    <?php foreach($goal_tasks as $task): ?>

                        <div class="bg-slate-800 rounded-2xl p-4 shadow-sm opacity-80 border border-blue-600">

                            <h3 class="font-semibold text-gray-400 line-through">
                                <?= html_escape($task->title) ?>
                                <small style="display:block"><?= html_escape($task->group_name ?: 'Tarea anterior sin grupo') ?> · <?= html_escape($task->assignee_name ?: 'Sin asignar') ?></small>
                            </h3>

                            <p class="text-sm text-gray-500 mt-2">
                                <?= $task->description ?>
                            </p>

                            <span class="inline-block mt-3 text-xs bg-blue-700 text-white px-3 py-1 rounded-full">
                                Completado
                            </span>

                            <div class="flex gap-2 mt-4">

                                <a href="<?= base_url('index.php/tasks/edit/'.$task->id_task) ?>"
                                   class="bg-purple-600 hover:bg-purple-500 text-white text-xs px-3 py-1 rounded">

                                    ✏️ Editar
                                </a>

                                <a href="<?= base_url('index.php/tasks/delete/'.$task->id_task) ?>"
                                   onclick="return confirm('¿Eliminar esta tarea?')"
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