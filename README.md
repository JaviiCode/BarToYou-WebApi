# BarToYou API REST

## Descripción del proyecto

BarToYou es una solución innovadora para el sector de la hostelería, centrada en facilitar la gestión y el pedido de bebidas en hoteles todo incluido.  
La API REST es la columna vertebral del sistema, gestionando toda la lógica de negocio, usuarios, pedidos y bebidas personalizadas.  
Desarrollada en **Laravel 12.1.1**, proporciona endpoints seguros y eficientes para el funcionamiento de la Web App.

## Tecnologías utilizadas

- **PHP 8+**
- **Laravel 12.1.1**
- **MySQL**
- **JWT para autenticación**
- **Composer**
- **XAMPP / Docker / Laragon**

## Instalación y configuración

1. Clonar el repositorio:  
   `git clone https://github.com/JaviiCode/BarToYou-WebApi`

2. Instalar dependencias:  
   `composer install`

3. Configurar el archivo `.env` (copiar desde `.env.example` y editar credenciales DB).

4. Generar la clave de la app:  
   `php artisan key:generate`

5. Importar el script SQL incluido.

6. Ejecutar migraciones:  
   `php artisan migrate`

7. Iniciar el servidor:  
   `php artisan serve`

## Organización del código

- **/app**: lógica de negocio y controladores.
- **/routes/api.php**: definición de rutas.
- **/database**: migraciones y seeders.
- **/public**: punto de acceso a la API.

## Buenas prácticas

- Uso de controladores separados por recursos.
- Seguridad mediante middleware de autenticación JWT.
- Código siguiendo los estándares de Laravel y PHP PSR.
- Documentación interna mediante comentarios claros.

## Mantenimiento

- Revisión periódica de logs.
- Backups semanales de la base de datos.
- Testing básico tras cada actualización.

