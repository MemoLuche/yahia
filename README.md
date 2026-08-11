# Sitio web — Dr. Elhadi Yahia · Phytochemicals & Nutrition Laboratory

Rediseño del sitio académico del **Dr. Elhadi Yahia** (Food Science & Postharvest
Technology, UAQ) convertido en un **tema clásico a medida de WordPress**, con todo
el contenido **dinámico y editable** por no-programadores.

- **Sitio viejo (producción):** `https://elhadiyahia.net/` — tema comercial *Agrikon* + Elementor.
- **Sitio nuevo (staging):** `https://elhadiyahia.net/dev/` — instalación WordPress aparte con el tema `tema-elhadi`.
- **Estado:** completo y funcional en staging; pendiente el visto bueno del Dr. y la migración a producción.

---

## 1. Estructura del repositorio

| Carpeta / archivo | Qué es |
|---|---|
| `tema-elhadi/` | **El tema de WordPress** (lo que se despliega). Fuente de verdad del sitio nuevo. |
| `elhadi-theme.zip` | El tema empaquetado, listo para subir a WordPress. |
| `rediseno/` | Maquetación **estática original** (HTML+CSS) del rediseño. Base del diseño; ya no es lo que corre en vivo. |
| `scripts/` | Scripts de Node para extraer datos del sitio viejo y generar los CSV / bloques. |
| `import/` | **CSV listos para importar** al sitio nuevo (Ultimate CSV Importer). |
| `paste-en-wordpress/` | Bloques HTML: el puente del About viejo (`about-viejo.html`) y páginas estáticas (método antiguo). |
| `backup/` | Respaldos de archivos antes de cambios reversibles (ej. navbar). |
| `.scrape/` | HTML descargado del sitio viejo (archivos de trabajo; se pueden borrar). |

---

## 2. El tema (`tema-elhadi/`)

Tema **clásico** (no bloques/FSE). Archivos:

| Archivo | Rol |
|---|---|
| `style.css` | Cabecera del tema (lo que WordPress reconoce). Los estilos reales están en `assets/styles-v2.css`. |
| `functions.php` | Registra los CPT, campos ACF, taxonomías, menú por defecto, encola CSS/JS y helpers. |
| `header.php` / `footer.php` | Navbar + `<head>` / footer, compartidos por todas las páginas. |
| `front-page.php` | **Home** (portada). Se usa automáticamente. |
| `page-about.php` | Página **About** (slug `about`) — equipo dinámico. |
| `page-news.php` | Página **News** (slug `news`) — Posts nativos. |
| `page-publications.php` | Página **Publications** (slug `publications`). |
| `page-views.php` | Página **Views** (slug `views`). |
| `page-links.php` | Página **Links** (slug `links`). |
| `page-gallery.php` | Página **Gallery** (slug `gallery`). |
| `single.php` | Vista individual de News / Views / Publications. |
| `single-team_member.php` | Perfil individual de un miembro del equipo. |
| `page.php` / `index.php` | Plantillas de respaldo. |
| `assets/styles-v2.css` | **Todo el CSS** del sitio. |
| `assets/main.js` | JS global (menú móvil, scroll suave). |
| `assets/img/` | Logos (`logo-dark.png` = navbar blanco, `logo-white.png` = navbar verde). |

> **WordPress usa `page-{slug}.php` automáticamente** para la Página cuyo slug coincide.
> Por eso cada sección necesita una **Página** publicada con el slug exacto:
> `about`, `news`, `publications`, `views`, `links`, `gallery`.

---

## 3. Contenido dinámico (cómo se edita cada sección)

Todo se administra desde el panel de WordPress. **Requiere el plugin ACF** (Advanced
Custom Fields, gratis) para los campos de Views/Publications/Team/Links.

