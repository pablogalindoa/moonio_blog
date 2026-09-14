<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.1/css/buttons.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/staterestore/1.4.1/css/stateRestore.bootstrap5.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Delius&display=swap" rel="stylesheet">

    <title>M O O N I O</title>
  </head>
  <body>
  <?php if ($this->session->userdata('id')): ?>
    <!-- <div class="flex justify-between items-center px-6 py-4 bg-purple-50">
  
      
      <div class="w-1/3">
        
        <a href="<?= base_url('index.php/employee/logout'); ?>" 
          class="no-underline bg-purple-300 text-white py-2 px-4 rounded-lg shadow hover:bg-purple-600 transition duration-300">
          Cerrar Sesión
        </a>
      </div>

      <div class="w-1/3 flex flex-col items-center">
        <p class="text-gray-700 mb-2 text-center">
          Bienvenido, <strong><?= $this->session->userdata('first_name'); ?></strong>!
        </p>
        <img src="<?= base_url($this->session->userdata('profile_picture')); ?>" 
            alt="Profile" class="w-28 h-28 rounded-full border border-gray-300 shadow-md">
      </div>

      
      <div class="w-1/3 text-right">
        <a href="<?= base_url('index.php/blog/profile'); ?>" 
          class="no-underline bg-blue-300 hover:bg-blue-400 text-white py-2 px-4 rounded-md transition">
          Ajustes
        </a>
      </div>
    </div> -->


    
    <!-- <a href="<?= base_url('index.php/employee/logout'); ?>" class="text-red-500 float-right">Logout</a> -->
  <?php else: ?>
    <!-- <a href="<?= base_url('index.php/login'); ?>" class="text-blue-500">Login</a> -->
  <?php endif; ?>
  <!-- Bootstrap Wrapper -->
  <div class="bootstrap-wrapper"></div>

  <style>
    body {
        background-color: #E6E6FA !important; /* Pastel purple */
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
</style>
