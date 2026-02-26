# Sistema de Gestión de Pagos

Este es un sistema administrativo integral para el control de cobros mensuales y gestión de clientes, desarrollado con **Laravel** y **Filament**.

## 🚀 Funcionalidades Principales
- **Control de Clientes:** Gestión de perfiles con estados de pago dinámicos.
- **Registro de Pagos:** Sistema con validación para evitar cobros duplicados en el mismo periodo.
- **Relaciones Integradas:** Historial de pagos accesible directamente desde el perfil del cliente.
- **Dashboard de Negocio:** Visualización de métricas financieras y tendencias de ingresos.
- **Cobranza Automatizada:** Integración para envío de recordatorios de pago personalizados.

## 🛠️ Stack Tecnológico
- **Backend:** Laravel 12
- **Panel Administrativo:** Filament v5
- **Base de Datos:** MySQL
- **Frontend:** Tailwind CSS

## 💻 Instalación
1. Clonar el repositorio.
2. Ejecutar `composer install` y `npm install`.
3. Configurar el archivo `.env` con las credenciales de base de datos.
4. Ejecutar `php artisan migrate`.
5. Crear un usuario administrativo con `php artisan make:filament-user`.
