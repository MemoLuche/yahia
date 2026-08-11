// Extrae las fotos de las 5 páginas de galería del sitio viejo y arma un CSV
// para importar al CPT "gallery_item" (featured image + categoría).
const fs = require('fs');
const path = require('path');

const cats = {
  'our-laboratory': 'Laboratory',
  'foods':          'Foods',
  'queretaro':      'Querétaro',
  'mexico':         'Mexico',
  'people':         'People',
};
const BASE = 'https://elhadiyahia.net';
const re = /\/wp-content\/uploads\/[A-Za-z0-9_\/.\-]+\.(?:jpg|jpeg|png|webp)/gi;

function csv(v) { v = v == null ? '' : String(v); return /[",\n]/.test(v) ? '"' + v.replace(/"/g, '""') + '"' : v; }

const rows = [['post_title', 'gallery_cat', 'featured_image', 'menu_order']];
const counts = {};

for (const [pg, cat] of Object.entries(cats)) {
  let h = fs.readFileSync(path.join(__dirname, '..', '.scrape', 'gal-' + pg + '.html'), 'utf8');
  const fi = h.search(/<footer/i);
  if (fi > 0) h = h.slice(0, fi);
  const all = [...h.matchAll(re)].map((m) => m[0]);
  // Quedarse con el original (sin sufijo -WxH), sin logos/decoración.
  const orig = [...new Set(all.map((u) => u.replace(/-\d+x\d+(\.\w+)$/, '$1')))]
    .filter((u) => !/favicon|logo|cropped|footer-bg|page-header/i.test(u));
  let n = 0;
  for (const u of orig) {
    n++;
    rows.push(['', cat, BASE + u, n].map(csv));
  }
  counts[cat] = n;
}

const out = path.join(__dirname, '..', 'import', 'gallery-import.csv');
fs.mkdirSync(path.dirname(out), { recursive: true });
fs.writeFileSync(out, rows.map((r) => r.join(',')).join('\n') + '\n', 'utf8');
console.log('CSV:', out);
console.log('Por categoría:', counts, ' Total:', rows.length - 1);
