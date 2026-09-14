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
                    <!-- Search Bar -->
                    <form method="get" action="<?= base_url('index.php/blog/search'); ?>" class="mb-3">
                    <input type="text" name="query" id="liveSearchInput" class="form-control" placeholder="Buscar..." />
                    <div id="liveResults" class="mt-3"></div>
                    </form> <br>
                    <ul class="list-unstyled items-center">
                    <li><a href="<?= base_url('index.php/blog'); ?>" 
                            class="no-underline bg-purple-300 text-white py-3 px-8 rounded-lg shadow hover:bg-purple-600 transition duration-300">
                            H O M E
                            </a>
                    </li> <br> <br>
                        <li><a href="<?= base_url('index.php/blog/create'); ?>" class="no-underline bg-blue-300 hover:bg-blue-400 text-white py-3 px-8 rounded-md transition text-center">Nueva Publicación</a></li> <br> <br>
                        <li><a href="<?= base_url('index.php/blog/profile'); ?>" 
                            class="no-underline bg-purple-300 text-white py-3 px-8 rounded-lg shadow hover:bg-purple-600 transition duration-300">
                            Ajustes
                            </a>
                        </li> <br> <br>
                        <li><a href="<?= base_url('index.php/home'); ?>" 
                            class="no-underline bg-blue-300 hover:bg-blue-400 text-white py-3 px-8 rounded-md transition text-center">
                            PÁGINA PRINCIPAL
                            </a>
                        </li> <br> <br>
                        <li><a href="<?= base_url('index.php/employee/logout'); ?>" 
                            class="no-underline bg-purple-300 text-white py-3 px-8 rounded-lg shadow hover:bg-purple-600 transition duration-300">
                            Cerrar Sesión
                            </a>
                        </li> <br> <br>
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
                    <h1 class="text-center">B L O G</h1>
                </div>

                <!-- Blog Posts -->
                <div class="row px-3 mt-3">
                <?php foreach ($blogs as $blog): ?>
                    <div class="card shadow-sm rounded-lg m-2">
                        <img class="card-img-top" src="<?= base_url($blog->image); ?>" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title"><?= htmlspecialchars($blog->title); ?></h4>
                            <h6 class="card-author"><?= htmlspecialchars($blog->first_name); ?></h6>
                            <p class="card-text"><?= nl2br(htmlspecialchars($blog->content)); ?></p>
                            <p class="card-text"><small class="text-muted"><?= nl2br(htmlspecialchars($blog->created_at)); ?></small></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if (isset($pagination) && !empty($pagination)) : ?>
                    <div class="pagination-container text-center mt-4">
                        <?= $pagination; ?>
                    </div>
                <?php endif; ?>

                <!-- Create Post Button -->
                <div class="text-center my-3">
                    
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Optional CSS for Pagination Styling -->
<style>

    body {
    
    font-weight: 1400;
    font-style: normal;
    }


    .bootstrap-wrapper .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        list-style: none;
        padding: 10px;
    }

    .bootstrap-wrapper .pagination a {
        color: #333 !important;
        background-color: #C3B1E1 !important;
        border: 1px solid #ccc !important;
        padding: 10px 18px;
        margin: 0 5px;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        border-radius: 50px;
        transition: all 0.3s ease-in-out;
        display: flex;
        justify-content: center;
        align-items: center;
        min-width: 40px;
        height: 40px;
    }

    .bootstrap-wrapper .pagination a.active {
        background-color: #301934 !important;
        color: white !important;
        border: 1px solid #301934 !important;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
    }

    .bootstrap-wrapper .pagination a:hover:not(.active) {
        background-color: #301934 !important;
        color: white !important;
        border: 1px solid #301934 !important;
    }
</style>
<script>
document.getElementById('liveSearchInput').addEventListener('input', function() {
    const query = this.value.trim();
    if (query.length === 0) {
        document.getElementById('liveResults').innerHTML = '';
        return;
    }

    fetch(`<?= base_url('index.php/blog/liveSearch'); ?>?query=${encodeURIComponent(query)}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('liveResults').innerHTML = data;
        });
});
</script>
