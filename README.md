# Panda Biker · SISPANDA, sistema de gestión para una tienda de repuestos de moto

Aplicación web en PHP y MySQL para gestionar el día a día de una tienda de repuestos de motocicletas: inventario, compras a proveedores, ventas, proveedores, usuarios e informes en PDF. Funciona en local con **XAMPP** (Apache + PHP + MySQL/MariaDB).

## Funcionalidades

| Módulo | Qué permite |
|---|---|
| Acceso (`index.html` → `rol/validar.php`) | Inicio de sesión con usuario y contraseña de la tabla `usuarios`. Si los datos no son correctos, muestra `index2.html` con el aviso. |
| Panel (`index.php`) | Plantilla AdminLTE con menú lateral. Los módulos se cargan dentro del panel sin recargar la página. |
| Inventario | Alta, edición y borrado de productos (código, descripción, marca, precio de compra, precio de venta y stock). |
| Compras | Compras a un proveedor con número de factura y varias líneas de producto. Un **trigger** suma el stock y actualiza el coste. Si el código no existía, crea el producto con un PVP sugerido del +30 %. |
| Ventas | Venta de uno o varios productos con forma de pago (efectivo, tarjeta, pago móvil, transferencia…). **Antes de guardar comprueba que hay stock** de todos los artículos, y un trigger descuenta las existencias. |
| Proveedores | Alta, edición y borrado (empresa, contacto, teléfono, dirección, RIF y estatus activo o inactivo). |
| Usuarios | Alta, edición y borrado de usuarios con rol 1 (administrador) o 2 (empleado). |
| Informes PDF (FPDF) | Inventario, compras y ventas. El de ventas pregunta el periodo y admite `?fecha=AAAA-MM-DD`, `?desde=…&hasta=…` o `?todo=1`. |

## Tecnologías

