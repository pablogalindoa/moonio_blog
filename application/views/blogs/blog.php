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
                        Bienvenid@, <strong><?= $this->session->userdata('first_name'); ?></strong>!
                    </p> <br>
                    <form id="liveSearchForm" class="mb-3">
                        <input type="text" name="query" id="liveSearchInputAdmin" class="form-control" placeholder="Buscar..." />
                        <div id="liveResultsAdmin" class="mt-3"></div>
                    </form><br>
                    <!-- --- Filter Form --- -->
                    <form method="get" action="<?= base_url('index.php/blog'); ?>" class="mb-3">
                        <h5>Filtrar Publicaciones</h5>

                        <!-- Year Filter -->
                        <h6>Ordenar publicaciones:</h6>
                        <div class="mb-2">
                            <!-- Date buttons -->
                            <a href="<?= base_url('index.php/blog?sort=date_desc'); ?>" 
                            class="btn btn-sm btn-primary mb-1 <?= isset($filters['sort']) && $filters['sort']=='date_desc' ? 'active' : '' ?>">Más reciente primero</a>
                            <a href="<?= base_url('index.php/blog?sort=date_asc'); ?>" 
                            class="btn btn-sm btn-secondary mb-1 <?= isset($filters['sort']) && $filters['sort']=='date_asc' ? 'active' : '' ?>">Más antiguo primero</a>
                        </div>

                        <!-- Likes Select -->
                        <!-- <form method="get" action="<?= base_url('index.php/blog'); ?>">
                            <div class="mb-2">
                                <label>Ordenar por</label>
                                <select name="sort" class="form-control" onchange="this.form.submit()">
                                    <option value="">Fecha por defecto</option>
                                    <option value="likes" <?= isset($filters['sort']) && $filters['sort']=='likes' ? 'selected' : '' ?>>Más Me Gusta</option>
                                </select>
                            </div>
                        </form> -->

                        <button type="submit" class="btn btn-primary w-full mt-2">Filtrar</button>
                    </form>

                    <!-- Other Links -->
                    <ul class="list-unstyled mt-3">
                        <br>
                        <li><a href="<?= base_url('index.php/blog/blog_my_posts/' . $this->session->userdata('id')); ?>" class="no-underline bg-purple-300 text-white py-3 px-9 rounded-lg shadow hover:bg-purple-600 transition duration-300">Mis Publicaciones</a></li><br><br>
                        <li><a href="<?= base_url('index.php/blog/create'); ?>"class="no-underline bg-blue-300 hover:bg-blue-400 text-white py-3 px-8 rounded-md transition text-center">Nueva Publicación</a></li><br><br>
                        <li><a href="<?= base_url('index.php/blog/profile'); ?>" class="no-underline bg-purple-300 text-white py-3 px-9 rounded-lg shadow hover:bg-purple-600 transition duration-300">Ajustes</a></li><br><br>
                        <li><a href="<?= base_url('index.php/home'); ?>" class="no-underline bg-blue-300 hover:bg-blue-400 text-white py-3 px-8 rounded-md transition text-center">Página Principal</a></li><br><br>
                        <li><a href="<?= base_url('index.php/employee/logout'); ?>" class="no-underline bg-purple-300 text-white py-3 px-9 rounded-lg shadow hover:bg-purple-600 transition duration-300">Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-9 mt-4">
            <div class="card shadow-lg">
                <div class="card-header text-center">
                    <h1>H O M E</h1>
                    <?php if ($this->session->flashdata('status')) : ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('status') ?></div>
                    <?php endif; ?>
                </div>
                <div class="row px-3 mt-3">
                    <?php foreach ($blogs as $row) : ?>

                        <?php
                        $file = $row->image;
                        $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        $is_video = in_array($ext, ['mp4','mov','avi','mkv']);

                        // Video thumbnail placeholder
                        $thumbnail = $is_video 
                            ? base_url('assets/img/video-thumb.png')   // <- you can change this path later
                            : base_url($row->image);
                        ?>

                        <div class="col-md-6 col-lg-4 col-xl-3 mb-4">

                            <!-- Wrap whole card in link -->
                            <a href="<?= base_url('index.php/blog/blog_individual_admin/' . $row->id_blogs_admin); ?>" 
                            class="text-decoration-none text-dark">

                                <div class="card h-100 shadow-sm rounded-lg text-center">

                                    <!-- THUMBNAIL WITH MODAL -->
                                    <img src="<?= $thumbnail ?>"
                                        class="card-img-top mx-auto"
                                        style="width: 100%; max-height: 300px; object-fit: cover; cursor:pointer;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#mediaModal<?= $row->id_blogs_admin ?>">
                                    
                                    <div class="card-body">
                                        <h4 class="card-title"><?= htmlspecialchars($row->title); ?></h4>
                                        <h6 class="card-author"><?= htmlspecialchars($row->first_name); ?></h6>
                                        <p class="card-text"><?= nl2br(htmlspecialchars(substr($row->content,0,100))) . '...'; ?></p>
                                        <p class="card-text">
                                            <small class="text-muted"><?= htmlspecialchars($row->created_at); ?></small>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- MODAL FOR FULL MEDIA -->
                        <div class="modal fade" id="mediaModal<?= $row->id_blogs_admin ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content bg-dark">
                                    <div class="modal-body p-0 text-center">

                                        <?php if ($is_video): ?>
                                            <video controls autoplay class="w-100 rounded-bottom">
                                                <source src="<?= base_url($row->image); ?>" type="video/<?= $ext ?>">
                                            </video>
                                        <?php else: ?>
                                            <img src="<?= base_url($row->image); ?>" class="w-100 rounded-bottom">
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>

                <!-- Pagination -->
                <?php if (isset($pagination) && !empty($pagination)) : ?>
                    <div class="pagination-container text-center mt-4"><?= $pagination; ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    #liveResultsAdmin {
        position: absolute; /* ensures it overlays content */
        background: white;
        border: 1px solid #ccc;
        max-height: 300px;
        overflow-y: auto;
        width: 90%; /* match your input width */
        z-index: 9999;
    }
    #liveResultsAdmin div {
        padding: 8px 12px;
        cursor: pointer;
    }
    #liveResultsAdmin div:hover {
        background-color: #f0f0f0;
    }
</style>
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
