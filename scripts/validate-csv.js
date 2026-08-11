const fs = require('fs');
const text = fs.readFileSync(process.argv[2] || 'import/publications-import.csv', 'utf8').replace(/\r/g, '');

// Parser CSV que respeta comillas y saltos de línea dentro de campos.
function parseCSV(s) {
  const rows = [];
  let row = [], cur = '', q = false;
  for (let i = 0; i < s.length; i++) {
    const c = s[i];
    if (q) {
      if (c === '"') { if (s[i + 1] === '"') { cur += '"'; i++; } else q = false; }
      else cur += c;
    } else {
      if (c === '"') q = true;
      else if (c === ',') { row.push(cur); cur = ''; }
      else if (c === '\n') { row.push(cur); rows.push(row); row = []; cur = ''; }
      else cur += c;
    }
  }
  if (cur !== '' || row.length) { row.push(cur); rows.push(row); }
  return rows;
}

const rows = parseCSV(text).filter(r => r.length > 1);
const data = rows.slice(1);
const byType = {}, noLink = {};
let bad = 0;
for (const f of data) {
  if (f.length !== 7) bad++;
  const t = f[6] || '?';
  byType[t] = (byType[t] || 0) + 1;
  if (!f[4]) noLink[t] = (noLink[t] || 0) + 1;
}
console.log('Filas de datos:', data.length);
console.log('Por tipo:', byType);
console.log('Sin enlace por tipo:', noLink);
console.log('Filas con != 7 columnas:', bad);
console.log('\n— Ejemplo fila 1 —');
console.log('title :', data[0][0].slice(0, 120));
console.log('year  :', data[0][2]);
console.log('link  :', data[0][4]);
console.log('type  :', data[0][6]);
console.log('\n— Ejemplo technical (con start=5) —');
const tech = data.find(r => r[6] === 'technical');
console.log('title :', tech[0].slice(0, 120));
console.log('link  :', tech[4]);
