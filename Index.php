<?php

// Depsues del login siempre se hara esta logica en las paginas
// para ello en el boton que te redirige a la pagina en conceto en name="page" que sera recogido aqui
// y procesara name=page junto con value="Nombre de la pagina" (ejemplo:altaAlumno), entra en el case 
// y te lleva a la pagina con "include"

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'home':
        include 'pages/home.php';
        break;
    case 'altaAlumno':
        include 'pages/altaAlumno.php';
        break;
    case 'altaProfesor':
        include 'pages/altaProfesor.php';
        break;
    default:
        include 'pages/home.php';
        break;
}
?>