| Sección | Tipo de contenido | Campos / taxonomía | Cómo se agrega |
|---|---|---|---|
| **News** | Posts nativos | Categoría `General` | Entradas → Añadir nueva |
| **Views** | CPT `view` | ACF: `view_type` (opinion/commentary/editorial/interview), `view_author`, `view_readtime` | Views → Añadir nueva |
| **Publications** | CPT `publication` | Taxonomía `pub_type` (books/chapters/articles/technical/abstracts) + ACF: `pub_authors`, `pub_year`, `pub_source`, `pub_link`, `pub_note` | Publications → Añadir nueva |
| **About (equipo)** | CPT `team_member` | ACF: `team_group` (lab/collaborator/past), `team_role`, `team_bio`, `team_tags` | Equipo → Añadir nuevo |
| **Links** | CPT `link_resource` | ACF: `link_type` (websites/videos/books/journals), `link_group`, `link_url`, `link_desc`, `link_source`, `link_meta`, `link_icon` | Links (recursos) → Añadir nuevo |
| **Gallery** | CPT `gallery_item` | Taxonomía `gallery_cat` (lab/foods/queretaro/mexico/people). Foto = imagen destacada, título = leyenda | Gallery → Añadir foto |

**Detalles útiles:**
- **Foto** de un miembro/foto/video = **imagen destacada** del post.
- **Orden** dentro de una sección = campo **"Orden"** (Atributos de página); menor = primero.
- El **Home** muestra números y fotos **reales** automáticamente: conteos de publicaciones (`elhadi_pub_count`), 3 fotos del hero (categoría *Foods*) y 7 del preview (aleatorias de la galería).
- **Perfiles del equipo:** una tarjeta es clickeable (abre `/team/nombre/`) solo si tiene foto, bio o contenido.

---

## 4. Plugins necesarios (sitio nuevo)

| Plugin | Para qué |
|---|---|
| **Advanced Custom Fields** (free) | Campos de Views / Publications / Team / Links. |
| **Ultimate CSV Importer** (free) | Importaciones masivas (los CSV de `import/`). |
| **Contact Form 7** | Formulario de contacto del Home (id `82d1c3d`, envía a `yahia@uaq.mx`) + reCAPTCHA v3. |
| *(Media Sync)* | Se usó una vez para registrar en la Biblioteca los `uploads` copiados por FTP. |

---

## 5. Despliegue / cómo subir cambios

El tema ya está **instalado** en el staging, así que los cambios se suben **por archivo**
con el **Administrador de archivos del cPanel** (o FTP) a
`.../dev/wp-content/themes/tema-elhadi/`, sobrescribiendo el archivo modificado.

