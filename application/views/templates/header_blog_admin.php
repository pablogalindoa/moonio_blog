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

    <title>M O O N I O</title>
  </head>
  <body>
    <?php 
      $message['message'] = '';
      $message['login'] = FALSE; 
    ?>
  <?php if ($this->session->userdata('id')): ?>  
    <!-- <a href="<?= base_url('index.php/blog'); ?>" 
        class="no-underline bg-purple-300 text-white py-8 px-4 rounded-lg shadow hover:bg-purple-600 transition duration-300 float-left">
        Mi Feed
    </a> -->
    <?php if($this->session->userdata('is_admin') == 1): ?>
      <a href="<?= base_url('index.php/employee'); ?>" 
        class="no-underline bg-purple-300 text-white py-3 px-8 rounded-lg shadow hover:bg-purple-600 transition duration-300 float-right">
        Lista de Usuarios
      </a>
    <!-- <a href="<?=base_url('index.php/blog/blog_request');?>" class="no-underline bg-red-300 -800 hover:bg-black text-white py-3 px-4 rounded-md transition float-left">Solicitudes</a>
    <a href="<?=base_url('index.php/mundiales/create');?>" class="no-underline bg-blue-300 -800 hover:bg-black text-white py-3 px-4 rounded-md transition float-left">Crear Página</a> -->
    <?php endif; ?>
  <?php else: ?>
    <p class="text-gray-700 text-left"; style="margin-right: 30px;"></p>
    <div>
      <a href="<?=base_url('index.php/login');?>" class="no-underline bg-blue-300 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition">Login</a> 
    </div>
    
  <?php endif; ?>

  <br><br>

