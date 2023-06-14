<?php

namespace App\Models;

class clientAPIModel {

    private $options = null;

    function __construct() {
        $this->options = array(
            CURLOPT_URL => "http://apirestarticulos.local/articulos",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/xml; charset=utf-8"
            )
        );
    }

    public function listarTodos()
    {
        $ch = curl_init();
        curl_setopt_array($ch, $this->options);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {

            $jsonStartPos = strpos($response, '{'); // Encuentra la posición del primer caracter '{'
            if ($jsonStartPos !== false) {
                $jsonStr = substr($response, $jsonStartPos - 1); // Extrae la cadena desde la posición antes del primer '{' hasta el final
            } else {
                echo "No se encontró el inicio del JSON en la respuesta.";
                return NULL;
            }


            $articulos = json_decode($jsonStr, true);
            if (json_last_error() == JSON_ERROR_NONE) { // Añade esta línea
                return $articulos;
            } else {
                echo "Error en la decodificación del JSON: " . json_last_error_msg(); // Añade esta línea
                return NULL;
            }
        } else {
            echo "Error al listar todos los artículos" . PHP_EOL;
            return NULL;
        }
    }

    public function buscarArticulos($articuloId) {

        $this->options[CURLOPT_URL] = "http://apirestarticulos.local/articulos/".$articuloId;

        $ch = curl_init();
        curl_setopt_array($ch, $this->options);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {

            $jsonStartPos = strpos($response, '{'); // Encuentra la posición del primer caracter '{'
            if ($jsonStartPos !== false) {
                $jsonStr = substr($response, $jsonStartPos - 1); // Extrae la cadena desde la posición antes del primer '{' hasta el final
            } else {
                echo "No se encontró el inicio del JSON en la respuesta.";
                return NULL;
            }

            echo $jsonStr;
            $articulos = json_decode($jsonStr, true);
            if (json_last_error() == JSON_ERROR_NONE) { // Añade esta línea
                return $articulos;
            } else {
                echo "Error en la decodificación del JSON: " . json_last_error_msg(); // Añade esta línea
                return NULL;
            }

        } else {
            echo "Error al listar todos los artículos" . PHP_EOL;
            return NULL;
        }

    }

    public function insertarArticulo($codigo, $descripcion, $embalaje, $nombre_tecnico) {

        $this->options[CURLOPT_URL] = "http://apirestarticulos.local/articulos";
        $this->options[CURLOPT_CUSTOMREQUEST] = "POST";

        $ch = curl_init();
        curl_setopt_array($ch, $this->options);

        $data = array(
            'codigo' => $codigo,
            'descripcion' => $descripcion,
            'embalaje' => $embalaje,
            'nombre_tecnico' => $nombre_tecnico
        );
        $data = json_encode($data);//IMPORTANTE PARA QUE FUNCIONE
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 201) {
            echo "Artículo ingresado correctamente" . PHP_EOL;
            return $response;
        } else {
            echo "Error al ingresar el artículo" . PHP_EOL;
        }
    }

    public function modificarArticulo($articuloId, $codigo, $descripcion, $embalaje, $nombre_tecnico) {

        $this->options[CURLOPT_URL] = "http://apirestarticulos.local/articulos/".$articuloId;
        $this->options[CURLOPT_CUSTOMREQUEST] = "PUT";

        $ch = curl_init();
        curl_setopt_array($ch, $this->options);

        $data = array(
            'codigo' => $codigo,
            'descripcion' => $descripcion,
            'embalaje' => $embalaje,
            'nombre_tecnico' => $nombre_tecnico
        );
        $data = json_encode($data);//IMPORTANTE PARA QUE FUNCIONE
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            echo "Artículo actualizado correctamente" . PHP_EOL;
            return $response;
        } else {
            echo "Error al actualizar el artículo" . PHP_EOL;
        }
    }

    public function borrarArticulo($articuloId) {

        $this->options[CURLOPT_URL] = "http://apirestarticulos.local/articulos/".$articuloId;
        $this->options[CURLOPT_CUSTOMREQUEST] = "DELETE";

        $ch = curl_init();
        curl_setopt_array($ch, $this->options);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            echo "Artículo eliminado correctamente" . PHP_EOL;
            return $response;
        } else {
            echo "Error al eliminar el artículo" . PHP_EOL;
        }
    }

    public function busquedaAvanzada($codigo, $descripcion, $embalaje, $nombre_tecnico) {

        $this->options[CURLOPT_URL] = "http://apirestarticulos.local/articulos";
        $this->options[CURLOPT_CUSTOMREQUEST] = "AS";

        $ch = curl_init();
        curl_setopt_array($ch, $this->options);

        $data = array(
            'codigo' => $codigo,
            'descripcion' => $descripcion,
            'embalaje' => $embalaje,
            'nombre_tecnico' => $nombre_tecnico
        );
        $data = json_encode($data, JSON_THROW_ON_ERROR);//IMPORTANTE PARA QUE FUNCIONE
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            echo "Artículo encontrado correctamente" . PHP_EOL;
            return $response;
        } else {
            echo "Error al buscar el artículo" . PHP_EOL;
        }
    }

    public function subirImagen($id_articulo, $imgFile) {

        $this->options[CURLOPT_URL] = "http://apirestarticulos.local/articulos";
        $this->options[CURLOPT_CUSTOMREQUEST] = "UPLOAD";

        $ch = curl_init();
        curl_setopt_array($ch, $this->options);

        $data = array(
            'id_articulo' => $id_articulo,
            'fichero' => $imgFile
        );
        $data = json_encode($data);//IMPORTANTE PARA QUE FUNCIONE

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 201) {
            echo "Artículo ingresado correctamente" . PHP_EOL;
            return $response;
        } else {
            echo "Error al ingresar el artículo" . PHP_EOL;
        }
    }
}