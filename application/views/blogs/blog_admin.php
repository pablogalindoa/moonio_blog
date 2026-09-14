<div class="row justify-content-center container-fluid" style="background-color: #ededf6ff;">
    <div class="col-md-12 mt-4">
        <div class="text-center mb-4">
            <img src="<?= base_url('uploads/moonio_logo_mundial.png'); ?>" 
                 alt="Logo" class="mx-auto w-36 h-36 rounded-full shadow-md">
        </div>
    </div>
</div>

<div class="container-fluid" style="background-color: #E6E6FA; min-height: 100vh; padding: 20px;">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card shadow sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h2 class="card-title text-center">M O O N I O</h2>

                    <?php if ($this->session->userdata('id')): ?> 
                        <img src="<?= base_url($this->session->userdata('profile_picture')); ?>" 
                            alt="Profile" class="w-28 h-28 rounded-full border border-gray-300 shadow-md mx-auto mt-2 mb-4"> 
                        <p class="text-gray-700 mb-2 text-center">
                            Bienvenid@, <strong><?= $this->session->userdata('first_name'); ?></strong>!
                        </p> 
                        <br>
                    <?php endif; ?>
                    <form id="liveSearchForm" class="mb-3">
                        <input type="text" name="query" id="liveSearchInputAdmin" class="form-control" placeholder="Buscar..." />
                        <div id="liveResultsAdmin" class="mt-3"></div>
                    </form><br>

                    <!-- Search Bar -->

                    <ul class="list-unstyled items-center">
                        <li>
                            <a href="<?= base_url('index.php/blog/blog_my_posts/' . $this->session->userdata('id')); ?>" 
                            class="no-underline bg-purple-300 text-white py-3 px-9 rounded-lg shadow hover:bg-purple-600 transition duration-300">
                                Mis Publicaciones
                            </a>
                        </li>
                        <br><br>

                        <li>
                            <a href="<?= base_url('index.php/blog/create'); ?>" 
                                class="no-underline bg-blue-300 hover:bg-blue-400 text-white py-3 px-8 rounded-md transition text-center">
                                Nueva Publicación
                            </a>
                        </li>
                        <br><br>

                        <li>
                            <a href="<?= base_url('index.php/blog/profile'); ?>" 
                                class="no-underline bg-purple-300 text-white py-3 px-14 rounded-lg shadow hover:bg-purple-600 transition duration-300">
                                Ajustes
                            </a>
                        </li>
                        <br><br>

                        <li>
                            <a href="<?= base_url('index.php/home'); ?>" 
                                class="no-underline bg-blue-300 hover:bg-blue-400 text-white py-3 px-8 rounded-md transition text-center">
                                Inicio
                            </a>   
                        </li>
                        <br><br>

                        <li>
                            <a href="<?= base_url('index.php/employee/logout'); ?>" 
                                class="no-underline bg-purple-300 text-white py-3 px-14 rounded-lg shadow hover:bg-purple-600 transition duration-300">
                                Cerrar Sesión
                            </a>
                        </li>
                        <br><br>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-9 mt-4">
            <div class="card shadow-lg">
                <div class="card-header text-center">
                    <?php if ($this->session->flashdata('status')) : ?>
                        <div class="alert alert-success">
                            <?= $this->session->flashdata('status') ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="text-center">H O M E</h1>
                </div>

                <!-- Blog Posts -->
                <!-- Blog Posts -->
                <div class="row px-3 mt-3">
                    <?php foreach ($blogs as $row) : ?>
                        <?php
                            $file = $row->image;
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $isVideo = in_array($ext, ['mp4','mov','avi','mkv']);
                            $thumbnail = $isVideo 
                                ? base_url('assets/video_placeholder.png')
                                : base_url($file);
                        ?>
                        <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                            <div class="card h-100 shadow-sm rounded-lg position-relative">
                                <a href="<?= base_url('index.php/blog/blog_individual_admin/' . $row->id_blogs_admin); ?>">
                                    <img class="card-img-top blog-image"
                                        src="<?= $thumbnail ?>"
                                        alt="<?= htmlspecialchars($row->title); ?>"
                                        style="cursor:pointer; object-fit:cover; height:300px;">
                                </a>
                                <div class="card-body">
                                    <h4><?= htmlspecialchars($row->title); ?></h4>
                                    <h6><?= htmlspecialchars($row->name); ?></h6>
                                    <p><?= nl2br(htmlspecialchars(substr($row->content, 0, 100))) . '...'; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div> <!-- end row -->


                <!-- Like system JavaScript -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                $(document).ready(function(){
                    $('.like-btn').on('click', function(e){
                        e.preventDefault();
                        let btn = $(this);
                        let blogId = btn.data('id');

                        $.ajax({
                            url: "<?= base_url('index.php/blog/toggle_like_admin'); ?>", 
                            type: "POST",
                            data: { id_blogs_admin: blogId },
                            dataType: "json",
                            success: function(res){
                                if(res.status === 'success') {
                                    // toggle active style
                                    if(res.liked) {
                                        btn.addClass('btn-danger').removeClass('btn-outline-danger');
                                    } else {
                                        btn.addClass('btn-outline-danger').removeClass('btn-danger');
                                    }
                                    // update count
                                    $('#like-count-' + blogId).text(res.count);
                                } else if (res.status === 'error') {
                                    alert(res.message);
                                }
                            }
                        });
                    });
                });
                </script>

                <!-- Pagination -->
                <?php if (isset($pagination) && !empty($pagination)) : ?>
                    <div class="pagination-container text-center mt-4">
                        <?= $pagination; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<!-- CSS -->
<style>
    .blog-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        transition: transform 0.3s ease, filter 0.3s ease;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }

    .blog-image:hover {
        transform: scale(1.23);
        filter: brightness(80%);
    }

    .like-btn {
        transition: transform 0.2s ease;
    }
    .like-btn:active {
        transform: scale(1.3);
    }

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

