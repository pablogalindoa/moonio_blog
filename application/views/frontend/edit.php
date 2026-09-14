<div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-purple-100 to-blue-100">
                    <div class="bg-gradient-to-r from-gray-100 to-purple-100 p-8 rounded-lg shadow-xl w-full max-w-md">
                        <h5>Editar Usuario
                        </h5>
                        <div class="card-body">
                        <form action="<?= base_url('index.php/employee/update/' . $employee->id); ?>" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="">Nombre</label>
                                    <input type="text" name="first_name" value="<?=$employee->first_name;?>" class="form-control">
                                    <small><?= form_error('first_name')?></small>
                                </div>
                                <div class="form-group">
                                    <label for="">Apellido</label>
                                    <input type="text" name="last_name" value="<?=$employee->last_name;?>" class="form-control">
                                    <small><?= form_error('last_name')?></small>
                                </div>
                                <div class="form-group">
                                    <label for="">Fecha de Nacimiento</label>
                                    <input type="date" name="birth_date" value="<?= set_value('birth_date') ?>" class="form-control" value="<?= set_value('birth_date') ?>" placeholder="dd/mm/yyyy">
                                    <small><?= form_error('birth_date')?></small>
                                </div>
                                <div class="form-group">
                                    <label for="">Teléfono</label>
                                    <input type="text" name="phone" value="<?=$employee->phone;?>" class="form-control">
                                    <small><?= form_error('phone')?></small>
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="email" name="email" value="<?=$employee->email;?>" class="form-control">
                                    <small><?= form_error('email')?></small>
                                </div>
                                <div class="form-group">
                                    <label for="">Contraseña</label>
                                    <input type="password" name="password" value="<?=$employee->password;?>" class="form-control">
                                    <small><?= form_error('password')?></small>
                                </div>
                                <div class="form-group">
                                    <label class="block text-lg font-medium text-gray-700">Foto de Perfil</label>
                                    <img src="<?= base_url($this->session->userdata('profile_picture')); ?>" alt="hola">
                                    <input type="file" name="profile_picture" class="w-full p-2 border border-gray-300 rounded" accept="image/*">
                                </div>
                                <div class="flex justify-between items-center mt-4">
                                    <button type="submit" class="no-underline bg-green-400 hover:bg-green-600 text-white py-2 px-4 rounded-md transition">Actualizar</button>
                                    <a href="<?= base_url('index.php/login'); ?>" class="no-underline bg-blue-300 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition">Regresar</a>
                                </div>
                            </form>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>