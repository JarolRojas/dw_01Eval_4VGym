<?php
require_once(__DIR__ . '/app/controllers/ActivityController.php');
require_once(__DIR__ . '/app/helpers/ActivityDateHelper.php');
session_start();

$rutaBase = '/DW_01EVAL_4VGym';

$controller = new ActivityController();
$message = '';
$messageType = '';


if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $messageType = isset($_SESSION['flash_type']) ? $_SESSION['flash_type'] : 'success';
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';

    if ($formAction === 'update') {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $type = isset($_POST['type']) ? trim($_POST['type']) : '';
        $monitor = isset($_POST['monitor']) ? trim($_POST['monitor']) : '';
        $place = isset($_POST['place']) ? trim($_POST['place']) : '';
        $date = isset($_POST['date']) ? trim($_POST['date']) : '';
        $scrollPos = isset($_POST['scroll_pos']) ? $_POST['scroll_pos'] : 0;

        $result = $controller->update($id, $type, $monitor, $place, $date);
        if (isset($result['success']) && $result['success']) {
            $_SESSION['flash_message'] = $result['message'];
            $_SESSION['flash_type'] = 'success';
            $_SESSION['scroll_pos'] = $scrollPos;
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['flash_message'] = $result['message'];
            $_SESSION['flash_type'] = 'danger';
            $_SESSION['scroll_pos'] = $scrollPos;
            header('Location: index.php');
            exit;
        }
    } elseif ($formAction === 'delete') {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $scrollPos = isset($_POST['scroll_pos']) ? $_POST['scroll_pos'] : 0;

        $result = $controller->delete($id);
        if (isset($result['success']) && $result['success']) {
            $_SESSION['flash_message'] = $result['message'];
            $_SESSION['flash_type'] = 'success';
            $_SESSION['scroll_pos'] = $scrollPos;
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['flash_message'] = $result['message'];
            $_SESSION['flash_type'] = 'danger';
            $_SESSION['scroll_pos'] = $scrollPos;
            header('Location: index.php');
            exit;
        }
    }
}

$scrollPos = isset($_SESSION['scroll_pos']) ? $_SESSION['scroll_pos'] : 0;
unset($_SESSION['scroll_pos']);

$activityDate = isset($_GET['activityDate']) ? $_GET['activityDate'] : null;
$activities = $controller->getAll($activityDate);
$requestedEditId = isset($_GET['editId']) ? intval($_GET['editId']) : null;

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
                <a type="button" class="btn btn-info " href="<?php echo $rutaBase . '/app/crearActividad.php' ?>"><span
                        class="octicon octicon-cloud-upload"></span> Subir
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
                            <input name="activityDate" id="activityDate" class="form-control" type="date"
                                value="<?php echo htmlspecialchars($activityDate ?: ''); ?>" />
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filter</button>
                        </div>
                        <div class="col-auto">
                            <a href="index.php" class="btn btn-outline-secondary my-2 my-sm-0">Eliminar filtro</a>
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

            if (empty($activities)):
                ?>
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        <h4 class="alert-heading">No hay actividades disponibles</h4>
                        <p>Actualmente no hay actividades
                            registradas<?php echo $activityDate ? ' para la fecha seleccionada' : ''; ?>.</p>
                        <hr>
                        <p class="mb-0">
                            <a href="<?php echo $rutaBase . '/app/crearActividad.php' ?>" class="btn btn-primary">
                                <span class="octicon octicon-plus"></span> Crear nueva actividad
                            </a>
                            <?php if ($activityDate): ?>
                                <a href="index.php" class="btn btn-secondary">Ver todas las actividades</a>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <?php
            else:
                foreach ($activities as $activity):
                    $image = $imageMap[$activity->getType()] ?? 'assets/img/spinning2.png';
                    $aid = (int) $activity->getId();
                    $aidEsc = htmlspecialchars($aid);
                    $dateValue = '';
                    if ($activity->getDate()) {
                        $dateValue = date('Y-m-d\TH:i', strtotime($activity->getDate()));
                    }
                    ?>
                    <div class=" col-sm-12 col-md-6 col-lg-4 ">
                        <div class="card ">
                            <img class="card-img-top w-50 p-3 img-fluid mx-auto" src='<?php echo ($image); ?>'
                                alt="<?php echo ($activity->getType()); ?>">
                            <div class="card-body">
                                <h2 class="card-title display-4"><?php echo ($activity->getPlace()); ?></h2>
                                <p class="card-text lead">
                                    <?php echo htmlspecialchars(formatActivityDate($activity->getDate())); ?>
                                </p>
                                <p class="card-text lead"><?php echo ($activity->getMonitor()); ?></p>
                                <p class="card-text"><span class="badge bg-info"><?php echo ($activity->getType()); ?></span>
                                </p>

                                <div class="edit-form" id="edit-form-<?php echo $aidEsc; ?>"
                                    style="<?php echo ($requestedEditId === $aid) ? 'display:block;' : 'display:none;'; ?> margin-top:10px;">
                                    <form method="POST" action="">
                                        <input type="hidden" name="form_action" value="update" />
                                        <input type="hidden" name="id" value="<?php echo $aidEsc; ?>" />
                                        <input type="hidden" name="scroll_pos" value="0" class="scroll-pos-input" />
                                        <div class="mb-2">
                                            <label class="form-label">Tipo</label>
                                            <select name="type" class="form-control form-control-sm">
                                                <option value="spinning" <?php echo $activity->getType() === 'spinning' ? 'selected' : ''; ?>>Spinning</option>
                                                <option value="bodypump" <?php echo $activity->getType() === 'bodypump' ? 'selected' : ''; ?>>BodyPump</option>
                                                <option value="pilates" <?php echo $activity->getType() === 'pilates' ? 'selected' : ''; ?>>Pilates</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Monitor</label>
                                            <input type="text" name="monitor" class="form-control form-control-sm"
                                                value="<?php echo htmlspecialchars($activity->getMonitor()); ?>" />
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Lugar</label>
                                            <input type="text" name="place" class="form-control form-control-sm"
                                                value="<?php echo htmlspecialchars($activity->getPlace()); ?>" />
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Fecha</label>
                                            <input type="datetime-local" name="date" class="form-control form-control-sm"
                                                value="<?php echo $dateValue; ?>" />
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                                            <a href="index.php<?php echo $activityDate ? '?activityDate=' . urlencode($activityDate) : ''; ?>"
                                                class="btn btn-secondary btn-sm">Cancelar</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                <div class="btn-group">
                                    <a class="d-none d-lg-block btn btn-success"
                                        href="index.php?editId=<?php echo $aidEsc; ?><?php echo $activityDate ? '&activityDate=' . urlencode($activityDate) : ''; ?>">Modificar</a>
                                    <form method="POST" action="" style="display:inline-block; margin:0;">
                                        <input type="hidden" name="form_action" value="delete" />
                                        <input type="hidden" name="id" value="<?php echo $aidEsc; ?>" />
                                        <input type="hidden" name="scroll_pos" value="0" class="scroll-pos-input" />
                                        <button type="submit" class="d-none d-lg-block btn btn-danger">Borrar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                endforeach;
            endif;
            ?>
        </div>
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
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>