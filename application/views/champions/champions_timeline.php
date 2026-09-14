<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Línea del Tiempo Campeones</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        fadeInUp: "fadeInUp 0.8s ease-out",
                    },
                    keyframes: {
                        fadeInUp: {
                            "0%": { opacity: 0, transform: "translateY(20px)" },
                            "100%": { opacity: 1, transform: "translateY(0)" },
                        },
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-gray-100">

<?php
$flags = [
    "Brasil" => "https://flagcdn.com/w40/br.png",
    "Alemania" => "https://flagcdn.com/w40/de.png",
    "Italia" => "https://flagcdn.com/w40/it.png",
    "Argentina" => "https://flagcdn.com/w40/ar.png",
    "Francia" => "https://flagcdn.com/w40/fr.png",
    "Uruguay" => "https://flagcdn.com/w40/uy.png",
    "España" => "https://flagcdn.com/w40/es.png",
    "Inglaterra" => "https://flagcdn.com/w40/gb-eng.png",
];
?>

<div class="max-w-4xl mx-auto py-10">

    <h1 class="text-4xl font-bold text-center mb-6">
        🌍 Línea del Tiempo Campeones
    </h1>

    <div class="bg-white shadow-lg rounded-xl p-6 mb-10 border-l-4 border-yellow-500 animate-fadeInUp">
        <h2 class="text-2xl font-semibold mb-2">🏆 País con Mayor Número de Campeonatos</h2>
        <p class="text-gray-700 text-lg">
            <img src="<?= $flags[$most_titles->champion] ?? '' ?>" class="inline-block w-10 h-6 rounded mr-2">
            <span class="font-bold text-yellow-600 text-xl"><?= $most_titles->champion ?></span>
            con
            <span class="font-bold"><?= $most_titles->titles ?></span> títulos.
        </p>
    </div>

    <div class="relative border-l-4 border-gray-300 ml-4">

        <?php foreach ($champions as $c): ?>
        <div class="mb-10 ml-6">

            <div class="absolute w-4 h-4 bg-yellow-500 rounded-full mt-1.5 -left-2 border-white border-2 animate-fadeInUp"></div>

            <div class="bg-white shadow-md rounded-lg p-4 hover:shadow-lg transition animate-fadeInUp">

                <h3 class="text-xl font-semibold"><?= $c->year ?></h3>

                <p class="text-gray-600 text-lg flex items-center gap-2 mt-1">
                    <img src="<?= $flags[$c->champion] ?? '' ?>" class="w-8 h-5 rounded shadow">
                    <?= $c->champion ?>
                </p>

            </div>

        </div>
        <?php endforeach; ?>

    </div>

</div>

</body>
</html>
