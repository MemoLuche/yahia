// Extrae las publicaciones (de los <li>) de las 4 páginas del sitio viejo
// y arma un CSV listo para importar al CPT "publication".
const fs = require('fs');
const path = require('path');

const SRC = path.join(__dirname, '..', '.scrape');
const OUT = path.join(__dirname, '..', 'import', 'publications-import.csv');

// El valor de pub_type es el NOMBRE del término (como aparece en WP), para que el
// importador lo empareje con el término existente y no cree duplicados.
const pages = {
  'book-chapters.html':     'Book Chapters',
  'refereed-articles.html': 'Refereed Articles',
  'technical-articles.html':'Technical Articles',
  'abstracs.html':          'Abstracts',
};

// Decodifica entidades HTML comunes + numéricas.
function decode(s) {
  return s
    .replace(/&nbsp;/g, ' ')
    .replace(/&amp;/g, '&')
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>')
    .replace(/&quot;/g, '"')
    .replace(/&#0?39;|&#x27;|&apos;/g, "'")
    .replace(/&#8217;|&#8216;/g, "'")
    .replace(/&#8220;|&#8221;/g, '"')
    .replace(/&#8211;/g, '–')
    .replace(/&#8212;/g, '—')
    .replace(/&#(\d+);/g, (_, n) => String.fromCharCode(parseInt(n, 10)))
    .replace(/&#x([0-9a-f]+);/gi, (_, n) => String.fromCharCode(parseInt(n, 16)));
}

function stripTags(html) {
  return decode(html.replace(/<[^>]+>/g, ' ')).replace(/\s+/g, ' ').trim();
}

function csvField(v) {
  v = v == null ? '' : String(v);
  if (/[",\n]/.test(v)) return '"' + v.replace(/"/g, '""') + '"';
  return v;
}

const rows = [['post_title', 'pub_authors', 'pub_year', 'pub_source', 'pub_link', 'pub_note', 'pub_type']];
const counts = {};

for (const [file, type] of Object.entries(pages)) {
  const html = fs.readFileSync(path.join(SRC, file), 'utf8');
  // Las publicaciones están en listas ORDENADAS <ol> (los menús/footer usan <ul>).
  // Tomamos TODAS las <ol> (algunas páginas las parten, ej. <ol> + <ol start="5">).
  const ols = html.match(/<ol[^>]*>[\s\S]*?<\/ol>/gi) || [];
  const lis = [];
  for (const ol of ols) {
    for (const li of ol.match(/<li[^>]*>[\s\S]*?<\/li>/gi) || []) lis.push(li);
  }
  let n = 0;
  for (const li of lis) {
    const text = stripTags(li);
    if (text.length < 20) continue; // saltar vacíos
    // Enlace: primer href; si no, alguna URL suelta en el texto.
    let link = '';
    const href = li.match(/href\s*=\s*["']([^"']+)["']/i);
    if (href) {
      link = decode(href[1]);
    } else {
      const url = text.match(/https?:\/\/\S+/);
      if (url) link = url[0].replace(/[.,)]+$/, '');
    }
    const year = (text.match(/\b(19|20)\d{2}\b/) || [''])[0];
    rows.push([text, '', year, '', link, '', type].map(csvField));
    n++;
  }
  counts[type] = n;
}

fs.mkdirSync(path.dirname(OUT), { recursive: true });
fs.writeFileSync(OUT, rows.map((r) => r.join(',')).join('\n') + '\n', 'utf8');
console.log('CSV:', OUT);
console.log('Por tipo:', counts, ' Total:', rows.length - 1);
