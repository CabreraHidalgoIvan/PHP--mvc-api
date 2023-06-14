<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div id="buscar">
    <h2>Artículo:</h2>
    <?php
    foreach ($articulos as $articulo) {
        echo "<a>ID: " . $articulo["id"] . "</a>";
        echo "<p>CÓDIGO: " . $articulo["codigo"] . "</p>";
        echo "<p>DESCRIPCIÓN: " . $articulo["descripcion"] . "</p>";
        echo "<p>EMBALAJE: " . $articulo["embalaje"] . "</p>";
        echo "<p>NOMBRE TÉCNICO: " . $articulo["nombre_tecnico"] . "</p>";

    }
    ?>
    </table>
    <a href="./">Volver atrás</a>
</div>
</body>
</html>