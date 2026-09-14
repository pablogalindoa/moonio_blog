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
                    <br>
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
                        <li><a href="<?= base_url('index.php/employee/logout'); ?>" 
                            class="no-underline bg-blue-300 hover:bg-blue-400 text-white py-3 px-8 rounded-md transition text-center">
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
                    <!-- Search Bar -->
                    <form method="GET" action="<?= base_url('index.php/blog/search'); ?>" class="mb-4">
                        <div class="form-row align-items-end">
                            <!-- Live search -->
                            <div class="col-md-4">
                                <label for="query">Buscar:</label>
                                <input type="text" class="form-control" name="query" placeholder="Título o contenido...">
                            </div>

                            <!-- Category dropdown -->
                            <div class="col-md-3">
                                <label for="category">Categoría:</label>
                                <select class="form-control" name="category">
                                    <option value="">Todas</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat->id_categories ?>"><?= $cat->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Date range -->
                            <div class="col-md-2">
                                <label>Desde:</label>
                                <input type="date" class="form-control" name="start_date">
                            </div>
                            <div class="col-md-2">
                                <label>Hasta:</label>
                                <input type="date" class="form-control" name="end_date">
                            </div>

                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>

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
