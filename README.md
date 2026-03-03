# 💰 Control de Pagos - ICEA

Sistema integral de gestión administrativa y control de cobros desarrollado para **ICEA** (Impuestos y Contabilidad E&A). Esta herramienta optimiza el seguimiento de carteras de clientes, automatiza la cobranza y ofrece una visión clara del flujo de caja.


## 🚀 Funcionalidades 

* **🚦 Semáforo de Pagos:** Identificación visual instantánea (`✅ Pagado` / `⏳ Pendiente`) en la lista de clientes para detectar moras al instante.
* **📂 Historial Integrado:** Visualización y registro de abonos directamente desde el perfil de cada cliente (*Relation Manager*).
* **🛡️ Validación Anti-Duplicados:** Protección inteligente que impide registrar dos veces el mismo mes y año para un cliente, evitando errores contables.
* **💬 Cobranza One-Click:** Botón dinámico de WhatsApp que genera recordatorios personalizados con el nombre del cliente, el mes adeudado y el monto exacto en negritas.
* **📊 Dashboard Estratégico:** Resumen de recaudación mensual y gráficos de tendencia de ingresos para una toma de decisiones informada.

## 🛠️ Stack Tecnológico

* **Framework:** Laravel 12
* **Panel Administrativo:** Filament v5 (TALL Stack)
* **Lenguaje:** PHP 8.3
* **Base de Datos:** MySQL
* **Estilos:** Tailwind CSS



## 🚀 Instalación y Configuración

1.  **Clonar el repositorio:**
    ```bash
    git clone [https://github.com/tu-usuario/control-pagos.git](https://github.com/tu-usuario/control-pagos.git)
    ```
2.  **Instalar dependencias:**
    ```bash
    composer install
    npm install && npm run dev
    ```
3.  **Configurar el entorno:**
    - Copiar el archivo `.env.example` a `.env`.
    - Configurar las credenciales de tu base de datos local.
    - Ejecutar `php artisan key:generate`.
4.  **Ejecutar migraciones:**
    ```bash
    php artisan migrate
    ```
5.  **Crear acceso administrativo:**
    ```bash
    php artisan make:filament-user
    ```

---
Desarrollado con pasión por **Sarai Gutiérrez** | **Eleos Studio** 🇸🇻
