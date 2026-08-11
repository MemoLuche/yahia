// Extrae de cada página estática el <style> + contenido del <body> (sin navbar/footer)
// + el <script> propio de la página, y reescribe las rutas a formato WordPress.
// Genera, por página, 3 archivos separados (una pestaña cada uno) listos para el
// bloque de código con pestañas HTML / CSS / JavaScript de WordPress:
//   paste-en-wordpress/<slug>/content.html  -> pestaña HTML
//   paste-en-wordpress/<slug>/styles.css    -> pestaña CSS
//   paste-en-wordpress/<slug>/script.js      -> pestaña JavaScript (solo si la página tiene JS)
const fs = require('fs');
const path = require('path');

const SRC = path.join(__dirname, '..', 'rediseno');
const OUT = path.join(__dirname, '..', 'paste-en-wordpress');
fs.mkdirSync(OUT, { recursive: true });

// Páginas a procesar: archivo de origen -> slug de WordPress
const pages = {
  'about.html': 'about',
  'links.html': 'links',
  'views.html': 'views',
  'gallery.html': 'gallery',
  'publications.html': 'publications',
  'news.html': 'news',
};

// Reescritura de rutas (orden importa: específicas antes que genéricas).
function rewrite(s) {
  const reps = [
    ['../elhadiyahia.net/wp-content/uploads/', '/wp-content/uploads/'],
    ['../elhadiyahia.net/views/index.html', '/views/'],
    ['../elhadiyahia.net/news/index.html', '/news/'],
    ['books.1.html', '/publications/#books'],
    ['../elhadiyahia.net/', '/'],
    ['index.html#contact', '/#contact'],
    ['href="index.html"', 'href="/"'],
    ["href='index.html'", "href='/'"],
    ['about.html', '/about/'],
    ['publications.html#', '/publications/#'],
    ['publications.html', '/publications/'],
    ['news.html', '/news/'],
    ['views.html', '/views/'],
    ['gallery.html', '/gallery/'],
    ['links.html', '/links/'],
  ];
  for (const [a, b] of reps) s = s.split(a).join(b);
  // Limpieza final: permalinks estilo WP para artículos individuales (futuras
  // entradas de News/Views): /algo/index.html -> /algo/
  s = s.split('/index.html').join('/');
  return s;
}

for (const [file, slug] of Object.entries(pages)) {
  const html = fs.readFileSync(path.join(SRC, file), 'utf8');

  // 1) Bloque <style> del <head> (sin las etiquetas <style>)
  const styleMatch = html.match(/<style>([\s\S]*?)<\/style>/);
  const style = styleMatch ? styleMatch[1].trim() : '';

  // 2) Contenido entre el cierre del nav móvil y el <footer>
  const afterMobile = html.split('<nav id="mobile-nav">')[1];
  const afterClose = afterMobile.substring(afterMobile.indexOf('</nav>') + '</nav>'.length);
  let content = afterClose.split(/<footer/)[0].trim();

  // 3) <script> propio de la página (antes de </body>), quitando el toggle del
  //    hamburguesa (ya vive en el main.js del tema) para no duplicar el handler.
  const scriptMatch = html.match(/<script>([\s\S]*?)<\/script>\s*<\/body>/);
  let pageScript = '';
  if (scriptMatch) {
    let js = scriptMatch[1];
    // a) quitar el handler del hamburguesa en su forma multilínea (function(){...})
    js = js.replace(
      /document\.getElementById\(['"]hamburger['"]\)\.addEventListener\(\s*['"]click['"]\s*,\s*function\s*\(\)\s*\{[\s\S]*?\}\s*\)\s*;?/g,
      ''
    );
    // b) y en su forma de una línea (flecha): quita cualquier línea que lo mencione
    js = js
      .split('\n')
      .filter((l) => !l.includes('hamburger'))
      .join('\n')
      .trim();
    if (js) pageScript = js;
  }

  // 4) Reescribir rutas en todo.
  const styleOut = rewrite(style);
  const contentOut = rewrite(content);
  const scriptOut = rewrite(pageScript);

  // 5) Escribir 3 archivos separados (uno por pestaña) en su carpeta.
  const dir = path.join(OUT, slug);
  fs.mkdirSync(dir, { recursive: true });

  const htmlHeader =
    `<!-- PÁGINA: ${slug}  ·  Pega ESTE archivo en la pestaña HTML.\n` +
    `     El CSS va en styles.css (pestaña CSS) y el JS en script.js (pestaña JavaScript). -->\n`;
  fs.writeFileSync(path.join(dir, 'content.html'), htmlHeader + contentOut + '\n', 'utf8');

  const cssHeader = `/* PÁGINA: ${slug}  ·  Pega ESTE archivo en la pestaña CSS (sin etiquetas <style>). */\n`;
  fs.writeFileSync(path.join(dir, 'styles.css'), cssHeader + styleOut + '\n', 'utf8');

  let hasJs = false;
  if (scriptOut) {
    hasJs = true;
    const jsHeader = `/* PÁGINA: ${slug}  ·  Pega ESTE archivo en la pestaña JavaScript (sin etiquetas <script>). */\n`;
    fs.writeFileSync(path.join(dir, 'script.js'), jsHeader + scriptOut + '\n', 'utf8');
  }

  console.log(`OK ${slug}/  -> content.html, styles.css${hasJs ? ', script.js' : ' (sin JS)'}`);
}
