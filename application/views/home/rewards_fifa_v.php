<div class="min-h-screen bg-gradient-to-br from-slate-900 via-green-900 to-slate-800 text-white p-8">

    <!-- Main Container -->
    <div class="max-w-6xl mx-auto bg-slate-900/80 backdrop-blur-lg rounded-3xl shadow-2xl p-10 border border-green-700">

        <!-- Header -->
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-4xl font-extrabold text-yellow-400">
                    Logros y Recompensas 🏆
                </h1>
                <p class="text-gray-400 mt-2">
                    Compite. Gana puntos. Asciende de división.
                </p>
            </div>

            <div class="bg-yellow-400 text-black px-6 py-3 rounded-xl font-bold shadow-lg">
                División: <?= $employee->division ?>
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
                <p class="text-gray-400 mt-2">Retos Logrados ⚽</p>
            </div>

            <div class="bg-slate-800 border border-green-700 rounded-2xl p-6 text-center shadow-md">
                <h2 class="text-4xl font-bold text-green-400">18</h2>
                <p class="text-gray-400 mt-2">Video Conferencias Atendidas 🎥</p>
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
