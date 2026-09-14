<div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="card">
                    
                    <div class="card-header">
                        <h5>Delete Employee
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <h1>The following Employee will be deleted:</h1>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                                 
                                    <td><?= $employee->id; ?></td>
                                    <td><?= $employee->first_name; ?></td>
                                    <td><?= $employee->last_name; ?></td>
                                    <td><?= $employee->phone; ?></td>
                                    <td><?= $employee->email; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <h3>Are you sure?</h3>
                            <a href="<?=base_url('index.php/employee');?>" class="btn btn-success float-left">Return</a> 
                            <div class="form-group">
                                <br> <br> 
                                <a href="<?=base_url('index.php/employee/remove/'. $employee->id);?>" class="btn btn-danger float-left">Delete</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>