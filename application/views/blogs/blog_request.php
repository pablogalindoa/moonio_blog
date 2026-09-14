<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Publicación</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Modal Styling -->
    <style>
        .modal-bg {
            background: rgba(0,0,0,0.8);
        }
    </style>
</head>

<body class="bg-gray-100 p-8">

<a href="<?= base_url('index.php/home'); ?>" 
   class="no-underline bg-red-400 hover:bg-black text-white py-3 px-4 rounded-md transition float-left">
   Regresar
</a>

<div class="max-w-3xl mx-auto bg-purple-200 p-6 rounded-lg shadow-md mt-10">
    <h2 class="text-2xl font-bold mb-4 text-center">Solicitudes de Publicación</h2>

    <?php if (isset($error)): ?>
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            <?= $error; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($requests)): ?>
        <div class="bg-blue-200 text-blue-800 p-4 rounded text-center mb-4">
            No hay solicitudes de publicación por el momento.
        </div>
    <?php endif; ?>


    <?php foreach ($requests as $req): ?>

        <?php
            // Normalize media path (some records were missing "uploads/")
            $media = $req->image;
            if (strpos($media, 'uploads/') === false) {
                $media = 'uploads/' . $media;
            }

            // Detect file type
            $ext = strtolower(pathinfo($media, PATHINFO_EXTENSION));
            $is_video = in_array($ext, ['mp4', 'mov', 'avi', 'mkv', 'webm']);

            // Decide thumbnail
            $thumbnail = $is_video
                ? base_url('assets/video_placeholder.png')
                : base_url($media);
        ?>

        <div class="p-4 bg-white my-4 rounded shadow">

            <h2 class="text-xl font-bold"><?= $req->first_name . " " . $req->last_name; ?></h2>

            <p><strong>Email:</strong> <?= $req->email; ?></p>
            <p><strong>Mundial:</strong> <?= $req->year; ?></p>

            <!-- Thumbnail -->
            <div class="mt-3">
                <img 
                    src="<?= $thumbnail ?>" 
                    class="w-48 rounded shadow cursor-pointer"
                    onclick="openModal('<?= base_url($media) ?>', <?= $is_video ? 'true' : 'false' ?>)"
                >
            </div>

            <p class="mt-3"><strong>Contenido:</strong><br> <?= nl2br(htmlspecialchars($req->content)); ?></p>
            <p><strong>Tipo de request:</strong> <?= $req->request_type; ?></p>

            <!-- Accept / Delete -->
            <form action="<?= base_url('index.php/blog/handle_request'); ?>" method="POST" class="mt-3">
                <input type="hidden" name="request_id" value="<?= $req->id_requests; ?>">

                <button name="action" value="accept"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded">
                    Aceptar
                </button>

                <button name="action" value="delete"
                    class="px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded ml-2">
                    Eliminar
                </button>
            </form>

        </div>

    <?php endforeach; ?>
</div>


<!-- Modal Viewer -->
<div id="mediaModal" class="fixed inset-0 hidden items-center justify-center modal-bg z-50">
    <div class="bg-black p-4 rounded relative max-w-3xl mx-auto">

        <!-- Close button -->
        <button onclick="closeModal()" 
                class="absolute top-2 right-2 text-white text-3xl">&times;</button>

        <!-- Image -->
        <img id="modalImage" class="max-h-screen mx-auto rounded hidden">

        <!-- Video -->
        <video id="modalVideo" controls class="max-h-screen mx-auto rounded hidden">
            <source id="modalVideoSource" src="" type="video/mp4">
        </video>
    </div>
</div>

<script>
function openModal(src, isVideo) {
    const modal = document.getElementById("mediaModal");
    const img = document.getElementById("modalImage");
    const video = document.getElementById("modalVideo");
    const videoSrc = document.getElementById("modalVideoSource");

    modal.classList.remove("hidden");

    if (isVideo) {
        img.classList.add("hidden");
        video.classList.remove("hidden");
        videoSrc.src = src;
        video.load();
    } else {
        video.classList.add("hidden");
        img.classList.remove("hidden");
        img.src = src;
    }
}

function closeModal() {
    const modal = document.getElementById("mediaModal");
    const video = document.getElementById("modalVideo");

    video.pause();
    modal.classList.add("hidden");
}
</script>

</body>
</html>
