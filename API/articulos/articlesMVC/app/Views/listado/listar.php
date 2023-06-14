<div id="listar">
    <h2>Listado de los Artículos</h2>
    <table border="1">
        <tr>

            <th>ID</th>
            <th>CÓDIGO</th>
            <th>DESCRIPCIÓN</th>
            <th>EMBALAJE</th>
            <th>NOMBRE TÉCNICO</th>
        </tr>

        <?php


    foreach ($articulos as $articulo) {
        echo "<tr>";
        echo "<td><a href='#' onclick='fetchArticleData(" . $articulo['id'] . ")'>ID: " . $articulo['id'] . "</a></td>";
        echo "<td>" . "Código: " . $articulo['codigo'] . "</td>";
        echo "<td>" . "Descripcion: " . $articulo['descripcion'] . "</td>";
        echo "<td>" . "Embalaje: " . $articulo['embalaje'] . "</td>";
        echo "<td>" . "Nombre: " . $articulo['nombre_tecnico'] . "</td>";
        echo "</tr>";
    }

?>
</table>
<a href="./">Volver atrás</a>
</div>