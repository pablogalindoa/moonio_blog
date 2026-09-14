<div class="text-center mb-4">
    <img src="<?= base_url('uploads/moonio_logo_mundial.png'); ?>"
         alt="Logo"
         class="mx-auto w-36 h-36 rounded-full shadow-lg border-4 border-yellow-400">
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">

            <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-900 via-blue-700 to-green-600 p-5 rounded-xl">

                <div class="bg-gradient-to-br from-white to-blue-50 p-8 rounded-xl shadow-2xl border-4 border-yellow-400 w-full max-w-md">

                    <h5 class="text-3xl font-bold text-center text-blue-900 mb-6 uppercase tracking-wider">
                        Registrar Usuario
                    </h5>

                    <div class="card-body">

                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('index.php/employee/store'); ?>" method="POST" enctype="multipart/form-data">

                            <div class="form-group mb-3">
                                <label class="font-semibold text-blue-900">Nombre</label>
                                <input type="text" name="first_name" class="form-control" value="<?= set_value('first_name') ?>">
                                <small><?= form_error('first_name')?></small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-semibold text-blue-900">Apellido</label>
                                <input type="text" name="last_name" class="form-control" value="<?= set_value('last_name') ?>">
                                <small><?= form_error('last_name')?></small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-semibold text-blue-900">Fecha de Nacimiento</label>
                                <input type="date" name="birth_date" class="form-control" value="<?= set_value('birth_date') ?>">
                                <small><?= form_error('birth_date')?></small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-semibold text-blue-900">Teléfono</label>
                                <input type="text" name="phone" class="form-control" value="<?= set_value('phone') ?>">
                                <small><?= form_error('phone')?></small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-semibold text-blue-900">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>">
                                <small><?= form_error('email')?></small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-semibold text-blue-900">Contraseña</label>
                                <input type="password"
                                       name="password"
                                       class="form-control focus:ring-2 focus:ring-yellow-400">
                                <small><?= form_error('password')?></small>
                            </div>

                            <div class="form-group mb-4">
                                <label class="block text-lg font-medium text-blue-900">
                                    Foto de Perfil
                                </label>
                                <input type="file"
                                       name="profile_picture"
                                       class="w-full p-2 border border-gray-300 rounded"
                                       accept="image/*">
                            </div>

                            <div class="form-group mt-3">
                                <label class="font-semibold text-blue-900">
                                    ¿Te refirieron a esta aplicación?
                                </label>
                                <select name="referred" id="referred" class="form-control">
                                    <option value="">Selecciona una opción</option>
                                    <option value="no">No</option>
                                    <option value="si">Sí</option>
                                </select>
                            </div>

                            <div class="form-group mt-3" id="referrer_container" style="display:none;">
                                <label class="font-semibold text-blue-900">
                                    ¿Quién te refirió?
                                </label>

                                <select name="referrer_id" id="referrer_id" class="form-control">
                                    <option value="">Selecciona un empleado</option>

                                    <?php foreach($employees as $employee): ?>
                                        <option value="<?= $employee->id ?>">
                                            <?= $employee->first_name . ' ' . $employee->last_name ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="flex justify-between items-center mt-5">

                                <button type="submit"
                                    class="bg-yellow-500 hover:bg-yellow-400 text-blue-900 font-bold py-3 px-8 rounded-lg shadow-lg transition duration-300">
                                    Registrar
                                </button>

                                <a href="<?= base_url('index.php/store'); ?>"
                                   class="no-underline bg-green-600 hover:bg-green-700 text-white py-3 px-8 rounded-lg shadow transition duration-300">
                                    Regresar
                                </a>

                            </div>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const referredSelect = document.getElementById('referred');
    const referrerContainer = document.getElementById('referrer_container');

    referredSelect.addEventListener('change', function() {
        if (this.value === 'si') {
            referrerContainer.style.display = 'block';
        } else {
            referrerContainer.style.display = 'none';
        }
    });
});
</script>

