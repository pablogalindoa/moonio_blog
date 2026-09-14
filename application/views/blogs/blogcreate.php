<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog Post</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-purple-200 p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Crear Nueva Publicación</h2>

        <?php if (isset($error)) : ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('index.php/blog/store'); ?>" method="POST" enctype="multipart/form-data">
            
            <!-- Title -->
            <div class="form-group mb-4">
                <label class="block text-lg font-medium text-gray-700">Título</label>
                <input type="text" name="title" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <!-- Mundial -->
            <div class="form-group mb-4">
                <label class="block text-lg font-medium text-gray-700">Mundial</label>
                <select name="mundial" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="">Seleccione un mundial</option>
                    <?php foreach ($mundiales as $mund): ?>
                        <option value="<?= $mund->id_mundiales ?>">
                            <?= htmlspecialchars($mund->name . ' - ' . $mund->year) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Category -->
            <div class="form-group mb-4">
                <label class="block text-lg font-medium text-gray-700">Categoría</label>
                <select name="category" class="w-full p-2 border border-gray-300 rounded" required>
                    <option value="">Seleccione una categoría</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id_blog_categories ?>">
                            <?= htmlspecialchars($cat->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Content -->
            <div class="form-group mb-4">
                <label class="block text-lg font-medium text-gray-700">Contenido</label>
                <textarea name="content" class="w-full p-2 border border-gray-300 rounded" rows="5" required></textarea>
            </div>

            <!-- Image / Video -->
            <div class="form-group mb-4">
                <label class="block text-lg font-medium text-gray-700">
                    Subir Imagen o Video
                </label>

                <!-- Accept IMAGES + VIDEOS -->
                <input 
                    type="file" 
                    name="image" 
                    class="w-full p-2 border border-gray-300 rounded"
                    accept="image/*,video/mp4,video/quicktime,video/x-m4v,video/*"
                >
                <small class="text-gray-600">Formatos permitidos: JPG, PNG, MP4, MOV, AVI...</small>
            </div>

            <!-- Submit -->
            <button type="submit" class="px-4 py-2 bg-blue-400 text-white rounded">
                Enviar
            </button>

            <a href="<?= base_url('index.php/blog'); ?>" 
               class="no-underline bg-red-400 hover:bg-red-500 text-white py-1 px-3 rounded-md transition float-right">
               Regresar
            </a>

        </form>

    </div>
</body>
</html>
