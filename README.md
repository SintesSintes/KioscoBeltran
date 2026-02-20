Kiosco Beltrán — Sistema de Gestión

Sistema web de gestión de ventas para kioscos desarrollado en PHP y MySQL. Permite administrar productos, registrar ventas, controlar stock y generar reportes de manera simple, rápida y visualmente moderna.

Descripción

Kiosco Beltrán es una aplicación web diseñada para pequeños comercios que necesitan un sistema liviano y funcional para controlar su negocio. El sistema automatiza procesos clave como ventas, actualización de stock y registro de movimientos, reduciendo errores manuales y mejorando la organización.

Características principales

Sistema de autenticación de usuarios

CRUD completo de productos

Registro de ventas con carrito

Historial detallado de ventas

Cálculo automático de total diario

Reportes PDF por rango de fechas

Control automático de inventario

Interfaz moderna con diseño glass UI

Tecnologías utilizadas

PHP (mysqli)

MySQL

Bootstrap 5

JavaScript

FPDF (generación de reportes PDF)

Requisitos

Apache (XAMPP recomendado)

PHP 7.4 o superior

MySQL

Navegador web moderno

Instalación

Copiar la carpeta del proyecto dentro de:

htdocs

Iniciar Apache y MySQL desde el panel de XAMPP

Abrir en el navegador:

http://localhost/KioscoBeltran

El sistema crea automáticamente la base de datos y tablas necesarias al ejecutarse por primera vez.

Estructura básica del proyecto
KioscoBeltran/
│
├── conexion.php
├── index.php
├── login.php
├── registro.php
├── listar.php
├── vender.php
├── ventas.php
├── terminarVenta.php
├── pdfVentas.php
├── css/
└── img/
Seguridad

Contraseñas cifradas con password_hash

Consultas preparadas para evitar SQL Injection

Validaciones básicas de formularios

Control de sesiones

Autor

Sistema desarrollado como proyecto de gestión comercial para entorno local.

Licencia

Proyecto de uso libre para fines educativos y comerciales personales.
