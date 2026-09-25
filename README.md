# Tienda en línea — Mi Empresa

E-commerce en PHP 8.3 + MySQL + Bootstrap + Composer (OpenPay, PHPMailer, phpdotenv).

## Estructura

```
opt/
├── public/                 ← DocumentRoot del vhost (UNICA carpeta expuesta al navegador)
│   ├── index.php           Tienda (antes: index.php)
│   ├── cart.php            Carrito (antes: carrito-de-compras.php)
│   ├── checkout.php        Checkout/Pedido (antes: pedido.php)
│   ├── payment.php         Pago OpenPay (antes: pago.php)
│   ├── order.php           Confirmación de orden (antes: orden.php — no existía)
│   ├── product.php         Página de producto (PLACEHOLDER — pendiente real)
│   ├── login.php           Login administrador
│   ├── logout.php          Cierre de sesión
│   ├── admin/              Panel administrador
│   │   ├── users.php       (antes: usuarios.php)
│   │   ├── approved-orders.php (antes: compras-aprobadas.php)
│   │   └── store-upload.php    (antes: carga-tienda-en-linea.php)
│   ├── actions/            Bridges HTTP → app/controllers/ (cart, coupon, products, orders, users, payments)
│   └── assets/             css/ js/ images/ uploads/products/ (uploads con .htaccess que bloquea PHP)
├── app/
│   ├── config/             config.php (leerá .env) + database.php (antes: dbcon.php)
│   ├── controllers/        Lógica POST/fetch (antes: code*.php, get_cart_products.php)
│   ├── includes/           bootstrap.php, session.php, helpers.php, mailer.php
│   └── screens/            layout/ (navbar, footer) + panel/ (sidenav)
├── sql/
│   ├── ecommerce.sql       Base de datos original
│   └── migracion-segunda-fase.sql  Migración de rutas de medios (NO automática)
├── .env                    Credenciales (NO versionar)
├── .env.example            Plantilla versionada
└── vendor/                 Composer
```

## Requisitos
- PHP 8.3+, mysqli, extensiones GD, intl, fileinfo
- Composer
- Apache con módulo rewrite + AllowOverride (para el vhost)

## Instalación local (XAMPP)

1. Clonar el repositorio.
2. Instalar dependencias:
   ```bash
   composer install
   ```
3. Configurar entorno:
   ```bash
   copy .env.example .env
   ```
   Completar valores reales en `.env` (nunca subir ese archivo).
4. Importar la base de datos:
   - Abrir phpMyAdmin → crear la BD → Importar `sql/ecommerce.sql`.
   - Ajustar `DB_*` en `.env` a los datos locales.
5. En `.env` dejar las URLs para acceso sin vhost:
   - `BASE_URL=http://localhost/opt`
   - `STORE_URL=http://localhost/opt`
   - `OPENPAY_REDIRECT_URL=http://localhost/opt/order.php`
6. Abrir `http://localhost/opt` (tienda) y `http://localhost/opt/login.php` (panel admin).

> Sin vhost: `opt/.htaccess` bloquea `.env*`, `app/`, `vendor/`, `sql/`, `composer.*`, `README.md`
> y reescribe todo lo demás hacia `public/` (`RewriteBase /opt/`). Requiere `mod_rewrite` y
> `AllowOverride All` sobre htdocs.
>
> En un hosting real: el DocumentRoot debe apuntar a `public/` (entonces `BASE_URL`
> sería la raíz del dominio, sin `public/`); el `.htaccess` raíz de tu proyecto local NO
> se usa en ese caso.

## Módulos de pago / correo
- OpenPay en modo sandbox (`OPENPAY_PRODUCTION_MODE=false`).
- PHPMailer centralizado en `app/includes/mailer.php` con `nuevoCorreo()`
  (SMTP admin para pedidos/usuarios; SMTP noreply para notificaciones al cliente).

## URLs después de la FASE 2
| Antes            | Ahora                        |
|------------------|------------------------------|
| index.php        | /index.php                   |
| carrito-de-compras.php | /cart.php              |
| pedido.php       | /checkout.php                |
| pago.php         | /payment.php                 |
| codepago.php     | /actions/payments.php        |
| codeenvio.php    | /actions/orders.php          |
| codeusuarios.php | /actions/users.php           |
| codeproductosventa.php | /actions/products.php   |
| get_cart_products.php | /actions/cart.php        |
| validar_cupon.php | /actions/coupon.php         |
| usuarios.php     | /admin/users.php             |
| compras-aprobadas.php | /admin/approved-orders.php|
| carga-tienda-en-linea.php | /admin/store-upload.php|
| orden.php (inexistente) | /order.php              |