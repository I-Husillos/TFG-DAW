# TFG-DAW - Proyecto Fin de Grado

Este repositorio contiene el código fuente para el Proyecto de Fin de Grado del ciclo formativo de Desarrollo de Aplicaciones Web (DAW).

## Descripción

El proyecto es una aplicación web desarrollada con el framework Laravel, utilizando Docker para la gestión del entorno de desarrollo.

## Stack Tecnológico

*   **Backend:** PHP / Laravel
*   **Frontend:** Blade, CSS, JavaScript
*   **Base de Datos:** MySQL
*   **Servidor Web:** Apache
*   **Contenerización:** Docker & Docker Compose

## Requisitos Previos

*   [Docker](https://www.docker.com/get-started)
*   [Docker Compose](https://docs.docker.com/compose/install/)

## Instalación y Puesta en Marcha

1.  **Clonar el repositorio:**
    ```bash
    git clone <URL-del-repositorio>
    cd TFG-DAW
    ```

2.  **Levantar los contenedores:**
    El proyecto utiliza Docker para gestionar el entorno. Ejecuta el siguiente comando para construir y levantar los contenedores en segundo plano:
    ```bash
    docker compose up -d --build
    ```

3.  **Instalar dependencias de PHP:**
    Accede al contenedor de la aplicación (`app`) y ejecuta Composer para instalar las dependencias de Laravel.
    ```bash
    docker compose run --rm service-composer install
    ```

4.  **Configurar el archivo de entorno:**
    Copia el archivo de ejemplo `.env.example` que se encuentra en `src/` y genera la clave de la aplicación.
    ```bash
    docker compose exec service-php cp .env.example .env
    docker compose exec service-php php artisan key:generate
    ```
    O puedes simplemente copiar el archivo mediante interfaz con click derecho si no quieres utilizar comandos.
    *Nota: Asegúrate de configurar las variables de entorno en `src/.env` si necesitas credenciales específicas para la base de datos u otros servicios.*

### Configuración del archivo `.env` de Laravel

Después de copiar `src/.env.example` a `src/.env`, deberás ajustar algunas variables para que coincidan con la configuración de Docker:

**Configuración de Base de Datos (MySQL):**

*   `DB_CONNECTION=mysql`
*   `DB_HOST=service-mysql` (Este es el nombre del servicio MySQL en `docker-compose.yml`)
*   `DB_PORT=3306`
*   `DB_DATABASE=baseDatosMysql`
*   `DB_USERNAME=user`
*   `DB_PASSWORD=1234`

**Configuración de Cache/Queue (Redis):**

*   `REDIS_HOST=service-redis` (Este es el nombre del servicio Redis en `docker-compose.yml`)
*   `REDIS_PASSWORD=null` (Si no has configurado una contraseña para Redis)
*   `REDIS_PORT=6379`

**Configuración de Correo Electrónico (Mailpit):**

*   `MAIL_MAILER=smtp`
*   `MAIL_HOST=service-mailpit` (Este es el nombre del servicio Mailpit en `docker-compose.yml`)
*   `MAIL_PORT=1025`
*   `MAIL_USERNAME=null`
*   `MAIL_PASSWORD=null`
*   `MAIL_ENCRYPTION=null`
*   `MAIL_FROM_ADDRESS="hello@example.com"`
*   `MAIL_FROM_NAME="${APP_NAME}"`

Asegúrate de que los valores de `DOCKER_MYSQL_DATABASE`, `DOCKER_MYSQL_USER`, y `DOCKER_MYSQL_PASSWORD` en tu archivo `.env` principal (el del directorio raíz del proyecto) coincidan con los que uses en el `src/.env` de Laravel.

5.  **Ejecutar las migraciones:**
    Para crear la estructura inicial de la base de datos.
    ```bash
    docker compose exec service-php php artisan migrate
    ```

Una vez completados estos pasos, la aplicación debería estar accesible en `http://localhost` (o el puerto que hayas configurado en `docker-compose.yml`).

## Estructura del Proyecto

-   `.docker/`: Contiene los Dockerfiles y configuraciones para los servicios (Apache, PHP-FPM, Supervisor).
-   `src/`: Contiene el código fuente completo de la aplicación Laravel.
-   `docker-compose.yml`: Archivo principal que orquesta el levantamiento de los contenedores de desarrollo.
-   `.env`: Archivo de configuración principal de docker-compose.