- PHP (probado con **PHP 8.2.12** de XAMPP; el volcado SQL original se generó con PHP 7.3), extensión `mysqli`.
- MySQL o MariaDB (probado con **MariaDB 10.4.32** de XAMPP). La base usa tablas InnoDB y tres **triggers**.
- HTML, CSS y JavaScript con AdminLTE 3, Bootstrap, jQuery, SweetAlert2 y Font Awesome (incluidos en `vistas/assets/`), además de Bootstrap 5 y DataTables cargados desde CDN.
- [FPDF](http://www.fpdf.org/) 1.8 en `reporte/fpdf/` para los PDF.

## Requisitos

- Windows con **XAMPP** (Apache, PHP 8.x y MySQL/MariaDB).
- Un navegador actual.
- Internet solo para Bootstrap 5, DataTables y la fuente Source Sans Pro (ver *Uso sin conexión*).

## Estructura

```
pandabiker/
├── index.html            pantalla de acceso (punto de entrada)
├── index2.html           acceso con el aviso de usuario o contraseña incorrectos
├── index.php             panel principal (carga controladores/ y vistas/plantilla.php)
├── home.php              portada del panel
├── config.example.php    datos de conexión de ejemplo (XAMPP); se versiona
├── db_config.php         lee config.local.php si existe; si no, config.example.php
├── panda.sql             estructura de la base de datos, triggers y usuarios de prueba
├── rol/                  validar.php (inicio de sesión), logout.php
├── inventario/ compras/ ventas/ proveedores/ usuarios/
│                         crud*.php (listado y formulario), insertar.php, actualizar.php, update.php, delete.php, conexion.php
├── reporte/              informes PDF (inventario.php, compras.php, ventas.php) y la librería fpdf/
├── php/                  db.php y scripts JSON auxiliares (dashboard_totals.php…)
├── vistas/               plantilla del panel, módulos de diseño y recursos (assets/)
├── controladores/        plantilla.controlador.php
└── imagenes/             imágenes del panel
```

## Instalación paso a paso (XAMPP)

1. **Instala XAMPP** y abre el *XAMPP Control Panel*.
2. **Arranca Apache y MySQL** con sus botones *Start*.
3. **Copia la carpeta del proyecto a `C:\xampp\htdocs\` con el nombre exacto `pandabiker`**, de modo que quede `C:\xampp\htdocs\pandabiker\index.html`. El nombre es obligatorio: varias redirecciones del código usan la ruta absoluta `/pandabiker/...`.
   ```powershell
   cd C:\xampp\htdocs
   git clone <url-del-repositorio> pandabiker
   ```
4. **Abre phpMyAdmin** en <http://localhost/phpmyadmin>.
5. **Crea la base de datos `panda`**: *Nueva* → nombre `panda` → cotejamiento `utf8mb4_general_ci` → *Crear*. También puedes hacerlo con SQL:
   ```sql
   CREATE DATABASE panda CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```
6. **Importa `panda.sql`**: selecciona la base `panda` → pestaña *Importar* → elige `C:\xampp\htdocs\pandabiker\panda.sql` → *Importar*. Se crean 6 tablas (`usuarios`, `inventario`, `ventas`, `proveedores`, `compras`, `detalle_compras`) y 3 triggers. El script no crea la base de datos: por eso hace falta el paso 5. Desde la consola:
   ```powershell
   C:\xampp\mysql\bin\mysql.exe -u root panda < C:\xampp\htdocs\pandabiker\panda.sql
   ```
7. **Configura la conexión.** Con un XAMPP recién instalado (usuario `root` sin contraseña, base `panda`, puerto 3306) **no tienes que hacer nada**: se usa `config.example.php`. Si tu MySQL tiene otra contraseña, otro puerto u otra base:
   ```powershell
   Copy-Item config.example.php config.local.php
   notepad config.local.php
   ```
   `config.local.php` no se sube a Git. Todos los archivos de conexión (`*/conexion.php`, `php/db.php`, `reporte/cn.php` y `rol/validar.php`) leen sus datos de `db_config.php`.
8. **Abre el sistema** en <http://localhost/pandabiker/>.

## Cuentas de demostración

`panda.sql` crea dos usuarios **de prueba**:

| Usuario | Contraseña | Rol |
|---|---|---|
| `admin` | `12345` | 1 (administrador) |
| `empleado` | `12345` | 2 (empleado) |

Se comprobó que ambos entran en el panel. **Son cuentas de demostración, con contraseñas débiles y guardadas en texto plano: no las uses en un sistema real.** Cámbialas o bórralas desde el módulo Usuarios. Ahora mismo los dos roles tienen el mismo acceso.

La base se crea **sin productos, proveedores ni compras**: puedes registrarlos desde el propio sistema.

## Uso sin conexión a internet

El sistema funciona sin internet porque PHP, MySQL, AdminLTE, jQuery, SweetAlert2, Font Awesome y FPDF están en local. Sin conexión se pierde:

- Bootstrap 5 (CDN de jsDelivr), que usan las páginas de inventario, compras, ventas, proveedores y usuarios: los formularios se ven con menos estilo, pero funcionan.
- DataTables (CDN): las tablas se muestran sin búsqueda ni paginación.
- La fuente Source Sans Pro: se usa la del sistema.

Para trabajar siempre sin conexión, descarga esos archivos a `vistas/assets/plugins/` y cambia sus enlaces `https://cdn…` por rutas locales.

## Solución de problemas

| Síntoma | Causa y solución |
|---|---|
| `Not Found` o páginas en blanco al guardar | La carpeta no se llama `pandabiker` o no está directamente en `htdocs`. |
| `Unknown database 'panda'` o `Access denied for user 'root'` | No se creó la base (paso 5), o tu MySQL tiene contraseña: crea `config.local.php` (paso 7). |
| Error al importar: `Table ... already exists` | La base ya tenía tablas. Importa en una base vacía (por ejemplo, crea `panda2` y ponla en `config.local.php`). |
| `#1419` al importar los triggers | Tu usuario MySQL no tiene permiso para crear triggers: importa como `root` o activa `log_bin_trust_function_creators`. |
| No puedo entrar | Comprueba que `panda.sql` se importó (tabla `usuarios`) y usa las cuentas de demostración. |
| El PDF no se abre | Revisa que existe `reporte/fpdf/fpdf.php` y que Apache está arrancado. |
| Apache no arranca (puerto 80 ocupado) | Cambia el puerto en *Config › httpd.conf* (`Listen 8080`) y abre `http://localhost:8080/pandabiker/`. |

## Limitaciones conocidas (revisar antes de usarlo en producción)

Se detectaron en la auditoría y **no se han corregido**, porque cambiarían la lógica del sistema:

1. **Sin control de sesión en las páginas internas.** `index.php`, los módulos CRUD (incluida la lista de usuarios con sus contraseñas) y los informes PDF se abren **sin haber iniciado sesión**.
2. **Inyección SQL.** Las consultas se construyen concatenando lo que escribe el usuario (por ejemplo, en `rol/validar.php` y en todos los `insertar.php` y `update.php`). Hay que pasarlas a consultas preparadas (`mysqli_prepare`).
3. **Contraseñas en texto plano** en la tabla `usuarios`. Deberían guardarse con `password_hash()` y comprobarse con `password_verify()`.
4. **Los roles no restringen nada**: administrador y empleado ven lo mismo.
5. **Falta `css/style.css`**: las páginas CRUD lo enlazan, pero el archivo no existe (error 404 sin efecto visible importante).
6. `rol/index.html` es un resto de la plantilla de acceso original: envía campos con otros nombres y no se usa. El acceso real es el `index.html` de la raíz.
7. La configuración por defecto (`root` sin contraseña) solo es válida para una instalación local de XAMPP.

## Personalizar

| Quiero cambiar… | Dónde |
|---|---|
| Datos de conexión | `config.local.php` (copia de `config.example.php`) |
| Logo, icono y fondo del acceso | `vistas/assets/dist/img/` (`panda_biker_logo.jpeg`, `panda_icono_web.png`, `login_panda.jpg`) |
| Imagen de la portada del panel | `imagenes/` y `home.php` |
| Menú lateral | `vistas/modulos/layout/sidebar_lateral.php` |
| Colores y estilos del panel | `vistas/assets/dist/css/index.css` |
| Margen del producto creado por una compra (30 %) | Trigger `trigger_aumentar_stock_compra` en `panda.sql` |
| Formato de los informes | `reporte/inventario.php`, `reporte/compras.php`, `reporte/ventas.php` |

## Comprobaciones realizadas

En una base MariaDB 10.4.32 aparte (sin tocar la base de XAMPP existente) con PHP 8.2.12:

- `panda.sql` se importa sin errores (6 tablas y 3 triggers).
- El acceso funciona con `admin` y `empleado`, y se rechaza una contraseña incorrecta.
- Cargan los cinco módulos (inventario, compras, ventas, proveedores y usuarios) y el resumen `php/dashboard_totals.php`.
- Los tres informes devuelven un PDF válido.
- Alta de un producto con 8 unidades y venta de 3: el trigger deja el stock en 5. Una venta de 99 unidades se rechaza por falta de stock.
- `php -l` sin errores en los archivos de conexión modificados.
