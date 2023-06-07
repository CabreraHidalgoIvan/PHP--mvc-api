# Creación de una API REST de Contactos

## Índice

- [1. Crear la estructura del proyecto](#estructura)
- [2. Crear la base de datos](#bd)
- [3. Crear las clases para gestionar la conexión a la base de datos](#clases)
- [4. Crear la clase para controlar los contactos de la base de datos](#claseContactos)

## 1. Crear la estructura del proyecto <a id="estructura"></a>

1. Crearemos una carpeta ````/app```` en la raíz del proyecto
2. En la raíz del proyecto crearemos el archivo ````composer.json```` con el siguiente contenido:
```json
{
  "require": {
    "vlucas/phpdotenv": "^2.4"
  },
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    }
  }
}
```
> - En la sección ````require```` indicamos las dependencias:
>   - ````vlucas/phpdotenv````: para gestionar las variables de entorno.
>
> - En la sección ````autoload```` indicamos el espacio de nombres y la carpeta donde se encuentra el código fuente
>   - En este caso el espacio de nombres(namespace) es ````App```` y el código fuente se encuentra en la carpeta ````app````
>   - El espacio de nombres es el que utilizaremos en el código fuente para referirnos a las clases (no necesitaremos include)
>
> - Ejecutamos el comando ````composer install```` para instalar las dependencias

3. Una vez instalado composer nos aparecerá la carpeta ````vendor```` con las dependencias instaladas
   1. ````vluca/phpdotenv````: para gestionar las variables de entorno
   2. ````composer y symfony````: para gestionar las dependencias
   3. ````autoload````: para cargar las clases automáticamente
   4. ````composer.lock````: es el que se utilizará para instalar las dependencias en el servidor de producción
   

4. Creamos el archivo ````.gitignore```` en la raíz del proyecto y añadimos la carpeta ````vendor```` para que no se suba al repositorio
```gitignore
vendor/
.env
```


5. Creamos el archivo ````.env.example```` con las variables de entorno de ejemplo y ````.env```` con las variables de entorno reales
```dotenv
# .env.example
DB_HOST=localhost
DB_NAME=
DB_USER=
DB_PASSWORD=
```

- En el archivo ````.env```` indicamos las variables de entorno que utilizaremos en el proyecto (en nuestro caso la conexión a la base de datos)
```dotenv
# .env
DB_HOST=localhost
DB_NAME=contactos
DB_USER=root
DB_PASSWORD=
```

6. Creamos el archivo ````bootstrap.php```` para cargar las dependencias y las variables de entorno
   1. En el archivo ````bootstrap.php```` cargamos las dependencias con ````require```` y las variables de entorno con ````Dotenv\Dotenv````
   2. Para comprobar que hasta ahora hemos seguido los pasos correctamente y ver que las dependencias funcionan podemos
   imprimir por pantalla una variable de entorno con ````getenv()````. Si nos devuelve el valor de la variable de entorno es que todo está correcto
```php
<?php
    
    require  'vendor/autoload.php';
    
    use Dotenv\Dotenv;
    
    $dotenv = new Dotenv(__DIR__);
    $dotenv->load();
    
    echo getenv('DB_HOST');
```