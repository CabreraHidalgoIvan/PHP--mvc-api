<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APLICACIÓN</title>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
        }

        header {
            background-color: #f1f1f1;
            padding: 20px;
            text-align: center;
        }

        nav {
            display: flex;
            justify-content: center;
            background-color: #333;
            padding: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
        }

        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        main {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
        }

        section {
            background-color: #f1f1f1;
            border-radius: 5px;
            padding: 20px;
            margin: 10px;
            width: 30%;
        }

        input[type="submit"], input[type="number"], input[type="text"] {
            margin: 5px 0;
        }
    </style>

    <script>
        function fetchArticleData(id) {
            if (id === "") {
                // Clear input fields if ID is empty
                document.querySelector('input[name="codigoUpdate"]').value = "";
                document.querySelector('input[name="descripcionUpdate"]').value = "";
                document.querySelector('input[name="embalajeUpdate"]').value = "";
                document.querySelector('input[name="nombre_tecnicoUpdate"]').value = "";
                return;
            }

            // Fetch article data from your API using the selected ID
            fetch(`http://apirestarticulos.local/articulos/${id}`)
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error(`Error fetching article data: ${response.statusText}`);
                    }
                })
                .then(data => {
                    const articleData = data[0];

                    document.querySelector('input[name="codigoUpdate"]').value = articleData.codigo;
                    document.querySelector('input[name="descripcionUpdate"]').value = articleData.descripcion;
                    document.querySelector('input[name="embalajeUpdate"]').value = articleData.embalaje;
                    document.querySelector('input[name="nombre_tecnicoUpdate"]').value = articleData.nombre_tecnico;
                })
                .catch(error => {
                    console.error('Error fetching article data:', error);
                });
        }

        function loadArticleIds() {
            fetch('http://apirestarticulos.local/articulos')
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error(`Error fetching article data: ${response.statusText}`);
                    }
                })
                .then(data => {
                    const selectElement = document.querySelector('select[name="idUpdate"]');
                    data.forEach(article => {
                        const option = document.createElement('option');
                        option.value = article.id;
                        option.text = article.id;
                        selectElement.add(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching article data:', error);
                });
        }

        function loadArticleIdsDelete() {
            fetch('http://apirestarticulos.local/articulos')
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error(`Error fetching article data: ${response.statusText}`);
                    }
                })
                .then(data => {
                    const selectElement = document.querySelector('select[name="idDelete"]');
                    data.forEach(article => {
                        const option = document.createElement('option');
                        option.value = article.id;
                        option.text = article.id;
                        selectElement.add(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching article data:', error);
                });
        }

        document.addEventListener('DOMContentLoaded', loadArticleIds);
        document.addEventListener('DOMContentLoaded', loadArticleIdsDelete);

    </script>

</head>

<body>
<header>
    <h1>Bienvenido/a</h1>
    <p>En esta APP puedes gestionar los artículos.</p>
</header>

<nav>
    <a href="#">Búsqueda de Artículos</a>
    <a href="#">Búsqueda Avanzada</a>
    <a href="#">Insercción de Artículos</a>
    <a href="#">Modificación de Artículos</a>
</nav>

<main>

    <section>

        <h1>Búsqueda de Artículos</h1>

        <?php
        if (!isset($_POST['listar']) && !isset($_POST['buscar'])) {
            echo '
            <form action="" method="post">
            <input type="submit" value="Listar Artículos" name="listar">
            <input type="number" name="articulo">
            <input type="submit" value="Buscar Artículo" name="buscar">
            </form>
            ';
        }

        // Controlador
        if (isset($_POST['listar']) || (isset($_POST['buscar']) && isset($_POST['articulo']))) {
            require_once('../app/Controllers/clientController.php');
        };

        ?>
    </section>

    <section>

        <h1>Búsqueda de Artículos Avanzada</h1>

        <?php
        if (!isset($_POST['buscarAvanzado'])) {
            echo '
            <form action="" method="post">
            <input type="text" name="codigo" placeholder="Código">
            <input type="text" name="descripcion" placeholder="Descripción">
            <input type="text" name="embalaje" placeholder="Embalaje">
            <input type="text" name="nombre_tecnico" placeholder="Nombre técnico">
            <input type="submit" value="Buscar Modo Avanzado" name="buscarAvanzado">
            </form>
            ';
        }

        // Controlador
        if (isset($_POST['buscarAvanzado'])) {
            require_once('../app/Controllers/clientController.php');
        };

        ?>
    </section>

    <section>
        <h1>Insercción de Artículos</h1>

        <?php
        if (!isset($_POST['insertar'])) {
            echo '
            <form action="" method="post">
            <input type="text" name="codigo" placeholder="Código">
            <input type="text" name="descripcion" placeholder="Descripción">
            <input type="text" name="embalaje" placeholder="Embalaje">
            <input type="text" name="nombre_tecnico" placeholder="Nombre técnico">
            <input type="submit" value="Insertar Artículo" name="insertar">
            </form>
            ';
        }

        // Controlador
        if (isset($_POST['insertar'])) {
            require_once('../app/Controllers/clientController.php');
        };
        ?>


    </section>

<section>
        <h1>Modificación de Artículos</h1>

        <?php
        if (!isset($_POST['modificar'])) {
            echo '
            <form action="" method="post">
            <select name="idUpdate" onchange="fetchArticleData(this.value)">
                <option value="">Seleccione un ID</option>
            </select>
            <input type="text" name="codigoUpdate" placeholder="Código">
            <input type="text" name="descripcionUpdate" placeholder="Descripción">
            <input type="text" name="embalajeUpdate" placeholder="Embalaje">
            <input type="text" name="nombre_tecnicoUpdate" placeholder="Nombre técnico">
            <input type="submit" value="Modificar Artículo" name="modificar">
            </form>
            ';
        }

        // Controlador
        if (isset($_POST['modificar'])) {
            require_once('../app/Controllers/clientController.php');
        };
        ?>

    </section>

    <section>
        <h1>Borrado de Artículos</h1>

        <?php
        if (!isset($_POST['borrar'])) {
            echo '
            <form action="" method="post">
             <select name="idDelete" onchange="fetchArticleData(this.value)">
                <option value="">Seleccione un ID</option>
            </select>
            <input type="submit" value="Borrar Artículo" name="borrar">
            </form>
            ';
        }

        // Controlador
        if (isset($_POST['borrar'])) {
            require_once('../app/Controllers/clientController.php');
        };
        ?>

    </section>

    <section>
        <h1>Subida de Imágenes</h1>

        <?php
        if (!isset($_POST['img'])) {
            echo '
            <form action="" method="post">
            <input type="text" name="codigo" placeholder="Código del artículo">
            <input type="file" name="imgFile" accept="image/*">
            <input type="submit" value="Subir Imagen" name="img">
            </form>
            ';
        }

        // Controlador
        if (isset($_POST['img'])) {
            require_once('../app/Controllers/clientController.php');
        };
        ?>

    </section>
</main>
</body>

</html>