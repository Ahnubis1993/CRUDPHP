<?php
// Captura username y password definidas con "for=" en los labels correspondientes
// de esta forma el boton solo nos lleva a la siguiente pagina cuando los campos esten completos
// ya que se comprueba que no esten vacios

// Esto es posible ya que la etiqueta <form> lleva el metodo POST, que es recogido aqui como una peticion
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se enviaron los campos de nombre de usuario y contraseña
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error_message = 'Por favor, complete todos los campos.';
    } else {
        // Por simplicidad, en este ejemplo siempre se mostrará un mensaje de éxito
        $error_message = 'Inicio de sesión exitoso. ¡Bienvenido!';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Inicio de Sesión</h5>
                        <form action="index.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de usuario</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                            </div>
                            <?php if (!empty($error_message)) { ?>
                                <div class="mt-3 text-center text-danger">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

<?php include 'includes/footer.php'; ?>

</html>