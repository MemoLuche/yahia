// Extrae los recursos de Links de las 4 páginas EN VIVO del sitio, con sus
// sub-categorías (grupos). Arma el CSV para el CPT "link_resource".
const fs = require('fs');
const path = require('path');

const pages = {
  'web-sites.html':           'websites',
  'videos.html':              'videos',
  'books-of-interest.html':   'books',
  'journals-of-interest.html':'journals',
};

function decode(s) {
  return s.replace(/&nbsp;/g, ' ').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>')
    .replace(/&quot;/g, '"').replace(/&#0?39;|&#x27;/g, "'").replace(/&#8217;|&#8216;/g, "'")
    .replace(/&#8211;/g, '–').replace(/&#8212;/g, '—').replace(/&#8220;|&#8221;/g, '"')
    .replace(/&#(\d+);/g, (_, n) => String.fromCharCode(+n));
}
function clean(h) { return decode(h.replace(/<[^>]+>/g, ' ')).replace(/\s+/g, ' ').trim(); }
function csv(v) { v = v == null ? '' : String(v); return /[",\n]/.test(v) ? '"' + v.replace(/"/g, '""') + '"' : v; }

const rows = [['post_title', 'link_type', 'link_group', 'link_url', 'menu_order']];
const counts = {};

for (const [file, type] of Object.entries(pages)) {
  let h = fs.readFileSync(path.join(__dirname, '..', '.scrape', file), 'utf8');
  const fi = h.search(/<footer/i);
  if (fi > 0) h = h.slice(0, fi);

  // Marcadores de grupo (<strong> o <span bold>) y items (<li> con <a href external>).
  const re = /<strong>([^<]{1,70})<\/strong>|<span[^>]*font-weight:\s*(?:bold|700)[^>]*>([^<]{1,70})<\/span>|<li[^>]*>[\s\S]*?<a\s+href="(https?:\/\/[^"]+)"[^>]*>([\s\S]*?)<\/a>/gi;
  let group = '';
  let n = 0;
  let m;
  while ((m = re.exec(h)) !== null) {
    if (m[1] || m[2]) {
      const g = clean(m[1] || m[2]);
      if (g && g.length > 1 && !/^videos$|^web sites$/i.test(g)) group = g;
      continue;
    }
    const url = decode(m[3]);
    const title = clean(m[4]);
    if (!title || url.includes('elhadiyahia.net')) continue; // saltar menú/interno
    n++;
    rows.push([title, type, group, url, n].map(csv));
  }
  counts[type] = n;
}

const out = path.join(__dirname, '..', 'import', 'links-import.csv');
fs.mkdirSync(path.dirname(out), { recursive: true });
fs.writeFileSync(out, rows.map(r => r.join(',')).join('\n') + '\n', 'utf8');
console.log('CSV:', out);
console.log('Por tipo:', counts, ' Total:', rows.length - 1);
