<?php
// File path from DB (already includes 'uploads/')
$media = $blogs->image;

// Determine extension and type
$ext = strtolower(pathinfo($media, PATHINFO_EXTENSION));
$is_video = in_array($ext, ['mp4','mov','avi','mkv']);

// Thumbnail logic
$thumbnail = $is_video 
    ? base_url('assets/video_placeholder.png') // placeholder for videos
    : base_url($media);
?>

<div class="container-fluid" style="background-color: #E6E6FA; min-height: 100vh; padding: 20px;">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card shadow sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h2 class="card-title text-center">M O O N I O</h2>
                    <img src="<?= base_url($this->session->userdata('profile_picture')); ?>" 
                         alt="Profile" class="w-28 h-28 rounded-full border border-gray-300 shadow-md mx-auto mt-2 mb-4">
                    <p class="text-gray-700 mb-2 text-center">
                        ¡Bienvenid@ <strong><?= $this->session->userdata('first_name'); ?></strong>!
                    </p>
                    <form id="liveSearchForm" class="mb-3">
                        <input type="text" name="query" id="liveSearchInputAdmin" class="form-control" placeholder="Buscar..." />
                        <div id="liveResultsAdmin" class="mt-3"></div>
                    </form><br>
                    <ul class="list-unstyled items-center">
                        <li><a href="<?= base_url('index.php/home'); ?>" class="btn btn-purple w-full mb-3">H O M E</a></li>
                        <li><a href="<?= base_url('index.php/blog/create'); ?>" class="btn btn-blue w-full mb-3">Nueva Publicación</a></li>
                        <li><a href="<?= base_url('index.php/blog/profile'); ?>" class="btn btn-purple w-full mb-3">Ajustes</a></li>
                        <li><a href="<?= base_url('index.php/employee/logout'); ?>" class="btn btn-blue w-full">Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 mt-4">
            <div class="card shadow-lg">
                <div class="card-header text-center">
                    <h1><?= htmlspecialchars($blogs->title); ?></h1>
                </div>

                <div class="card shadow-sm rounded-lg m-3 p-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="text-muted"><?= htmlspecialchars($blogs->first_name); ?></h2>
                            <p><?= nl2br(htmlspecialchars($blogs->content)); ?></p>
                            <small class="text-muted"><?= htmlspecialchars($blogs->created_at); ?></small>
                        </div>

                        <div class="col-md-6 text-center">
                            <img 
                                src="<?= $thumbnail ?>" 
                                class="img-fluid rounded blog-img shadow cursor-pointer"
                                style="max-height:400px; object-fit:contain;"
                                onclick="openModal('<?= base_url($media) ?>', <?= $is_video ? 'true' : 'false' ?>)"
                            >
                        </div>
                    </div>

                    <h3 class="mt-4">Comentarios</h3>
                    <div class="mb-3">
                        <?php if(!empty($comments)): ?>
                            <?php foreach($comments as $c): ?>
                                <div class="p-2 mb-2 border rounded">
                                    <strong><?= htmlspecialchars($c->first_name . ' ' . $c->last_name); ?>:</strong>
                                    <p><?= nl2br(htmlspecialchars($c->comment)); ?></p>
                                    <small class="text-muted"><?= $c->created_at; ?></small>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No hay comentarios aún. ¡Se el primero!</p>
                        <?php endif; ?>
                    </div>

                    <form method="post" action="<?= base_url('index.php/blog/add_comment'); ?>">
                        <input type="hidden" name="blog_id" value="<?= $blogs->id_blogs_admin; ?>">
                        <textarea name="comment" class="form-control mb-2" placeholder="Write a comment..." required></textarea>
                        <button type="submit" class="btn btn-primary">Agregar Comentario</button>
                    </form>


                    <a href="<?= base_url('index.php/blog/delete_request/'.$blogs->id_blogs_admin); ?>" 
                       class="btn btn-danger mt-3" 
                       onclick="return confirm('¿Seguro que quieres solicitar la eliminación de esta publicación?');">
                        Solicitar Eliminación
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="mediaModal" class="fixed inset-0 hidden items-center justify-center modal-bg z-50">
    <div class="bg-black p-4 rounded relative max-w-3xl mx-auto">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-white text-3xl">&times;</button>
        <img id="modalImage" class="max-h-screen mx-auto rounded hidden">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('liveSearchForm');
    const input = document.getElementById('liveSearchInputAdmin');
    const results = document.getElementById('liveResultsAdmin');

    // Prevent default form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
    });

    input.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length === 0) {
            results.innerHTML = '';
            return;
        }

        fetch(`<?= base_url('index.php/blog/liveSearch'); ?>?query=${encodeURIComponent(query)}`)
            .then(response => response.text())
            .then(data => {
                results.innerHTML = data;
            });
    });
});
</script>
