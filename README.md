# TKAMBIO Backend

## Descripción
Este es el backend del proyecto **TKAMBIO**, desarrollado en Laravel. Proporciona autenticación con JWT, generación de reportes en Excel y almacenamiento de archivos. Actualmente, utiliza Microsoft SQL Server como base de datos y jobs en base de datos. Próximamente será dockerizado.

## Tecnologías Utilizadas
- **Laravel** (versión compatible con PHP 8.1.10)
- **PHP 8.1.10**
- **JWT Authentication**
- **MSSQL como base de datos**
- **Queue con Database**
- **Maatwebsite/Excel** para generar reportes en Excel

## Requisitos Previos
Antes de instalar, asegúrate de tener lo siguiente:

- **PHP 8.1.10**
- **Composer** instalado
- **Microsoft SQL Server** instalado y configurado
- **Extensiones de PHP necesarias:**
  - pdo_sqlsrv
  - pdo_mysql
  - gd
  - exif
  - fileinfo
  - mbstring
  - intl
  - openssl

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
   Editar el archivo `.env` y configurar la base de datos:
   ```ini
   DB_CONNECTION=sqlsrv
   DB_HOST=127.0.0.1
   DB_PORT=1433
   DB_DATABASE=TKambioDB
   DB_USERNAME=tkambio_user
   DB_PASSWORD=tkambio123
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
- **Login:** `POST /login`

### Generación de Reportes
- **Crear reporte:** `POST /api/generate-report`
  - Requiere `title`, `start_date`, `end_date`
- **Listar reportes:** `GET /api/list-reports`
- **Descargar reporte:** `GET /api/download-report/{report_id}`

## Manejo de Jobs
Para procesar reportes en background:
```sh
php artisan queue:work
```

## Próximos Pasos
- Dockerizar la aplicación
- Mejorar la documentación de la API
- Implementar WebSockets para notificaciones en tiempo real

