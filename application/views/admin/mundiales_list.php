<h2 class="text-2xl font-bold mb-4">World Cups</h2>

<a href="<?= base_url('index.php/MundialesAdmin/create'); ?>" 
   class="px-4 py-2 bg-green-500 text-white rounded">Create New</a>

<table class="table-auto w-full mt-4 border">
    <thead>
        <tr>
            <th>Year</th>
            <th>Country</th>
            <th>Winner</th>
            <th>Enabled</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($mundiales as $wc): ?>
        <tr>
            <td><?= $wc->year; ?></td>
            <td><?= $wc->id_countries; ?></td>
            <td><?= $wc->id_winner; ?></td>
            <td><?= $wc->enable ? "Yes" : "No"; ?></td>

            <td>
                <img src="<?= base_url($wc->background_img); ?>" width="100">
            </td>

            <td>
                <?php if ($wc->enable): ?>
                    <a href="<?= base_url('index.php/MundialesAdmin/disable/' . $wc->id_mundiales); ?>" 
                       class="text-red-500">Disable</a>
                <?php else: ?>
                    <a href="<?= base_url('index.php/MundialesAdmin/enable/' . $wc->id_mundiales); ?>" 
                       class="text-green-500">Enable</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
