<?php
require_once 'includes/Teachers.php';
require_once 'includes/Alumn.php';

$teacherClass = new Teachers();
$alumnClass = new Alumn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['eliminar_profesor']) && isset($_POST['id_profesor'])) {
        $teacherClass->deleteProfessorById($_POST['id_profesor']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['modificar_profesor']) && isset($_POST['id_profesor']) && isset($_POST['nombre']) && isset($_POST['apellido1']) && isset($_POST['apellido2'])) {
        $teacherClass->updateProfessor($_POST['id_profesor'], $_POST['nombre'], $_POST['apellido1'], $_POST['apellido2']);
    }

    if (isset($_POST['eliminar_alumno']) && isset($_POST['id_alumno'])) {
        $alumnClass->deleteAlumnById($_POST['id_alumno']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['modificar_alumno']) && isset($_POST['id_alumno']) && isset($_POST['nombre']) && isset($_POST['apellido1'])) {
        $alumnClass->updateAlumn($_POST['id_alumno'], $_POST['nombre'], $_POST['apellido1']);
    }

    if (isset($_POST['buscar_profesor']) && isset($_POST['valor_busqueda'])) {
        $datos = $teacherClass->searchProfessorsByField($_POST['valor_busqueda']);
    } elseif (isset($_POST['buscar_alumno']) && isset($_POST['valor_busqueda'])) {
        $datos = $alumnClass->searchAlumnsByField($_POST['valor_busqueda']);
    }
}

// Cuando pulsa en profesor o alumno, se carga la tabla con todos los datos
$columnas = [];

if (isset($_GET['profesor'])) {
    $datos = $teacherClass->getAllProfessors();
    $columnas = $teacherClass->getColumnNames();
} elseif (isset($_GET['alumno'])) {
    $datos = $alumnClass->getAllAlumns();
    $columnas = $alumnClass->getColumnNames();
}

