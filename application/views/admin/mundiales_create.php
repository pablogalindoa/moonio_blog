<h2 class="text-2xl font-bold mb-4">Create World Cup</h2>

<form method="POST" action="<?= base_url('index.php/MundialesAdmin/store'); ?>" 
      enctype="multipart/form-data">

    <label>Year:</label>
    <input type="number" name="year" class="border p-2 w-full" required>

    <label>Host Country:</label>
    <select name="id_countries" class="border p-2 w-full" required>
        <option value="">-- Select Country --</option>
        <?php foreach ($countries as $c): ?>
            <option value="<?= $c->id_countries; ?>">
                <?= $c->name; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Winner:</label>
    <select name="id_winner" class="border p-2 w-full" required>
        <option value="">-- Select Winner --</option>
        <?php foreach ($countries as $c): ?>
            <option value="<?= $c->id_countries; ?>">
                <?= $c->name; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Background Image:</label>
    <input type="file" name="background_img" class="border p-2 w-full" required>

    <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Save</button>
</form>
