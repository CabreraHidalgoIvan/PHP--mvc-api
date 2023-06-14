<?php

namespace App\Controllers;

use App\Models\clientAPIModel;

echo $_POST['insertar'];
if (isset($_POST['listar'])) {
  require_once('../app/Models/clientAPIModel.php');
  $model = new ClientAPIModel();
    $articulos = $model->listarTodos();
    include '../app/Views/listado/listar.php';
};

if (isset($_POST['buscar'])) {
  require_once('../app/Models/clientAPIModel.php');
  $model = new ClientAPIModel();
  $articulos = $model->buscarArticulos($_POST['articulo']);
  include '../app/Views/listado/buscar.php';
}


if (isset($_POST['insertar'])) {
    require_once('../app/Models/clientAPIModel.php');
    $model = new ClientAPIModel();
    $articulos = $model->insertarArticulo($_POST['codigo'], $_POST['descripcion'], $_POST['embalaje'], $_POST['nombre_tecnico']);
    include '../app/Views/insertar/insertar.php';
}

if (isset($_POST['modificar'])) {
    require_once('../app/Models/clientAPIModel.php');
    $model = new ClientAPIModel();
    $articulos = $model->modificarArticulo($_POST['idUpdate'], $_POST['codigoUpdate'], $_POST['descripcionUpdate'], $_POST['embalajeUpdate'], $_POST['nombre_tecnicoUpdate']);
    include '../app/Views/modificar/modificar.php';
}

if (isset($_POST['borrar'])) {
    require_once('../app/Models/clientAPIModel.php');
    $model = new ClientAPIModel();
    $articulos = $model->borrarArticulo($_POST['idDelete']);
    include '../app/Views/borrar/borrar.php';
}

// ------------ Busqueda Avanzada ------------ //

if (isset($_POST['buscarAvanzado'])) {
    require_once('../app/Models/clientAPIModel.php');
    $model = new ClientAPIModel();

    $codigo = $_POST['codigo'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $embalaje = $_POST['embalaje'] ?? null;
    $nombre_tecnico = $_POST['nombre_tecnico'] ?? null;

    if ($codigo == null && $descripcion == null && $embalaje == null && $nombre_tecnico == null) {
        echo '<h3>Debe rellenar al menos un campo</h3>';
        $value = null;
        return;
    } else {
        $articulos = $model->busquedaAvanzada($codigo, $descripcion, $embalaje, $nombre_tecnico);

        // Aquí puedes incluir la vista para mostrar los resultados de la búsqueda avanzada
        include '../app/Views/listado/busquedaAvanzadaResultado.php';
    }
}

/*
if (isset($_POST['busquedaAvanzada'])) {
    echo "hola";
    require_once('../app/Models/clientAPIModel.php');
    $model = new ClientAPIModel();
    $articulos = $model->busquedaAvanzada($_POST['codigo'], $_POST['descripcion'], $_POST['embalaje'], $_POST['nombre_tecnico']);
    include '../app/Views/listado/busquedaAvanzadaResultado.php';
}*/

// ------------ IMÁGENES ------------ //

if (isset($_POST['img'])) {
    require_once('../app/Models/clientAPIModel.php');
    $model = new ClientAPIModel();
    $articulos = $model->subirImagen($_POST['codigo'], $_POST['imgFile']);
    include '../app/Views/imagenes/subirImagen.php';
}