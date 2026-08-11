// Arma el CSV de News a partir de la API REST del sitio viejo (news-api.json).
// Columnas para importar como Posts nativos de WordPress.
const fs = require('fs');
const path = require('path');

const posts = JSON.parse(fs.readFileSync(path.join(__dirname, '..', '.scrape', 'news-api.json'), 'utf8'));
const BASE = 'https://elhadiyahia.net';

function decode(s) {
  return String(s || '')
    .replace(/&nbsp;/g, ' ').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>')
    .replace(/&quot;/g, '"').replace(/&#0?39;|&#x27;/g, "'").replace(/&#8217;|&#8216;/g, "'")
    .replace(/&#8211;/g, '–').replace(/&#8212;/g, '—').replace(/&#8220;|&#8221;/g, '"')
    .replace(/&hellip;/g, '…').replace(/&#(\d+);/g, (_, n) => String.fromCharCode(+n));
}
function stripText(html) { return decode(String(html || '').replace(/<[^>]+>/g, ' ')).replace(/\s+/g, ' ').replace(/\[…\]|\[…\]/g, '').trim(); }
function abs(u) { if (!u) return ''; return /^https?:/i.test(u) ? u : BASE + u; }
function csv(v) { v = v == null ? '' : String(v); return /[",\n]/.test(v) ? '"' + v.replace(/"/g, '""') + '"' : v; }

const rows = [['post_title', 'post_content', 'post_excerpt', 'post_date', 'featured_image', 'post_category']];

for (const p of posts) {
  const title = decode((p.title && p.title.rendered) || '').replace(/<[^>]+>/g, '').trim();
  const content = (p.content && p.content.rendered || '').trim();
  const excerpt = stripText(p.excerpt && p.excerpt.rendered);
  const date = (p.date || '').slice(0, 19).replace('T', ' ');

  // Imagen destacada: la de la API; si no hay, la 1ra imagen del contenido (tamaño original).
  let img = '';
  try { img = p._embedded['wp:featuredmedia'][0].source_url || ''; } catch (e) { /* none */ }
  if (!img) {
    const m = content.match(/(?:https?:\/\/[^"'\s>]+)?\/wp-content\/uploads\/[^"'\s>]+\.(?:jpg|jpeg|png|webp)/i);
    if (m) img = abs(m[0]).replace(/-\d+x\d+(\.\w+)$/, '$1');
  }

  rows.push([title, content, excerpt, date, img, 'General'].map(csv));
}

const out = path.join(__dirname, '..', 'import', 'news-import.csv');
fs.mkdirSync(path.dirname(out), { recursive: true });
fs.writeFileSync(out, rows.map((r) => r.join(',')).join('\n') + '\n', 'utf8');
console.log('CSV:', out, ' posts:', rows.length - 1);
rows.slice(1).forEach((r) => console.log('  •', r[0].slice(0, 46).padEnd(47), '| fecha', r[3].slice(0, 10), '| img:', r[4] ? 'sí' : 'NO'));
