
<div class="container mt-5">
    <div>
        <a href="<?=base_url('index.php/home');?>"
           class="no-underline bg-blue-800 hover:bg-blue-900 text-white py-2 px-4 rounded-md transition">
            Página Principal
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 mt-4">

            <!-- Logo -->
            <div class="text-center mb-4">
                <img src="<?= base_url('uploads/moonio_logo_mundial.png'); ?>"
                     alt="Logo"
                     class="mx-auto w-36 h-36 rounded-full shadow-lg border-4 border-yellow-400">
            </div>

            <!-- Top footballs -->
            <table cellpadding="0" cellspacing="0" style="page-break-inside: avoid;" width="100%">
                <tr>
                    <?php for($i=0; $i<6; $i++): ?>
                    <td width="10%">
                        <img src="<?= base_url('uploads/pelota_1.png'); ?>"
                             alt="Pelota"
                             class="mx-auto w-32 h-32">
                    </td>
                    <?php endfor; ?>
                </tr>
            </table>

            <!-- Main Section -->
            <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-900 via-blue-700 to-green-600 rounded-xl p-5">

                <!-- Login Card -->
                <div class="bg-gradient-to-br from-white to-blue-50 p-8 rounded-xl shadow-2xl border-4 border-yellow-400 w-full max-w-md">

                    <?php if ($this->session->flashdata('status')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('status') ?>
                        </div>
                    <?php endif; ?>

                    <h5 class="text-3xl font-bold text-center text-blue-900 mb-6 uppercase tracking-wider">
                        Login
                    </h5>

                    <div class="card-body">

                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('index.php/employee/loginAuth'); ?>" method="POST">

                            <div class="form-group mb-3">
                                <label class="font-semibold text-blue-900">
                                    Email
                                </label>
                                <input type="text"
                                       name="email"
                                       class="form-control"
                                       required>
                                <small><?= form_error('email')?></small>
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-semibold text-blue-900">
                                    Contraseña
                                </label>
                                <input type="password"
                                       name="password"
                                       class="form-control w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                       required>
                                <small><?= form_error('password')?></small>
                            </div>

                            <div class="form-group mb-4">
                                <button type="submit"
                                    class="w-full bg-yellow-500 hover:bg-yellow-400 text-blue-900 font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300">
                                    Login
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="<?=base_url('index.php/employee/add');?>"
                                   class="no-underline bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md transition">
                                    Registrar
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- Bottom footballs -->
            <table cellpadding="0" cellspacing="0" style="page-break-inside: avoid;" width="100%">
                <tr>
                    <?php for($i=0; $i<6; $i++): ?>
                    <td width="10%">
                        <img src="<?= base_url('uploads/pelota_1.png'); ?>"
                             alt="Pelota"
                             class="mx-auto w-32 h-32">
                    </td>
                    <?php endfor; ?>
                </tr>
            </table>

        </div>
    </div>
</div>