- **Primera instalación** (o tema nuevo): subir `elhadi-theme.zip` por *Apariencia → Temas → Subir*.
- **Regenerar el zip** (script de PowerShell, Windows):
  ```powershell
  Add-Type -AssemblyName System.IO.Compression.FileSystem
  $root = (Get-Location).Path; $src = Join-Path $root "tema-elhadi"; $zip = Join-Path $root "elhadi-theme.zip"
  if (Test-Path $zip) { Remove-Item $zip -Force }
  $z = [System.IO.Compression.ZipFile]::Open($zip,'Create')
  Get-ChildItem -Recurse -File $src | % { $rel = $_.FullName.Substring($root.Length+1) -replace '\\','/'; [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($z,$_.FullName,$rel,'Optimal') | Out-Null }
  $z.Dispose()
  ```
  > ⚠️ **NO usar `Compress-Archive` ni el "Comprimir en ZIP" del Explorador**: guardan las
  > rutas con `\` y WordPress dice *"El tema no tiene style.css"*. Hay que usar rutas `/`
  > (como el snippet de arriba).
- **Tras registrar un CPT/taxonomía nuevo**: ir a **Ajustes → Enlaces permanentes → Guardar**
  (refresca las reglas de URL; si no, las URLs `/view/`, `/team/`, etc. dan 404).
- Al editar CSS: recargar con **Ctrl + F5** (salta el caché).

---

## 6. Scripts (`scripts/`) — extracción y generación

Todos son Node (`node scripts/xxx.js`). Leen de `.scrape/` (HTML/JSON descargados del
sitio viejo) y escriben en `import/` o `paste-en-wordpress/`.

| Script | Genera | Notas |
|---|---|---|
| `scrape-team.js` | `import/team-import.csv` (44) | Equipo desde `about-us` (3 grupos + fotos). |
| `scrape-pubs.js` | `import/publications-import.csv` (461) | Chapters/Articles/Technical/Abstracts. |
| `scrape-links.js` | `import/links-import.csv` (710) | 4 páginas vivas, con sub-grupos. |
| `scrape-gallery.js` | `import/gallery-import.csv` (417) | 5 páginas de galería por categoría. |
| `build-news-csv.js` | `import/news-import.csv` (8) | Desde la **API REST** del sitio viejo (`/wp-json/wp/v2/posts`). |
| `build-old-about.js` | `paste-en-wordpress/about-viejo.html` | Bloque del About para el sitio **viejo**. |
| `wp-extract.js` | `paste-en-wordpress/<slug>/` | (Método antiguo) trocea `rediseno/*.html`. |
| `validate-csv.js` | — | Valida columnas de un CSV. |

---

## 7. Importaciones (`import/`)

Se importan con **Ultimate CSV Importer** como el CPT correspondiente. Reglas clave:

- **`team_group` y `link_type` van como Custom Field** (NO taxonomía).
- **`pub_type` y `gallery_cat` van como Taxonomía**.
- Mapear siempre `menu_order → Order`, `featured_image → Featured Image`, `post_title → Title`.
- Con muchos registros, usar **Adaptive processing = 10** (evita timeouts).
- ⚠️ **No importar dos veces** el mismo CSV (duplica). Borrar antes si hace falta.

| CSV | Importar como | Filas |
|---|---|---|
| `publications-import.csv` | `publication` | 461 |
| `team-import.csv` | `team_member` | 44 |
| `links-import.csv` | `link_resource` | 710 |
| `gallery-import.csv` | `gallery_item` | 417 |
| `news-import.csv` | Posts (Entradas) | 8 |

---

## 8. Puente temporal — About del sitio VIEJO

Mientras se migra, el About del sitio viejo (Agrikon + Elementor) se actualizó con un
**bloque autocontenido** (`paste-en-wordpress/about-viejo.html`) pegado en un **widget
HTML de Elementor**. Es **estático** (equipo horneado, con modal de detalle por persona).
Si cambia el equipo, re-correr `node scripts/build-old-about.js` y volver a pegar.

---

## 9. Notas y "gotchas"

- **Imágenes:** el tema usa `wp_get_upload_dir()` (uploads de `/dev/`). Varias imágenes de
  contenido apuntan por URL a los `uploads` del sitio viejo (raíz del dominio) y cargan bien.
- **Barra de admin de WP:** hay CSS (`body.admin-bar #navbar{top:32px}`) para que, estando
  logeado, la barra de admin no tape el navbar fijo.
- **Navbar:** actualmente **blanco** (v3) con logo oscuro. El respaldo del navbar verde
  original está en `backup/` (restaurar `styles-v2.css` y `header.php` desde ahí para revertir).
- **Formulario de contacto:** Contact Form 7 + reCAPTCHA v3. Si los correos no llegan, el
  hosting probablemente no manda `mail()` → instalar **WP Mail SMTP**.

---

## 10. Pendientes antes de producción

- [ ] Visto bueno del Dr.
- [ ] **Título del sitio** (Ajustes → Generales; quitar "My WordPress Blog").
- [ ] **noindex** del staging (Ajustes → Lectura) mientras no sea producción.
- [ ] Revisar responsive (celular) en las páginas nuevas.
- [ ] **Migrar `/dev/` a producción** (reemplazar el sitio viejo por el tema nuevo).
- [ ] Confirmar envío de correos del formulario (SMTP si hace falta).

---

*Desarrollo: Guillermo A. Gordillo Pizarro — Servicio Social, UAQ.*
