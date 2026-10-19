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
  <?php if ($this->session->userdata('id')):
    $currentRoute = $this->uri->segment(1);
    $navigation = [
      ['home', 'Dashboard', '◈'], ['chats', 'Chats', '◌'],
      ['rewards', 'Recompensas', '✧'], ['task_manager', 'Administrador de Tareas', '☷'],
      ['ftp_center', 'Centro de Transferencias', '↗'], ['smtp', 'Centro de Correos', '✉']
    ];
    $displayName = (string)$this->session->userdata('first_name');
  ?>
    <header class="moon-topbar">
      <a class="moon-brand" href="<?= site_url('home'); ?>" aria-label="Moonio — Dashboard">
        <img src="<?= base_url('assets/img/moonio-mark.svg'); ?>" alt="" width="30" height="30"> <span>Moonio</span>
      </a>
      <button class="moon-menu-toggle" type="button" aria-controls="moon-navigation" aria-expanded="false">☰ <span>Menú</span></button>
      <nav id="moon-navigation" class="moon-navigation" aria-label="Navegación principal">
        <?php foreach ($navigation as [$route, $label, $icon]): ?>
          <a class="moon-nav-link" href="<?= site_url($route); ?>" <?= $currentRoute === $route ? 'aria-current="page"' : ''; ?>>
            <span class="moon-icon" aria-hidden="true"><?= $icon; ?></span><span><?= $label; ?></span>
          </a>
        <?php endforeach; ?>
      </nav>
      <details class="moon-profile">
        <summary aria-label="Menú de usuario: <?= html_escape($displayName); ?>">
          <span class="moon-avatar" aria-hidden="true"><?= html_escape(mb_substr($displayName, 0, 1)); ?></span>
          <span class="moon-user-name"><?= html_escape($displayName); ?></span><span class="moon-chevron" aria-hidden="true">⌄</span>
        </summary>
        <div class="moon-profile-menu">
          <p>Tu espacio de trabajo</p>
          <strong><?= html_escape($displayName); ?></strong>
          <button type="button" onclick="sendLocation()">Compartir ubicación</button>
          <a href="<?= site_url('employee/logout'); ?>">Cerrar Sesión <span aria-hidden="true">↗</span></a>
        </div>
      </details>
    </header>
    <script>
      (() => {
        const header = document.querySelector('.moon-topbar');
        const toggle = header.querySelector('.moon-menu-toggle');
        const profile = header.querySelector('.moon-profile');
        toggle.addEventListener('click', () => {
          const expanded = toggle.getAttribute('aria-expanded') !== 'true';
          toggle.setAttribute('aria-expanded', String(expanded));
          header.classList.toggle('menu-open', expanded);
        });
        document.addEventListener('click', event => { if (!profile.contains(event.target)) profile.open = false; });
        document.addEventListener('keydown', event => {
          if (event.key !== 'Escape') return;
          if (profile.open) { profile.open = false; profile.querySelector('summary').focus(); }
          if (toggle.getAttribute('aria-expanded') === 'true') { toggle.click(); toggle.focus(); }
        });
      })();
    </script>
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
                                      alert("Ubicación enviada");
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
  <?php else: ?>
    <header class="moon-topbar moon-guestbar">
      <a class="moon-brand" href="<?= site_url('home'); ?>"><img src="<?= base_url('assets/img/moonio-mark.svg'); ?>" alt="" width="30" height="30"> Moonio</a>
      <a class="moon-nav-link" href="<?= site_url('login'); ?>">Iniciar Sesión</a>
    </header>
  <?php endif; ?>
