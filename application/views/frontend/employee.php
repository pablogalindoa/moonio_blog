    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-header text-center bg-gradient-to-r from-purple-100 to-blue-100">
                        <?php if($this->session->flashdata('status')) :?>
                        <div class="alert alert-success">
                            <?= $this->session->flashdata('status') ?>
                        </div>
                        <?php endif; ?>
                        <h2>Lista de Usuarios
                            <a href="<?=base_url('index.php/home');?>" class="no-underline bg-purple-300 text-white py-1 px-3 rounded-lg shadow hover:bg-purple-600 transition duration-300 float-left">Regresar</a>
                            <a href="<?=base_url('index.php/employee/logout');?>" class="no-underline bg-red-300 text-white py-1 px-3 rounded-lg shadow hover:bg-red-600 transition duration-300 float-right">Cerrar Sesión</a>
                        </h2>
                    </div>
                    <div class="card-body bg-gradient-to-r from-purple-100 to-blue-100">
                        <table id="dataTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th>APELLIDO</th>
                                    <th>TELÉFONO</th>
                                    <th>EMAIL</th>
                                    <th>CONTRASEÑA</th>
                                    <th>EDITAR</th>
                                    <th>ELIMINAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($employee as $row):?>
                                <tr>                                 
                                    <td><?= $row->id; ?></td>
                                    <td><?= $row->first_name; ?></td>
                                    <td><?= $row->last_name; ?></td>
                                    <td><?= $row->phone; ?></td>
                                    <td><?= $row->email; ?></td>
                                    <td><?= substr($row->password, 0, 10) . '...'; ?></td>
                                    <td>
                                        <a href="<?= base_url('index.php/employee/edit/' . $row->id);?>" class="no-underline bg-purple-300 text-white py-2 px-5 rounded-lg shadow hover:bg-purple-600 transition duration-300">Edit</a>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('index.php/employee/delete/' . $row->id);?>" class="no-underline bg-red-300 text-white py-2 px-5 rounded-lg shadow hover:bg-red-600 transition duration-300">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>