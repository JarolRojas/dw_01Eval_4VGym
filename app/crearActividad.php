<?php
require_once(__DIR__ . '/controllers/ActivityController.php');
session_start();

// Inicializar el controlador y variables de mensaje
$controller = new ActivityController();
$message = '';
$messageType = '';

// Manejar mensajes flash
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $messageType = isset($_SESSION['flash_type']) ? $_SESSION['flash_type'] : 'success';
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
}

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = isset($_POST['type']) ? trim($_POST['type']) : '';
    $monitor = isset($_POST['monitor']) ? trim($_POST['monitor']) : '';
    $place = isset($_POST['place']) ? trim($_POST['place']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';

    $result = $controller->create($type, $monitor, $place, $date);

    // Redirigir o mostrar mensaje según el resultado
    if (isset($result['success']) && $result['success']) {
        $_SESSION['flash_message'] = $result['message'];
        $_SESSION['flash_type'] = 'success';
        header('Location: ./../index.php');
        exit;
    } else {
        $message = $result['message'];
        $messageType = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>4VGym - Crear Actividad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/3.5.0/octicons.min.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-light navbar-fixed-top navbar-expand-md" role="navigation">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
            data-target="#navbarToggler02" aria-controls="navbarTogglerDemo02" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarToggler02">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="navbar-brand" href="./../index.php"> <img
                            class="img-fluid rounded d-inline-block align-top" src="./../assets/img/small-logo_1.jpg"
                            alt="" width="30" height="30">
                        4VGYM
                    </a>
                </li>
            </ul>
            <div class="ml-auto">
                <a type="button" class="btn btn-secondary" href="./../index.php"><span
                        class="octicon octicon-home"></span> Volver</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo htmlspecialchars($messageType); ?> alert-dismissible fade show"
                        role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h2>Crear Nueva Actividad</h2>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" method="POST" action="">
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipo</label>
                                <select id="type" class="form-control" name="type" >
                                    <option value="">-- Seleccionar --</option>
                                    <option value="spinning">Spinning</option>
                                    <option value="bodypump">BodyPump</option>
                                    <option value="pilates">Pilates</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="monitor" class="form-label">Monitor</label>
                                <input type="text" class="form-control" name="monitor" id="monitor"
                                    placeholder="Nombre del monitor" >
                            </div>

                            <div class="mb-3">
                                <label for="place" class="form-label">Lugar</label>
                                <input type="text" class="form-control" name="place" id="place"
                                    placeholder="Lugar de la actividad" >
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Fecha</label>
                                <input type="datetime-local" class="form-control" name="date" id="date" >
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Crear Actividad</button>
                                <a href="./../index.php" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="container-fluid mt-5" role="footer">
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; M. G.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>