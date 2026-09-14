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
  <?php if ($this->session->userdata('id')): ?>
    <p class="text-gray-700 text-right"; style="margin-right: 30px;">¡Benvenid@ <strong><?= $this->session->userdata('first_name'); ?></strong>!</p>
    <img src="<?= base_url($this->session->userdata('profile_picture')); ?>" 
     alt="Profile" width="200" height="120" 
     class="float-right rounded-full border border-gray-300 shadow-md">
    <br> 
    <!-- <a href="<?= base_url('index.php/employee/logout'); ?>" class="text-red-500 float-right">Logout</a> -->
  <?php else: ?>
    <!-- <a href="<?= base_url('index.php/login'); ?>" class="text-blue-500">Login</a> -->
  <?php endif; ?>

