# Líder V y G — Sistema Web (2 usuarios)

## 1) ¿Qué incluye?
- Clientes (con los campos: Nombre, Dirección, Lugar, Administrador, Observación, Unidad Inmobiliaria, Tipo Comprobante, Nombre Factura, RUC)
- Catálogo de productos con imágenes (código, descripción, precio, precio proveedor, proveedor)
- Cotizaciones en PDF con logo
  - Numeración: **MM + 3 dígitos** (ej: **12001**)
  - **Editable solo si está Pendiente**
  - Columna **Observaciones por producto** en **rojo**
  - Los precios ingresados son **finales**. Si está **afecto a IGV**, el sistema **desglosa** IGV (18%).
- Guía en PDF con logo
  - Se genera al **aprobar** la cotización
  - Muestra **solo cantidades** (sin precios)
  - Incluye **Observaciones por producto** en **rojo**
  - Al generarse, la cotización queda **bloqueada**
- Reporte SUNAT (vista + exportación Excel) con los campos que se pueden obtener
- Roles: **Admin** y **Operador**
  - Solo **Admin** puede aprobar/generar guía

## 2) Requisitos del servidor
- PHP 8.2+
- Composer
- MySQL/MariaDB
- (Recomendado) Node.js + npm (para compilar el login de Breeze)

## 3) Instalación rápida (servidor remoto)
1. Descomprime este ZIP en tu servidor.
2. Entra a la carpeta del ZIP y ejecuta:
   ```bash
   chmod +x installer/setup.sh
   ./installer/setup.sh lider-vyg
   ```
3. Entra al proyecto:
   ```bash
   cd lider-vyg
   ```
4. Configura tu BD en `.env`:
   - `DB_DATABASE=...`
   - `DB_USERNAME=...`
   - `DB_PASSWORD=...`
5. Ejecuta migraciones y seed:
   ```bash
   php artisan migrate --seed
   ```
6. Levanta el sistema:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

## 4) Usuarios por defecto
- Admin: `admin@lidervyg.pe` / `Admin123!`
- Operador: `operador@lidervyg.pe` / `Operador123!`

> Cambia las contraseñas luego de instalar.

## 5) Forma de uso (operación diaria)
### A) Registrar clientes
Menú **Clientes** → **Nuevo** → Guardar

### B) Registrar productos con imagen
Menú **Catálogo** → **Nuevo** → subir imagen → Guardar

### C) Crear cotización
Menú **Cotizaciones** → **Nueva**
- Selecciona cliente
- Elige si está **Afecto IGV**
- Agrega ítems (precio unitario es **precio final**)
- Por cada producto puedes escribir **Observaciones** (saldrá en rojo)
- Guardar

### D) Aprobar y generar guía (solo Admin)
En la cotización → botón **Aprobar / Generar Guía**
- Se crea la guía con el **mismo número**
- La cotización cambia a **Aprobada** (ya no se puede editar)

### E) Imprimir PDFs
- Cotización: botón **PDF**
- Guía: botón **PDF**

### F) Reporte SUNAT
Menú **Reporte SUNAT**
- Selecciona mes
- **Exportar Excel**

## 6) Notas de IGV (importante)
- El precio guardado/ingresado se considera **con IGV incluido**.
- Si la cotización está marcada como **Afecto IGV**, el sistema calcula:
  - Subtotal = Total / 1.18
  - IGV = Total - Subtotal

---

Si deseas que el sistema tenga además **cuentas por cobrar / estados de deuda**, o integración con facturación electrónica (serie/numero), se puede ampliar.
