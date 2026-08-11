// Extrae el equipo (3 grupos) de la página vieja about-us, con la BIO COMPLETA
// de cada persona (todo el texto entre su <h2> y el siguiente). Arma el CSV
// para importar al CPT "team_member".
const fs = require('fs');
const path = require('path');

let h = fs.readFileSync(path.join(__dirname, '..', '.scrape', 'about-us.html'), 'utf8');
const fi = h.search(/<footer/i);
if (fi > 0) h = h.slice(0, fi);

function decode(s) {
  return s
    .replace(/&nbsp;/g, ' ').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>')
    .replace(/&quot;/g, '"').replace(/&#0?39;|&#x27;|&apos;/g, "'")
    .replace(/&#8217;|&#8216;/g, "'").replace(/&#8220;|&#8221;/g, '"')
    .replace(/&#8211;/g, '–').replace(/&#8212;/g, '—')
    .replace(/&#(\d+);/g, (_, n) => String.fromCharCode(+n))
    .replace(/&#x([0-9a-f]+);/gi, (_, n) => String.fromCharCode(parseInt(n, 16)));
}
function clean(html) {
  let s = decode(html.replace(/<[^>]+>/g, ' ')).replace(/\s+/g, ' ').trim();
  // Arregla palabras partidas por <span> (p.ej. "Univ ersity" -> "University").
  s = s.replace(/\bUniv ersity\b/g, 'University').replace(/\bQuerétar o\b/g, 'Querétaro');
  return s;
}
function csv(v) { v = v == null ? '' : String(v); return /[",\n]/.test(v) ? '"' + v.replace(/"/g, '""') + '"' : v; }

// <h2> con posición y texto.
const h2 = [...h.matchAll(/<h2[^>]*>([\s\S]*?)<\/h2>/gi)].map(m => ({ i: m.index, end: m.index + m[0].length, text: clean(m[1]) }));

const sections = {
  'Current Laboratory Team': 'lab',
  'Current Collaborators': 'collaborator',
  'Previous Team Members and Collaborators': 'past',
};
const roleDefault = { lab: '', collaborator: 'Collaborator', past: 'Previous Member' };
// Roles específicos del equipo actual (para que el re-import deje buenos cargos).
const roleByName = {
  'Elhadi M. Yahia, Ph.D.': 'Emeritus Professor · Laboratory Leader',
  'Dr. Francisco Lujan Méndez': 'Professor & Researcher',
  'Antonio Rocha González': 'Research Student',
  'Guillermo A. Gordillo Pizarro': 'Web & Digital',
};

let group = null;
const order = { lab: 0, collaborator: 0, past: 0 };
const rows = [['post_title', 'team_group', 'team_role', 'team_bio', 'team_tags', 'menu_order', 'featured_image']];

for (let k = 0; k < h2.length; k++) {
  const cur = h2[k];
  if (sections[cur.text]) { group = sections[cur.text]; continue; }
  if (!group) continue;
  const m = cur.text.match(/^(.*?)\s*\(([^)]+)\)\s*$/); // "Nombre (País)"
  if (!m) { group = null; continue; }                    // p.ej. "Links" -> fin del equipo
  const name = m[1].trim();
  const country = m[2].trim();

  // Bio = todo el texto entre este <h2> y el siguiente <h2>.
  const stop = h2[k + 1] ? h2[k + 1].i : cur.end + 4000;
  let bio = clean(h.slice(cur.end, stop));
  if (bio.length > 2800) bio = bio.slice(0, 2800).replace(/\s+\S*$/, '') + '…';

  // Foto: primer <img> de uploads en el bloque de la persona (tamaño completo).
  const block = h.slice( cur.i, stop );
  const imgs = [...block.matchAll(/<img[^>]+src="([^"]+)"/gi)]
    .map(m => m[1])
    .filter(u => /uploads/.test(u) && !/logo|icon/i.test(u));
  let img = imgs.length ? imgs[0] : '';
  img = img.replace(/-\d+x\d+(\.\w+)$/, '$1'); // quita el sufijo -150x150 -> original

  const role = roleByName[name] || roleDefault[group];
  order[group]++;
  rows.push([name, group, role, bio, country, String(order[group]), img].map(csv));
}

const out = path.join(__dirname, '..', 'import', 'team-import.csv');
fs.mkdirSync(path.dirname(out), { recursive: true });
fs.writeFileSync(out, rows.map(r => r.join(',')).join('\n') + '\n', 'utf8');

const counts = {};
let withBio = 0;
rows.slice(1).forEach(r => { counts[r[1]] = (counts[r[1]] || 0) + 1; if (r[3] && r[3].length > 2) withBio++; });
console.log('CSV:', out);
console.log('Por grupo:', counts, ' Total:', rows.length - 1, ' Con bio:', withBio);
