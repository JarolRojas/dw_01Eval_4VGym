<?php
require_once(__DIR__ . '/app/controllers/ActivityController.php');
session_start();

//TODO NO INSERTAR ACTIVIDADES CON FECHAS ANTERIORES
//TODO VALIDAR CAMPOS DEL FORMULARIO
//TODO FORMATEAR FECHA  -> 5 "NOVIEMBRE 2025 19:00"
//TODO MENSAJES DE ERROR Y EXITO EN LA INSERCION
//FIXME AL RECARGAR DESPUES DE INSERTAR, SE VUELVE A ENVIAR EL FORMULARIO

$controller = new ActivityController();
$message = '';
$messageType = '';

if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $messageType = isset($_SESSION['flash_type']) ? $_SESSION['flash_type'] : 'success';
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = isset($_POST['type']) ? trim($_POST['type']) : '';
    $monitor = isset($_POST['monitor']) ? trim($_POST['monitor']) : '';
    $place = isset($_POST['place']) ? trim($_POST['place']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';

    $result = $controller->create($type, $monitor, $place, $date);

    if (isset($result['success']) && $result['success']) {
        $_SESSION['flash_message'] = $result['message'];
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php');
        exit;
    } else {
        $message = $result['message'];
        $messageType = 'danger';
    }
}


$activityDate = isset($_GET['activityDate']) ? $_GET['activityDate'] : null;
$activities = $controller->getAll($activityDate);

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>4VGym</title>
    <!-- Bootstrap Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/3.5.0/octicons.min.css">
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-light  navbar-fixed-top navbar-expand-md" role="navigation">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
            data-target="#navbarToggler02" aria-controls="navbarTogglerDemo02" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarToggler02">
            <ul class="navbar-nav ">
                <li class="nav-item ">
                    <a class="navbar-brand" href="index.php"> <img class="img-fluid rounded d-inline-block align-top"
                            src="assets/img/small-logo_1.jpg" alt="" width="30" height="30">
                        4VGYM
                    </a>
                </li>
            </ul>
            <div class="ml-auto">
                <a type="button" class="btn btn-info " href=""><span class="octicon octicon-cloud-upload"></span> Subir
                    Actividad</a>
            </div>
        </div>
    </nav>


    <!-- Welcome Content -->
    <div class="container-fluid">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <!-- Mensaje de éxito o error -->
            <?php if ($message): ?>
                <div class="alert alert-<?php echo htmlspecialchars($messageType); ?> alert-dismissible fade show"
                    role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Heading Row -->
            <div class="row">
                <div class="col-md-5 ">
                    <img class="img-fluid img-rounded" src="assets/img/main-logo.png" alt="">
                </div>
                <div class="col-md-7 ">
                    <h1 class="alert-heading">4VGym, GYM de 4V</h1>
                    <p>Ponte en forma y ganaras vida</p>
                    <hr />
                    <form action="" method="get" class="row g-2 align-items-center">
                        <div class="col-auto">
                            <input name="activityDate" id="activityDate" class="form-control" type="date" value="<?php echo htmlspecialchars($activityDate ?: ''); ?>" />
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Page Content -->
    <div class="container">
        <!-- Content Row -->
        <div class="row">
            <?php
            $imageMap = [
                'spinning' => 'assets/img/spinning2.png',
                'bodypump' => 'assets/img/bodypump.png',
                'pilates' => 'assets/img/pilates.png'
            ];
            foreach ($activities as $activity):
                $image = $imageMap[$activity->getType()] ?? 'assets/img/spinning2.png';
                ?>
                <div class=" col-sm-12 col-md-6 col-lg-4 ">
                    <div class="card ">
                        <img class="card-img-top w-50 p-3 img-fluid mx-auto" src='<?php echo ($image); ?>'
                            alt="<?php echo ($activity->getType()); ?>">
                        <div class="card-body">
                            <h2 class="card-title display-4"><?php echo ($activity->getPlace()); ?></h2>
                            <p class="card-text lead"><?php echo ($activity->getDate()); ?></p>
                            <p class="card-text lead"><?php echo ($activity->getMonitor()); ?></p>
                            <p class="card-text"><span class="badge bg-info"><?php echo ($activity->getType()); ?></span>
                            </p>
                        </div>
                        <div class="card-footer d-flex justify-content-center">
                            <div class="btn-group">
                                <a type="button" class="d-none d-lg-block  btn btn-success" href="">Modificar</a>
                                <a type="button" class="d-none d-lg-block  btn btn-danger" href="">Borrar</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container">
        <form class="form-horizontal" method="POST" action="">

            <div class="form-group">
                <label for="type" class="col-sm-2 control-label">Tipo</label>
                <div class="col-sm-10">
                    <select id="type" class="form-control" name="type">
                        <option value="">-- Seleccionar --</option>
                        <option value="spinning">Spinning</option>
                        <option value="bodypump">BodyPump</option>
                        <option value="pilates">Pilates</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="monitor" class="col-sm-2 control-label">Monitor</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="monitor" id="monitor" placeholder="" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="place" class="col-sm-2 control-label">Lugar</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="place" id="place" placeholder="" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="date" class="col-sm-2 control-label">Fecha</label>
                <div class="col-sm-10">
                    <input type="datetime-local" class="form-control" name="date" id="date" placeholder="" value="">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Insert</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="container-fluid mb-0" role="footer">
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; M. G.</p>
            </div>
        </div>
    </footer>

    </div>

    <!-- Bootstrap Core JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
        <script>
            (function() {
                try {
                    const params = new URLSearchParams(window.location.search);
                    if (params.has('activityDate')) {
                        params.delete('activityDate');
                        const newSearch = params.toString();
                        const newUrl = window.location.pathname + (newSearch ? '?' + newSearch : '');
                        history.replaceState(null, '', newUrl);
                    }
                } catch (e) {
                    // no hacer nada si el navegador no soporta URLSearchParams
                }
            })();
        </script>
</body>

</html>