if (isset($_GET['agregar_profesor'])) {
    // Obtener los datos de un profesor existente (si es necesario)
    $profesorExistente = $teacherClass->getProfessorById($_GET['agregar_profesor']);

    // Mostrar el formulario de inserción de profesor
    echo '<script>';
    echo 'mostrarFormularioInsertar("agregar", "profesor");';
    echo '</script>';

    // Llenar los campos del formulario con los datos del profesor existente
    if ($profesorExistente) {
        echo '<script>';
        echo 'document.getElementById("nombreProfesor").value = "' . $profesorExistente['nombre'] . '";';
        echo 'document.getElementById("apellido1Profesor").value = "' . $profesorExistente['apellido1'] . '";';
        echo 'document.getElementById("apellido2Profesor").value = "' . $profesorExistente['apellido2'] . '";';
        echo '</script>';
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" type="text/css" href="css/home.css">

    <script>

        function mostrarFormularioInsertar(accion, tipo) {
            if (accion === 'agregar') {
                if (tipo === 'profesor') {
                    document.getElementById('formularioAgregarProfesor').style.display = 'block';
                    document.getElementById('formularioAgregarAlumno').style.display = 'none';
                } else if (tipo === 'alumno') {
                    document.getElementById('formularioAgregarAlumno').style.display = 'block';
                    document.getElementById('formularioAgregarProfesor').style.display = 'none';
                }
            }
        }

        function mostrarFormulario(btn) {
            var tipo = btn.dataset.tipo;
            if (tipo === 'profesor') {
                document.getElementById('formularioModificacionProfesor').style.display = 'block';
                document.getElementById('idProfesorModificar').value = btn.dataset.id;
                cargarDatosProfesor(btn.dataset.id);
            } else if (tipo === 'alumno') {
                document.getElementById('formularioModificacionAlumno').style.display = 'block';
                document.getElementById('idAlumnoModificar').value = btn.dataset.id;
                cargarDatosAlumno(btn.dataset.id);
            }
        }

        function cerrarFormulario(tipo) {
            if (tipo === 'profesor') {
                document.getElementById('formularioModificacionProfesor').style.display = 'none';
            } else if (tipo === 'alumno') {
                document.getElementById('formularioModificacionAlumno').style.display = 'none';
            }
        }

        function cerrarFormularioInsertar(tipo) {
            if (tipo === 'profesor') {
                document.getElementById('formularioAgregarProfesor').style.display = 'none';
            } else if (tipo === 'alumno') {
                document.getElementById('formularioAgregarAlumno').style.display = 'none';
            }
        }

    </script>
</head>

<body>

    <div class="container">
        <div class="container">
            <div class="sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="?profesor" style="background-color: #191970;">Profesor</a>
                        <div class="input-group mt-2">
                            <form id="formBuscarProfesores" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <input id="buscarProfesorInput" type="search" class="form-control"
                                    placeholder="Buscar profesor" aria-label="Buscar profesor" name="valor_busqueda">
                                <button id="buscarProfesorBtn" class="btn btn-outline-secondary" type="submit"
                                    name="buscar_profesor">Buscar</button>
                            </form>
                        </div>
                        <button class="btn btn-primary mt-2"
                            onclick="mostrarFormularioInsertar('agregar', 'profesor')">+ Añadir Profesor</button>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link" href="?alumno" style="background-color: #191970;">Alumnos</a>
                        <div class="input-group mt-2">
                            <form id="formBuscarAlumnos" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <input id="buscarAlumnoInput" type="search" class="form-control"
                                    placeholder="Buscar alumno" aria-label="Buscar alumno" name="valor_busqueda">
                                <button id="buscarProfesorBtn" class="btn btn-outline-secondary" type="submit"
                                    name="buscar_alumno">Buscar</button>
                            </form>
                        </div>
                        <button class="btn btn-primary mt-2" onclick="mostrarFormularioInsertar('agregar', 'alumno')">+
                            Añadir Alumno</button>
                    </li>
                </ul>
            </div>

            <main class="options-container">
                <!-- Aquí se mostrarán las tablas y los formularios -->
            </main>
        </div>

        <!-- Formulario para agregar profesor -->
        <div id="formularioAgregarProfesor" class="formulario"
            style="display: none; max-height: 400px; overflow-y: auto;">
            <h2>Añadir Profesor</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <?php foreach ($columnas as $columna): ?>
                    <?php if ($columna !== 'idProfesor'): ?>
                        <div class="mb-3">
                            <label for="<?php echo $columna; ?>Profesor" class="form-label">
                                <?php echo $columna; ?>
                            </label>
                            <input type="text" class="form-control" id="<?php echo $columna; ?>Profesor"
                                name="<?php echo $columna; ?>Profesor">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <button type="submit" name="agregar_profesor" class="btn btn-primary">Agregar Profesor</button>
                <button type="button" class="btn btn-secondary"
                    onclick="cerrarFormularioInsertar('profesor')">Cancelar</button>
            </form>
        </div>
    </div>

    <!-- Formulario para agregar alumno -->
    <div id="formularioAgregarAlumno" class="formulario" style="display: none; max-height: 400px; overflow-y: auto;">
        <h2>Añadir Alumno</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <?php foreach ($columnas as $columna): ?>
                <?php if ($columna !== 'idAlumno'): ?>
                    <div class="mb-3">
                        <label for="<?php echo $columna; ?>Alumno" class="form-label">
                            <?php echo $columna; ?>
                        </label>
                        <input type="text" class="form-control" id="<?php echo $columna; ?>Alumno"
                            name="<?php echo $columna; ?>Alumno">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <button type="submit" name="agregar_alumno" class="btn btn-primary">Agregar Alumno</button>
            <button type="button" class="btn btn-secondary"
                onclick="cerrarFormularioInsertar('alumno')">Cancelar</button>
        </form>
    </div>


    <main class="options-container">
        <table class="table">
            <thead>
                <tr>
                    <?php foreach ($columnas as $columna): ?>
                        <th scope="col">
                            <?php echo $columna; ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($datos)): ?>
                    <?php foreach ($datos as $fila): ?>
                        <tr>
                            <?php foreach ($fila as $valor): ?>
                                <td>
                                    <?php echo $valor; ?>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <?php if (isset($_GET['profesor'])): ?>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display: inline;">
                                        <input type="hidden" name="id_profesor" value="<?php echo $fila['idProfesor'] ?? ''; ?>">
                                        <button type="button" class="btn btn-primary" onclick="mostrarFormulario(this)"
                                            data-tipo="profesor"
                                            data-id="<?php echo $fila['idProfesor'] ?? ''; ?>">Modificar</button>
                                    </form>
                                <?php elseif (isset($_GET['alumno'])): ?>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display: inline;">
                                        <input type="hidden" name="id_alumno" value="<?php echo $fila['idAlumno'] ?? ''; ?>">
                                        <button type="button" class="btn btn-primary" onclick="mostrarFormulario(this)"
                                            data-tipo="alumno" data-id="<?php echo $fila['idAlumno'] ?? ''; ?>">Modificar</button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display: inline;">
                                    <?php if (isset($_GET['profesor'])): ?>
                                        <input type="hidden" name="id_profesor" value="<?php echo $fila['idProfesor'] ?? ''; ?>">
                                        <button type="submit" name="eliminar_profesor" class="btn btn-danger">Borrar</button>
                                    <?php elseif (isset($_GET['alumno'])): ?>
                                        <input type="hidden" name="id_alumno" value="<?php echo $fila['idAlumno'] ?? ''; ?>">
                                        <button type="submit" name="eliminar_alumno" class="btn btn-danger">Borrar</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo count($columnas) + 1; ?>">No se encontraron resultados. Debes realiza una búsqueda</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
    </div>

    <div id="formularioModificacionProfesor"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <h2>Modificar Profesor</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" id="idProfesorModificar" name="id_profesor">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre">
            </div>
            <div class="mb-3">
                <label for="apellido1" class="form-label">Apellido 1</label>
                <input type="text" class="form-control" id="apellido1" name="apellido1">
            </div>
            <div class="mb-3">
                <label for="apellido2" class="form-label">Apellido 2</label>
                <input type="text" class="form-control" id="apellido2" name="apellido2">
            </div>
            <button type="submit" name="modificar_profesor" class="btn btn-primary">Aceptar</button>
            <button type="button" onclick="cerrarFormulario('profesor')" class="btn btn-secondary">Cancelar</button>
        </form>
    </div>

    <div id="formularioModificacionAlumno"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <h2>Modificar Alumno</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" id="idAlumnoModificar" name="id_alumno">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre">
            </div>
            <div class="mb-3">
                <label for="apellido1" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido1" name="apellido1">
            </div>
            <button type="submit" name="modificar_alumno" class="btn btn-primary">Aceptar</button>
            <button type="button" onclick="cerrarFormulario('alumno')" class="btn btn-secondary">Cancelar</button>
        </form>
    </div>

</body>

</html>