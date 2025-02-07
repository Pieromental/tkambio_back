# TKAMBIO Backend

## Descripción

Este es el backend del proyecto **TKAMBIO**, desarrollado en Laravel. Proporciona autenticación con JWT, generación de reportes en Excel y almacenamiento de archivos. Actualmente, utiliza **SQLite** como base de datos y jobs en base de datos. Próximamente será dockerizado.

## Tecnologías Utilizadas

-   **Laravel** (versión compatible con PHP 8.1.10)
-   **PHP 8.1.10**
-   **JWT Authentication**
-   **SQLite como base de datos**
-   **Queue con Database**
-   **Maatwebsite/Excel** para generar reportes en Excel

## Requisitos Previos

Antes de instalar, asegúrate de tener lo siguiente:

-   **PHP 8.1.10**
-   **Composer** instalado
-   **Extensiones de PHP necesarias para SQLite:**
    -   pdo_sqlite
    -   sqlite3

### Instalación de SQLite en diferentes entornos

#### 🔹 **XAMPP (Windows)**

1. Abre `php.ini` desde el directorio de XAMPP (`C:\xampp\php\php.ini`).
2. Habilita las siguientes líneas eliminando `;` al inicio:
    ```ini
    extension=pdo_sqlite
    extension=sqlite3
    ```
3. Reinicia Apache desde el Panel de Control de XAMPP.

#### 🔹 **Laragon (Windows)**

1. Abre Laragon y ve a `Menú -> PHP -> php.ini`.
2. Busca y descomenta estas líneas:
    ```ini
    extension=pdo_sqlite
    extension=sqlite3
    ```
3. Reinicia Laragon.

#### 🔹 **Linux (Ubuntu/Debian)**

1. Instala las extensiones necesarias:
    ```sh
    sudo apt update
    sudo apt install php-sqlite3
    ```
2. Reinicia Apache:
    ```sh
    sudo systemctl restart apache2
    ```

## Instalación y Configuración

1. Clonar el repositorio:

    ```sh
    git clone https://github.com/tu-usuario/tkambio-backend.git
    cd tkambio-backend
    ```

2. Instalar dependencias:

    ```sh
    composer install
    ```

3. Copiar archivo de entorno y configurar:

    ```sh
    cp .env.example .env
    ```

    Editar el archivo `.env` y configurar la base de datos y sistema de colas:

    ```ini
    DB_CONNECTION=sqlite
    QUEUE_CONNECTION=database
    ```

4. Generar la clave de la aplicación y JWT:

    ```sh
    php artisan key:generate
    php artisan jwt:secret
    ```

5. Ejecutar migraciones y seeders:
    ```sh
    php artisan migrate --seed
    ```
6. Iniciar el servidor:
    ```sh
    php artisan serve
    ```

## Uso de API

### Autenticación con JWT

-   **Login:** `POST /login`
-   **Obtener usuario autenticado:** `GET /user` (requiere token)
-   **Logout:** `POST /logout`

### Generación de Reportes

-   **Crear reporte:** `POST /api/generate-report`
    -   Requiere `title`, `start_date`, `end_date`
-   **Listar reportes:** `GET /api/list-reports`
-   **Descargar reporte:** `GET /api/download-report/{report_id}`

### 🔑 Credenciales de prueba

Para poder loguearte, usa las siguientes credenciales:

```
   {
        "email": "admin@example.com,
        "password": "password123"
   }
```

## Manejo de Jobs

Para procesar reportes en background:

```sh
php artisan queue:work
```

## Ignorar Archivos en Git

Para evitar que los archivos Excel generados se suban a Git, asegúrate de agregar esta línea en `.gitignore`:

```
storage/app/public/reports/*.xlsx
```

## Próximos Pasos

-   Dockerizar la aplicación
-   Mejorar la documentación de la API
-   Implementar WebSockets para notificaciones en tiempo real
