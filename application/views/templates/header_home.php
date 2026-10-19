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
    <link rel="stylesheet" href="<?= base_url('assets/css/moonio-theme.css'); ?>">
  </head>
  <body class="moonio-theme">
    <?php 
      $message['message'] = '';
      $message['login'] = FALSE; 
    ?>
  <?php if ($this->session->userdata('id')): ?>

    <div class="bg-slate-800 border border-green-700 rounded-2xl p-4 mb-6">

        <div class="flex justify-between items-center flex-wrap gap-4">

            <div>
                <h3 class="text-xl font-bold text-yellow-400">
                    ✦ MOONIO Collaboration Hub
                </h3>

                <p class="text-gray-300 text-sm">
                    Bienvenid@ <?= $this->session->userdata('first_name'); ?>
                </p>
                <a href="<?= base_url('index.php/employee/logout'); ?>"
                    class="no-underline bg-purple-300 text-white py-3 px-3 rounded-lg shadow hover:bg-purple-600 transition duration-300">
                    Cerrar Sesión
                </a>
            </div>


            <div class="flex flex-wrap gap-3">

                <a href="<?= base_url('index.php/home') ?>"
                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold">
                    🌑 Dashboard
                </a>

                <a href="<?= base_url('index.php/chats') ?>"
                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold">
                    💬 Chats
                </a>

                <a href="<?= base_url('index.php/rewards') ?>"
                    class="bg-yellow-500 hover:bg-yellow-400 text-slate-900 px-4 py-2 rounded-lg font-semibold">
                    🌙 Recompensas
                </a>

                <a href="<?= base_url('index.php/task_manager') ?>"
                    class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg font-semibold">
                    📋 Administrador de Tareas
                </a>

                <a href="<?= base_url('index.php/ftp_center') ?>"
                    class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg font-semibold">
                    📁 Centro de Transferencias
                </a>

                <a href="<?= base_url('index.php/smtp') ?>"
                    class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg font-semibold">
                    📧 Centro de Correos
                </a>
                <button onclick="sendLocation()"
                    class="bg-green-500 hover:bg-green-400 text-black px-4 py-2 rounded-lg font-semibold">
                    📍 Enviar Ubicación
                </button>
                <script>
                  function sendLocation()
                  {
                      if (!navigator.geolocation) {
                          alert("Geolocalización no soportada");
                          return;
                      }

                      navigator.geolocation.getCurrentPosition(function(position) {

                          fetch("<?= base_url('index.php/location/save') ?>", {
                              method: "POST",
                              headers: {
                                  "Content-Type": "application/x-www-form-urlencoded"
                              },
                              body:
                                  "latitude=" + encodeURIComponent(position.coords.latitude) +
                                  "&longitude=" + encodeURIComponent(position.coords.longitude)
                          })
                          .then(res => res.text())
                          .then(text => {
                              console.log(text);

                              try {
                                  const data = JSON.parse(text);

                                  if (data.status === "ok") {
                                      alert("Ubicación enviada ⚽");
                                  } else {
                                      alert("Error al guardar ubicación: " + data.message);
                                  }
                              } catch (e) {
                                  alert("Respuesta no válida del servidor. Revisa la consola.");
                              }
                          })
                          .catch(error => {
                              console.error(error);
                              alert("Error enviando ubicación");
                          });

                      }, function(error) {
                          alert("No se pudo obtener la ubicación");
                          console.log(error);
                      });
                  }
                  </script>

            </div>

        </div>

    </div>
    <br> 
    <!-- <a href="<?= base_url('index.php/employee/logout'); ?>" class="text-red-500 float-right">Logout</a> -->
  <?php else: ?>
    <?php  
    ?> 
    <p class="text-gray-700 text-left"; style="margin-right: 30px;">INICIA SESIÓN O REGÍSTRATE :D</p>
    <div>
      <a href="<?=base_url('index.php/login');?>" class="no-underline bg-blue-300 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition">Login</a> 
    </div>
  <?php endif; ?>

