<div class="min-h-screen bg-gradient-to-br from-slate-900 via-green-900 to-slate-800 text-white p-8">

    <!-- Main Container -->
    <div class="max-w-6xl mx-auto bg-slate-900/80 backdrop-blur-lg rounded-3xl shadow-2xl p-10 border border-green-700">

        <!-- Header -->
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-4xl font-extrabold text-yellow-400">
                    Logros y Recompensas 🌙
                </h1>
                <p class="text-gray-400 mt-2">
                    Mantente Activo. Gana recompensas 💜
                </p>
            </div>

            <div class="bg-yellow-400 text-black px-6 py-3 rounded-xl font-bold shadow-lg">
                Nivel: <?= $employee->division ?>
            </div>
        </div>

        <!-- Points Progress -->
        <div class="mb-12">
            <div class="flex justify-between text-sm text-gray-300 mb-2">
                <span>Puntos de la temporada</span>
                <span><?= $employee->points ?> / 1000 PTS</span>
            </div>
            <?php
            $percentage = min(($employee->points / 1000) * 100, 100);
            ?>

            <div class="bg-green-500 h-5 rounded-full"
                style="width: <?= $percentage ?>%;">
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-3 gap-6 mb-12">

            <div class="bg-slate-800 border border-green-700 rounded-2xl p-6 text-center shadow-md">
                <h2 class="text-4xl font-bold text-green-400">
                    <?= $employee->total_trophies ?>
                </h2>
                <p class="text-gray-400 mt-2">Retos Logrados🌙</p>
            </div>

            <div class="bg-slate-800 border border-green-700 rounded-2xl p-6 text-center shadow-md">
                <h2 class="text-4xl font-bold text-green-400">—</h2>
                <p class="text-gray-400 mt-2">Videollamadas (sin contador) 🎥</p>
            </div>

            <div class="bg-slate-800 border border-green-700 rounded-2xl p-6 text-center shadow-md">
                <h2 class="text-4xl font-bold text-green-400">
                    <?= $employee->total_messages ?>
                </h2>
                <p class="text-gray-400 mt-2">Mensajes Enviados 💬</p>
            </div>

        </div>

        <!-- Trophy Cabinet -->
        <h2 class="text-2xl font-bold text-yellow-400 mb-6">
            Gabinete de Trofeos 🏅
        </h2>

       <div class="grid grid-cols-4 gap-6">

        <?php foreach($trophies as $trophy): ?>

            <div class="
                bg-slate-800
                rounded-2xl
                p-6
                text-center

                <?= $trophy->unlocked
                    ? 'border border-yellow-400 shadow-lg'
                    : 'border border-slate-700 opacity-50'
                ?>
            ">

                <div class="text-5xl mb-3">
                    <?= $trophy->icon ?>
                </div>

                <h3 class="
                    font-bold
                    <?= $trophy->unlocked
                        ? 'text-yellow-400'
                        : 'text-gray-400'
                    ?>
                ">
                    <?= $trophy->trophy_name ?>
                </h3>

                <p class="text-sm text-gray-400 mt-2">
                    <?= $trophy->description ?>
                </p>

            </div>

        <?php endforeach; ?>

    </div>

    </div>

</div>

<link rel="stylesheet" href="<?= base_url('assets/css/moonio-reward-profiles.css') ?>">
<section class="moon-ranking" aria-labelledby="rankingTitle" data-base-url="<?= html_escape(base_url()) ?>">
    <h2 id="rankingTitle">Clasificación de todos los usuarios</h2>
    <p>Selecciona un usuario para ver sus recompensas obtenidas.</p>
    <p class="moon-ranking-rules">Registro: 10 puntos · Inicio de sesión: 5 puntos · Referir un nuevo usuario: 25 puntos.</p>
    <table>
        <thead><tr><th scope="col">Usuario</th><th scope="col">Puntos</th></tr></thead>
        <tbody>
        <?php foreach ($ranking as $position => $rank): ?>
        <tr>
            <td><button type="button" class="moon-reward-user" data-profile-url="<?= site_url('rewards/profile/'.(int)$rank->id) ?>" aria-haspopup="dialog" aria-controls="rewardProfile">
                <span class="moon-ranking-position" aria-hidden="true"><?= $position + 1 ?></span>
                <span><?= html_escape($rank->first_name.' '.$rank->last_name) ?></span>
                <span class="moon-profile-hint" aria-hidden="true">Ver logros ↗</span>
            </button></td>
            <td class="moon-ranking-points"><?= (int)$rank->points ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<dialog id="rewardProfile" class="moon-reward-dialog" aria-labelledby="rewardProfileTitle">
    <header class="moon-reward-dialog-header">
        <h2 id="rewardProfileTitle">Perfil de recompensas</h2>
        <button type="button" class="moon-reward-close" aria-label="Cerrar perfil">✕</button>
    </header>
    <div id="rewardProfileContent" aria-live="polite" aria-busy="false"></div>
</dialog>
<script src="<?= base_url('assets/js/moonio-reward-profiles.js') ?>" defer></script>
