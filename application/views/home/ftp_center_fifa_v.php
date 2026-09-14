<div class="min-h-screen bg-gradient-to-br from-green-900 via-green-800 to-yellow-700 p-8">

    <div class="max-w-6xl mx-auto">

        <!-- Header -->

        <div class="text-center mb-10">

            <div class="text-7xl mb-4">
                📂⚽
            </div>

            <h1 class="text-5xl font-extrabold text-white">
                Centro de Transferencias FIFA
            </h1>

            <p class="text-green-100 mt-3">
                Envía y administra documentos estratégicos del torneo
            </p>

        </div>

        <!-- Upload Card -->

        <div class="bg-gray-900 rounded-3xl shadow-2xl p-8 mb-10 border-4 border-yellow-400">

            <form
                action="<?= base_url('index.php/ftp/upload') ?>"
                method="POST"
                enctype="multipart/form-data">

                <div class="mb-6">

                    <label class="block text-yellow-400 font-bold mb-3">
                        Seleccionar archivo
                    </label>

                    <input
                        type="file"
                        name="userfile"
                        class="w-full bg-gray-800 text-white p-4 rounded-xl border border-green-500">

                </div>

                <button
                    type="submit"
                    class="bg-yellow-400 hover:bg-yellow-300 text-black font-bold px-8 py-3 rounded-full shadow-lg">

                    ⚽ Transferir Archivo

                </button>

            </form>

        </div>

        <!-- Files Grid -->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

            <?php foreach($files as $file): ?>

                <div class="bg-gray-900 rounded-2xl p-6 border-2 border-green-500 shadow-xl">

                    <div class="text-5xl mb-4 text-center">
                        📄
                    </div>

                    <h3 class="font-bold text-yellow-400 text-center truncate">
                        <?= $file->file_name ?>
                    </h3>

                    <?php
                        $size = $file->file_size;

                        if($size >= 1024)
                        {
                            $sizeText = round($size / 1024, 2) . ' MB';
                        }
                        else
                        {
                            $sizeText = round($size, 2) . ' KB';
                        }
                    ?>

                    <p class="text-green-200 text-sm text-center mt-2">
                        📦 <?= $sizeText ?>
                    </p>

                    <p class="text-green-200 text-sm text-center mt-3">
                        <?= date('d/m/Y H:i', strtotime($file->created_at)) ?>
                    </p>

                    <div class="mt-6 text-center">

                        <a
                            href="<?= base_url('index.php/ftp/download/' . $file->id_file) ?>"
                            class="inline-block bg-green-600 hover:bg-green-500 text-white px-5 py-2 rounded-full font-semibold">

                            Descargar

                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>