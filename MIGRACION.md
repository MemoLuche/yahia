# Migración del sitio nuevo (/dev/) a producción (raíz)

Objetivo: que el WordPress que hoy vive en `elhadiyahia.net/dev/` pase a ser el sitio
principal `elhadiyahia.net/`, **sin borrar el sitio viejo** (se archiva para poder revertir).

Método recomendado: **Duplicator** (plugin gratis) + respaldos con Backup Wizard / phpMyAdmin.

> ⏱️ Hazlo en un horario de poco tráfico. Calcula ~1 hora con calma.
> 🧯 Regla de oro: **no borramos nada del sitio viejo**; lo movemos a `viejo-backup/`.
> Si algo falla, se revierte moviéndolo de vuelta.

---

## Antes de empezar — apunta estos datos

- [ ] Usuario y contraseña de **wp-admin del /dev/** (los seguirás usando; son los que quedan en producción).
- [ ] Claves de **reCAPTCHA v3** (siguen sirviendo: son del mismo dominio `elhadiyahia.net`).
- [ ] Correo destino del formulario: **yahia@uaq.mx**.

---

## FASE 0 — Respaldos (el seguro). NO te la saltes.

1. **Backup completo del hosting** (sitio viejo):
   - cPanel → **Backup Wizard** → *Back Up* → *Full Backup* → destino "Home Directory" → generar.
   - Cuando termine, **descárgalo a tu computadora**. (O al menos: *Backup Wizard → Download a MySQL Database Backup* de la BD vieja + descargar un zip de `public_html`.)
2. **Exporta las dos bases de datos** por si acaso:
   - cPanel → **phpMyAdmin** → selecciona la BD del **sitio viejo** → pestaña *Exportar* → *Rápido* → SQL → descargar.
   - Repite con la BD del **/dev/**. *(Si no sabes cuál es cada una, la del /dev/ está en `dev/wp-config.php`, en `DB_NAME`.)*

✅ No sigas hasta tener estos archivos **descargados en tu compu**.

---

## FASE 1 — Empaquetar el /dev/ con Duplicator

3. Entra a **wp-admin del /dev/** (`elhadiyahia.net/dev/wp-admin`).
4. Plugins → Añadir nuevo → busca **"Duplicator"** (de Snap Creek) → Instalar → Activar.
5. Menú **Duplicator → Packages → Create New**.
   - Nombre: `mudanza-produccion`. Click **Next**.
   - Deja que corra el *Scan* (si sale algún aviso amarillo casi siempre se puede continuar). Click **Build**.
6. Al terminar, descarga los **dos archivos**:
   - **Installer** (`installer.php`)
   - **Archive** (`..._archive.zip`)

   Guárdalos en tu compu (los vas a subir a la raíz en la Fase 3).

---

## FASE 2 — Crear la base de datos nueva (para el sitio ya en producción)

> Creamos una BD nueva y vacía; así la BD vieja queda intacta como respaldo.

7. cPanel → **MySQL Database Wizard**:
   - Crea una base nueva, ej. `xxx_elhadi_prod`.
   - Crea un usuario nuevo con **contraseña fuerte** (guárdala).
   - Asígnale el usuario a la base con **ALL PRIVILEGES**.
   - **Apunta:** nombre completo de la BD, usuario completo y contraseña (cPanel les pone un prefijo).

---

## FASE 3 — Mover el sitio viejo a un lado y subir Duplicator

8. cPanel → **File Manager** → entra a `public_html` (la raíz del dominio).
   - Activa *Settings → Show Hidden Files* (para ver `.htaccess`).
9. Crea una carpeta llamada **`viejo-backup`**.
10. Selecciona **TODO lo del sitio viejo** (wp-admin, wp-includes, wp-content, wp-config.php, index.php, .htaccess, etc.)
    **⚠️ MENOS la carpeta `dev` y MENOS la carpeta `viejo-backup`** → **Move** → dentro de `viejo-backup/`.
    - Al terminar, la raíz debe quedar prácticamente con solo dos carpetas: `dev/` y `viejo-backup/`.
11. Sube a la **raíz** (`public_html`) los dos archivos de Duplicator: `installer.php` y el `..._archive.zip`.
    - Usa **Upload** del File Manager (aguanta archivos grandes mejor que el subidor de WordPress).

---

## FASE 4 — Instalar (aquí Duplicator arregla las URLs solo)

12. En el navegador abre: **`https://elhadiyahia.net/installer.php`**
13. **Paso 1:** acepta los términos; valida el archivo → **Next**.
14. **Conexión a la base de datos:**
    - Host: `localhost`
    - Database / User / Password: los de la **BD nueva** de la Fase 2.
    - **Test Database** → debe salir verde → **Next** → confirma que importe.
15. **URLs:** Duplicator mostrará
    - Old URL: `https://elhadiyahia.net/dev`
    - New URL: `https://elhadiyahia.net`
    Confírmalo (así reemplaza todas las rutas `/dev` por la raíz) → **Next**.
16. **Finish.** Te ofrecerá ir a wp-admin y **borrar los archivos del instalador**: hazlo (deja que limpie `installer.php` y temporales).

---

## FASE 5 — Verificar (checklist post-migración)

17. Entra a `https://elhadiyahia.net/wp-admin` (con tu usuario del /dev/).
18. **Ajustes → Enlaces permanentes → Guardar cambios** (regenera las URLs; evita 404 en /about/, /views/, etc.).
19. **Ajustes → Lectura →** desmarca *"Disuade a los motores de búsqueda…"* (para que Google SÍ indexe) y revisa que el **Título del sitio** (Ajustes → Generales) sea el correcto (no "My WordPress Blog").
20. Revisa **página por página**: Home, About, Publications, News, Views, Gallery, Links → que carguen imágenes y contenido.
21. **Formulario de contacto:** manda un mensaje de prueba. Verifica que llegue a **yahia@uaq.mx** (revisa también SPAM).
    - Si no llega: aplica la plantilla de CF7 que te pasé y, si aun así no llega, instala **WP Mail SMTP**.
22. Revisa el **candado https** y prueba en **celular**.
23. Limpia caché si tienes plugin de caché o CDN.

---

## 🧯 Cómo REVERTIR (si algo sale mal en cualquier punto)

1. File Manager → borra de la raíz lo que subió/extrajo Duplicator (installer y los wp-* nuevos).
2. Entra a `viejo-backup/`, selecciona **todo** y **Move** de regreso a `public_html`.
3. El sitio viejo usa su BD vieja (que **no tocamos**), así que vuelve a funcionar tal cual.
4. Además tienes el **Full Backup** de la Fase 0 como último recurso.

---

## Limpieza final (solo cuando estés 100% seguro, días después)

- [ ] Borrar la carpeta `dev/` (ya no se usa; era la copia de trabajo).
- [ ] Borrar `viejo-backup/` **solo si ya no la necesitas** (conserva el Full Backup descargado).
- [ ] (Opcional, SEO) Redirigir URLs viejas a las nuevas (ej. `/about-us/` → `/about/`) con un plugin de redirecciones, para no perder posicionamiento.

---

### Notas
- reCAPTCHA y el formulario siguen funcionando porque el dominio es el mismo.
- Si el **Build** de Duplicator o el `installer.php` se traba por tamaño/tiempo en hosting compartido, avísame y lo hacemos por partes (o cambiamos a método manual).
