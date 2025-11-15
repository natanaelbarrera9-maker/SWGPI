# Topicos (esqueleto Laravel)

Esta carpeta `Topicos` contiene plantillas e instrucciones para iniciar la migración del proyecto SWGPI a Laravel.

Archivos incluidos:
- `.env.example` - plantilla de configuración de entorno.
- `.env` - ejemplo prellenado con valores tomados de `Servidor/config/config.ini` (DB host/usuario/base de datos).

Instrucciones rápidas:

1. Instalar Laravel dentro de esta carpeta (desde la raíz `Topicos`):

```sh
# desde la carpeta Topicos
composer create-project laravel/laravel .
composer install
cp .env.example .env   # o mantener el .env generado aquí
php artisan key:generate
```

2. Ajustar en `.env` la contraseña `DB_PASSWORD` si corresponde.

3. Importar la estructura de la base de datos o convertir `DB/scaffold_projects.sql` en migraciones de Laravel.

4. Mover vistas y assets desde `Cliente/` a `resources/views` y `public/` respectivamente.

Notas:
- El `.env` incluido es una plantilla útil para empezar; no lo subas al repositorio si contiene credenciales reales.
- Si quieres que yo ejecute la creación del proyecto Laravel aquí (ejecutando `composer`), confírmamelo y lo intento desde el entorno.
