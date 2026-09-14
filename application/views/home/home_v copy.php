<br>
<div class="row justify-content-center">
            <div class="col-md-12 mt-4">
                <div class="text-center mb-4">
                <img src="<?= base_url('uploads/moonio_logo_mundial.png'); ?>" alt="Logo" class="mx-auto w-36 h-36 rounded-full shadow-md">
                </div>
            </div>
</div>
<div class="container my-5">
    <h1 class="text-center mb-4 fw-bold" style="text-align: center;">🏆 CAMPEONATOS FIFA (1994 – 2022)</h1>
    <div class="text-center my-4">
    <button id="btnMyApi" class="no-underline bg-blue-300 hover:bg-blue-400 text-white py-3 px-8 rounded-md">
        📡 Ver API Interna (Champions)
    </button>

    <button id="btnExternalApi" class="no-underline bg-purple-300 hover:bg-purple-400 text-white py-3 px-8 rounded-md">
        🌍 Ver Dato del Mundial (API Externa)
    </button>
    </div>

    <div id="apiResult" class="text-center mt-4 p-3" 
        style="display:none; background:#f1f1f1; border-radius:10px;">
    </div>

    <div class="masonry-grid">

        <?php foreach ($worldCups as $wc): ?>

            <a href="<?= base_url('index.php/blog/blog_admin/' . $wc->id_mundiales); ?>" 
            class="masonry-item">

                <div class="overlay">
                    <h3><?= $wc->year; ?></h3>
                    <p><?= $wc->id_countries; ?></p>
                </div>

                <img src="<?= base_url($wc->background_img); ?>" alt="World Cup">
            </a>

        <?php endforeach; ?>

    </div>
</div>


<style>

    .masonry-grid {
    column-count: 4; /* number of columns on large screens */
    column-gap: 1rem;
    padding: 1rem;
    }

    .masonry-item {
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
        width: 100%;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.3s ease;
    }

    .masonry-item img {
        width: 100%;
        border-radius: 10px;
        display: block;
        transition: transform 0.3s ease, filter 0.3s ease;
    }

    .masonry-item:hover img {
        transform: scale(1.03);
        filter: brightness(60%);
    }

    .overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: white;
        font-weight: bold;
    }

    .masonry-item:hover .overlay {
        opacity: 1;
    }

    .overlay h3 {
        margin: 0;
        font-size: 1.8rem;
    }

    .overlay p {
        font-size: 1rem;
        margin: 0;
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .masonry-grid { column-count: 3; }
    }

    @media (max-width: 768px) {
        .masonry-grid { column-count: 2; }
    }

    @media (max-width: 480px) {
        .masonry-grid { column-count: 1; }
    }


</style>

