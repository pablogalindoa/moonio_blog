<div class="min-h-screen bg-gradient-to-br from-slate-950 via-indigo-950 to-purple-950 p-8 text-white">

    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-10">

            <div class="text-6xl mb-4">
                🚀
            </div>

            <h1 class="text-5xl font-extrabold text-purple-300">
                Nueva Tarea
            </h1>

            <p class="text-indigo-200 mt-3 text-lg">
                Crea una nueva actividad y asígnala a un integrante del equipo
            </p>

        </div>

        <!-- Mini Stats -->
        <div class="grid grid-cols-3 gap-4 mb-8">

            <div class="bg-indigo-900 rounded-2xl p-4 text-center shadow-lg border border-blue-500">
                <div class="text-3xl mb-2">📋</div>
                <p class="font-semibold">Tareas</p>
                <p class="text-xs text-indigo-200">Nuevas actividades</p>
            </div>

            <div class="bg-purple-900 rounded-2xl p-4 text-center shadow-lg border border-purple-500">
                <div class="text-3xl mb-2">🎯</div>
                <p class="font-semibold">Objetivos</p>
                <p class="text-xs text-purple-200">Metas por completar</p>
            </div>

            <div class="bg-slate-800 rounded-2xl p-4 text-center shadow-lg border border-pink-500">
                <div class="text-3xl mb-2">⚡</div>
                <p class="font-semibold">Prioridad</p>
                <p class="text-xs text-gray-300">Nivel de importancia</p>
            </div>

        </div>

        <!-- Form Card -->
        <div class="bg-slate-900/90 backdrop-blur-lg border-2 border-purple-500 rounded-3xl shadow-2xl p-10">

            <form action="<?= base_url('index.php/tasks/store') ?>" method="POST">

                <label for="taskGroup">Grupo</label>
                <select id="taskGroup" name="id_chats" required class="w-full p-4 rounded-xl bg-slate-800 text-white">
                    <option value="">Selecciona un grupo</option>
                    <?php foreach ($groups as $group): ?>
                    <option value="<?= (int)$group->id_chats ?>"><?= html_escape($group->name) ?></option>
                    <?php endforeach; ?>
                </select>
                <!-- Title -->
                <div class="mb-6">

                    <label class="block mb-3 font-semibold text-purple-300">
                        Nombre de la Tarea
                    </label>

                    <input
                        type="text"
                        name="title"
                        required
                        placeholder="Ej. Implementar sistema de trofeos"
                        class="w-full p-4 rounded-xl bg-slate-800 border-2 border-indigo-500 text-white focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-purple-400">

                </div>

                <!-- Description -->
                <div class="mb-6">

                    <label class="block mb-3 font-semibold text-purple-300">
                        Descripción
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        placeholder="Describe la tarea o actividad..."
                        class="w-full p-4 rounded-xl bg-slate-800 border-2 border-indigo-500 text-white focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-purple-400"></textarea>

                </div>

                <!-- Priority -->
                <div class="mb-6">

                    <label class="block mb-3 font-semibold text-purple-300">
                        Nivel de Prioridad
                    </label>

                    <select
                        name="priority"
                        class="w-full p-4 rounded-xl bg-slate-800 border-2 border-indigo-500 text-white focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-purple-400">

                        <option value="friendly">
                            🔵 Prioridad Baja
                        </option>

                        <option value="regular">
                            🟣 Prioridad Media
                        </option>

                        <option value="decisive">
                            🔴 Prioridad Alta
                        </option>

                    </select>

                </div>

                <!-- Status -->
                <div class="mb-6">

                    <label class="block mb-3 font-semibold text-purple-300">
                        Estado de la Tarea
                    </label>

                    <select
                        name="status"
                        class="w-full p-4 rounded-xl bg-slate-800 border-2 border-indigo-500 text-white focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-purple-400">

                        <option value="bench">
                            🌑 Pendiente
                        </option>

                        <option value="training">
                            🌌 En Progreso
                        </option>

                        <option value="goal">
                            ✨ Completada
                        </option>

                    </select>

                </div>

                <!-- Assigned User -->
                <div class="mb-8">

                    <label class="block mb-3 font-semibold text-purple-300">
                        Usuario Asignado
                    </label>

                    <select
                        name="assigned_to" id="taskAssignee" required
                        class="w-full p-4 rounded-xl bg-slate-800 border-2 border-indigo-500 text-white focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-purple-400">

                        <option value="">
                            Sin asignar
                        </option>

                        <?php foreach($employees as $employee): ?>

                            <option value="<?= $employee->id ?>">

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
                       class="bg-slate-700 hover:bg-slate-600 px-8 py-3 rounded-full font-semibold text-white transition no-underline">

                        Volver
                    </a>

                    <button
                        type="submit"
                        class="bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-400 hover:to-blue-400 text-white font-bold px-10 py-3 rounded-full shadow-lg transform hover:scale-105 transition">

                        🚀 Guardar Tarea

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
<script>
const groupMembers = <?= json_encode($group_members, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
function selectGroupMembers() {
    const select = document.getElementById('taskAssignee');
    select.replaceChildren(new Option('Selecciona un integrante', ''));
    (groupMembers[document.getElementById('taskGroup').value] || []).forEach(member => select.add(new Option(member.first_name + ' ' + member.last_name, member.id)));
}
document.getElementById('taskGroup').addEventListener('change', selectGroupMembers);
selectGroupMembers();
</script>
