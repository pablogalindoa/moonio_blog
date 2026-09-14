<div class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">

        <h1 class="text-3xl font-bold mb-4 text-center">
            Mundial <?= $mundial->year ?>
        </h1>

        <?php if (!empty($mundial->background_img)) : ?>
            <img src="<?= base_url($mundial->background_img); ?>" 
                 class="w-full rounded shadow mb-4">
        <?php endif; ?>

        <p><strong>País Anfitrión:</strong> <?= $mundial->id_countries; ?></p>
        <p><strong>Ganador:</strong> <?= $mundial->id_winner; ?></p>

        <div class="mt-6 p-4 bg-blue-100 border rounded text-center">
            <a href="<?= base_url('index.php/blog/request/'.$mundial->id_mundiales); ?>"
               class="bg-blue-600 text-white px-6 py-3 rounded shadow hover:bg-blue-800">
                Crear Publicación para este Mundial
            </a>
        </div>

    </div>
</div>
