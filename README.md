# TKAMBIO Backend

## Descripción

Este es el backend del proyecto **TKAMBIO**, desarrollado en Laravel. Proporciona autenticación con JWT, generación de reportes en Excel y almacenamiento de archivos, se utilizan colas para realizar acciones en segundo plano y se usa SOKETI para manejo de WebSockets. Para poder trabajar con WEBSOCKETS es necesario levantar el proyecto usando Docker ya que se ha configurado el para tener un container que levante el WEBSOCKET de SOKETI. Actualmente, utiliza **SQLite** como base de datos y jobs en base de datos.
## Tecnologías Utilizadas

-   **Laravel** (versión compatible con PHP 8.1.10)
-   **PHP 8.1.10**
-   **JWT Authentication**
-   **SQLite como base de datos**
-   **Queue con Database**
-   **Pusher**
-   **Docker version 26.1.1
-   **Docker Compose version v2.27.0-desktop.2
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
    APP_NAME=Laravel
    APP_ENV=local
    APP_KEY=base64:vj/hoXSZh70VeIRfegGqLMuL+SQcD1QwnCZBS8L4/t0=
    APP_DEBUG=true
    APP_URL=http://localhost
    
    LOG_CHANNEL=stack
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug
    
    DB_CONNECTION=sqlite
    
    BROADCAST_DRIVER=pusher
    CACHE_DRIVER=file
    FILESYSTEM_DISK=local
    QUEUE_CONNECTION=database
    SESSION_DRIVER=file
    SESSION_LIFETIME=120
    
    MEMCACHED_HOST=127.0.0.1
    
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    
    MAIL_MAILER=smtp
    MAIL_HOST=mailpit
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"
    
    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=
    AWS_USE_PATH_STYLE_ENDPOINT=false
    
    PUSHER_APP_ID=local-app-id
    PUSHER_APP_KEY=local-app-key
    PUSHER_APP_SECRET=local-app-secret
    PUSHER_HOST=soketi
    PUSHER_PORT=6001
    PUSHER_SCHEME=http
    PUSHER_APP_CLUSTER=mt1
    
    VITE_APP_NAME="${APP_NAME}"
    VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    VITE_PUSHER_HOST="${PUSHER_HOST}"
    VITE_PUSHER_PORT="${PUSHER_PORT}"
    VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
    VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
    
    JWT_SECRET=UpFL7l2nMtzukKA28SFMh6X1hPiIv5vGUsEZudpERijPi4xuCNYBafBubY8sxB68

    ```

4. Ejecutar migraciones y seeders:
    ```sh
    php artisan migrate:fresh --seed --force
    ```
5. Iniciar el servidor:
    ```sh
    php artisan serve
    ```
6. Si prefieres levantar el proyecto usando docker:
    ```sh
    docker-compose up -d --build
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

