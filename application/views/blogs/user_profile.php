    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="card bg-gradient-to-r from-blue-100 to-purple-300">
                    <div class="card-header text-center">
                        <?php if($this->session->flashdata('status')) :?>
                        <div class="alert alert-success">
                            <?= $this->session->flashdata('status') ?>
                        </div>
                        <?php endif; ?>
                        <a href="<?=base_url('index.php/employee/logout');?>" class="no-underline bg-purple-300 text-white py-2 px-2 rounded-lg shadow hover:bg-purple-600 transition duration-300 float-left">Cerrar Sesión</a>
                        <a href="<?=base_url('index.php/blog');?>" class="no-underline bg-blue-300 -800 hover:bg-black text-white py-2 px-6 rounded-md transition float-right">Regresar</a>
                        <h3>Perfil
                        </h3>
                    </div>
                    <div class="card-body bg-gradient-to-r from-purple-100 to-blue-100">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Overview</a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Account</a>
                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Help</a>
                        </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                <h3 class="text-gray-700 text-center"; style="margin-right: 0px;"> <strong><?= $this->session->userdata('first_name'). ' ' . $this->session->userdata('last_name'); ?></strong> </h3>
                                <img src="<?= base_url($this->session->userdata('profile_picture')); ?>" 
                                    alt="Profile" width="300" height="220" 
                                    class="mx-auto full border border-gray-300 shadow-md">
                            <br>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <br>
                                <table>
                                    <thead>
                                        <tr class="bg-white hover:bg-purple-50">
                                        <th class="px-4 py-2 border">Nombre</th>
                                        <th class="px-4 py-2 border">Correo</th>
                                        <th class="px-4 py-2 border">Teléfono</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="hover:bg-purple-50">
                                        <th class="px-4 py-2 border"><?= $this->session->userdata('first_name'). ' ' . $this->session->userdata('last_name'); ?></th>
                                        <th class="px-4 py-2 border"><?= $this->session->userdata('email'); ?></th>
                                        <th class="px-4 py-2 border"><?= $this->session->userdata('phone'); ?></th>
                                        </tr>
                                    </tbody>
                                </table>
                            
                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab"> <br>
                            <a href="<?=base_url('index.php/employee/edit/'. $this->session->userdata('id'));?>" class="no-underline bg-gray-500 -800 hover:bg-black text-white py-3 px-6 rounded-md transition align-center">Editar Cuenta</a> <br><br>
                            <!-- Button trigger modal -->
                                <button type="button" class="no-underline bg-red-300 -800 hover:bg-black text-white py-3 px-6 rounded-md transition align-center" data-toggle="modal" data-target="#exampleModal">
                                Eliminar Cuenta
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalLabel">Eliminar Cuenta</h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Esta acción no puede ser revertida.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Regresar</button>
                                        <button type="button" class="no-underline bg-red-300 -800 hover:bg-black text-white py-2 px-6 rounded-md transition align-center">Eliminar</button>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        body {
        font-family: "Delius";
        font-weight: 1400;
        font-style: normal;
        }

    </style>
    

    <!-- <table id="account-table" class="table table-bordered">
                                    
    </table> -->