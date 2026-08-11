// Genera un bloque AUTOCONTENIDO (HTML + CSS con scope .ey-about) con el equipo
// "horneado", para pegar en un widget HTML de Elementor en el About viejo (Agrikon).
// Versión ligera: sin hero verde, blende con el sitio viejo, sin depender del tema nuevo.
const fs = require('fs');
const path = require('path');

function parseCSV(s) {
  const rows = []; let row = [], cur = '', q = false;
  for (let i = 0; i < s.length; i++) {
    const c = s[i];
    if (q) { if (c === '"') { if (s[i + 1] === '"') { cur += '"'; i++; } else q = false; } else cur += c; }
    else { if (c === '"') q = true; else if (c === ',') { row.push(cur); cur = ''; } else if (c === '\n') { row.push(cur); rows.push(row); row = []; cur = ''; } else cur += c; }
  }
  if (cur !== '' || row.length) { row.push(cur); rows.push(row); }
  return rows;
}
function esc(s) {
  return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

const csv = fs.readFileSync(path.join(__dirname, '..', 'import', 'team-import.csv'), 'utf8').replace(/\r/g, '');
const rows = parseCSV(csv).filter(r => r.length > 1).slice(1);
// columnas: post_title, team_group, team_role, team_bio, team_tags, menu_order, featured_image
const people = rows.map(r => ({ name: r[0], group: r[1], role: r[2], bio: r[3], country: r[4], order: +r[5] || 0, img: r[6] }));
people.sort((a, b) => a.order - b.order);
const by = g => people.filter(p => p.group === g);
// Índice global estable (en orden de aparición) para abrir el modal de cada persona.
const ordered = [...by('lab'), ...by('collaborator'), ...by('past')];
ordered.forEach((p, i) => { p.idx = i; });

function card(p) {
  const photo = p.img
    ? `<img class="ey-photo" src="${esc(p.img)}" alt="${esc(p.name)}" loading="lazy" onerror="this.outerHTML='&lt;div class=\\'ey-fallback\\'&gt;&lt;i class=\\'fa-solid fa-user\\'&gt;&lt;/i&gt;&lt;/div&gt;'">`
    : `<div class="ey-fallback"><i class="fa-solid fa-user"></i></div>`;
  return `      <div class="ey-card" onclick="eyOpen(${p.idx})">
        <div class="ey-card-top">
          ${photo}
          <div>
            <div class="ey-name">${esc(p.name)}</div>
            ${p.role ? `<div class="ey-role">${esc(p.role)}</div>` : ''}
          </div>
        </div>
        ${p.bio ? `<div class="ey-card-body"><p class="ey-bio">${esc(p.bio)}</p></div>` : ''}
        ${p.country ? `<div class="ey-tags"><span class="ey-tag">${esc(p.country)}</span></div>` : ''}
      </div>`;
}
function pastItem(p) {
  const meta = [p.bio, p.country].filter(Boolean).join(' · ');
  const icon = p.img
    ? `<img class="ey-past-photo" src="${esc(p.img)}" alt="${esc(p.name)}" loading="lazy">`
    : `<div class="ey-past-icon"><i class="fa-solid fa-user"></i></div>`;
  return `      <div class="ey-past" onclick="eyOpen(${p.idx})">
        ${icon}
        <div>
          <div class="ey-name">${esc(p.name)}</div>
          ${meta ? `<div class="ey-meta">${esc(meta)}</div>` : ''}
        </div>
      </div>`;
}

const css = `
.ey-about{font-family:'Inter',-apple-system,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#374151;max-width:1140px;margin:0 auto;padding:8px 14px;}
.ey-about *{box-sizing:border-box;}
.ey-about h2{font-family:'Poppins',sans-serif;}
.ey-section{padding:26px 0;}
.ey-divider{display:flex;align-items:center;gap:14px;margin-bottom:26px;}
.ey-divider h2{font-size:1.35rem;font-weight:700;color:#1f2937;white-space:nowrap;margin:0;}
.ey-divider::after{content:'';flex:1;height:1px;background:#e5e7eb;}
.ey-pill{display:inline-flex;align-items:center;gap:6px;background:#1e8c4e;color:#fff;padding:4px 12px;border-radius:6px;font-size:.72rem;font-weight:700;white-space:nowrap;text-transform:uppercase;letter-spacing:.04em;}
.ey-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;}
.ey-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);}
.ey-card-top{padding:22px 22px 16px;display:flex;gap:14px;align-items:flex-start;border-bottom:1px solid #f1f3f5;}
.ey-photo{width:72px;height:72px;border-radius:50%;object-fit:cover;object-position:top;flex-shrink:0;border:3px solid #e6f4ec;}
.ey-fallback{width:72px;height:72px;border-radius:50%;background:#e6f4ec;display:flex;align-items:center;justify-content:center;color:#1e8c4e;font-size:1.7rem;flex-shrink:0;}
.ey-name{font-family:'Poppins',sans-serif;font-weight:700;color:#1f2937;font-size:.98rem;line-height:1.3;}
.ey-role{display:inline-block;font-size:.66rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:#1e8c4e;background:#e6f4ec;padding:2px 9px;border-radius:6px;margin-top:6px;}
.ey-card-body{padding:16px 22px;}
.ey-bio{font-size:.85rem;color:#6b7280;line-height:1.6;margin:0;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden;}
.ey-tags{padding:10px 22px;background:#fafbfb;border-top:1px solid #f1f3f5;display:flex;flex-wrap:wrap;gap:6px;}
.ey-tag{font-size:.72rem;font-weight:600;color:#1e8c4e;background:#e6f4ec;padding:3px 9px;border-radius:6px;}
.ey-past-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(235px,1fr));gap:10px;}
.ey-past{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px 14px;display:flex;gap:11px;align-items:center;}
.ey-past-icon{width:36px;height:36px;border-radius:50%;background:#e6f4ec;color:#1e8c4e;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.8rem;}
.ey-past-photo{width:36px;height:36px;border-radius:50%;object-fit:cover;object-position:top;flex-shrink:0;}
.ey-past .ey-name{font-size:.85rem;}
.ey-meta{font-size:.7rem;color:#6b7280;margin-top:2px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.ey-card,.ey-past{cursor:pointer;transition:transform .15s,box-shadow .15s,border-color .15s;}
.ey-card:hover{transform:translateY(-3px);box-shadow:0 10px 26px rgba(0,0,0,.09);}
.ey-past:hover{border-color:#1e8c4e;box-shadow:0 4px 14px rgba(0,0,0,.06);}
.ey-modal{display:none;position:fixed;inset:0;background:rgba(15,30,20,.55);z-index:99999;padding:18px;overflow-y:auto;}
.ey-modal.open{display:block;}
.ey-modal-box{background:#fff;border-radius:16px;max-width:640px;width:100%;margin:36px auto;padding:32px;position:relative;box-shadow:0 24px 70px rgba(0,0,0,.35);font-family:'Inter',-apple-system,'Segoe UI',Roboto,sans-serif;}
.ey-modal-x{position:absolute;top:12px;right:16px;background:none;border:none;font-size:1.7rem;line-height:1;color:#9ca3af;cursor:pointer;}
.ey-modal-x:hover{color:#374151;}
.ey-modal-head{display:flex;gap:18px;align-items:center;margin-bottom:18px;padding-right:24px;}
.ey-modal-photo{width:96px;height:96px;border-radius:50%;object-fit:cover;object-position:top;border:4px solid #e6f4ec;flex-shrink:0;}
.ey-modal-fallback{width:96px;height:96px;border-radius:50%;background:#e6f4ec;display:flex;align-items:center;justify-content:center;color:#1e8c4e;font-size:2.4rem;flex-shrink:0;}
.ey-modal-name{font-family:'Poppins',sans-serif;font-size:1.35rem;font-weight:700;color:#1f2937;margin:0 0 8px;}
.ey-modal-country{font-size:.78rem;color:#6b7280;margin-top:8px;}
.ey-modal-bio{font-size:.92rem;color:#374151;line-height:1.75;white-space:pre-line;}
@media(max-width:600px){.ey-grid{grid-template-columns:1fr;}.ey-modal-box{padding:24px;}.ey-modal-head{flex-direction:column;text-align:center;}}`;

const section = (pill, icon, title, inner) => `  <section class="ey-section">
    <div class="ey-divider"><span class="ey-pill"><i class="fa-solid ${icon}"></i> ${pill}</span><h2>${title}</h2></div>
${inner}
  </section>`;

// Datos para el modal (JSON embebido) + markup + JS.
const modalData = JSON.stringify(ordered.map(p => ({ n: p.name, r: p.role, c: p.country, i: p.img, b: p.bio }))).replace(/</g, '\\u003c');
const modal = `
  <!-- Modal de detalle (popup) -->
  <div class="ey-modal" id="eyModal">
    <div class="ey-modal-box">
      <button class="ey-modal-x" type="button" aria-label="Cerrar">&times;</button>
      <div class="ey-modal-head">
        <span id="eyMPhoto"></span>
        <div>
          <h3 class="ey-modal-name" id="eyMName"></h3>
          <span class="ey-role" id="eyMRole"></span>
          <div class="ey-modal-country" id="eyMCountry"></div>
        </div>
      </div>
      <div class="ey-modal-bio" id="eyMBio"></div>
    </div>
  </div>
  <script type="application/json" id="eyData">${modalData}</script>
  <script>
  (function(){
    var modal=document.getElementById('eyModal');
    // Mover el modal al <body> para que position:fixed funcione respecto a la
    // pantalla (Elementor suele poner transform en secciones, lo que rompe el fixed).
    if (modal && modal.parentNode !== document.body) document.body.appendChild(modal);
    var data=JSON.parse(document.getElementById('eyData').textContent);
    window.eyOpen=function(i){
      var p=data[i]; if(!p) return;
      document.getElementById('eyMPhoto').innerHTML = p.i ? '<img class="ey-modal-photo" src="'+p.i+'" alt="">' : '<div class="ey-modal-fallback"><i class="fa-solid fa-user"></i></div>';
      document.getElementById('eyMName').textContent = p.n||'';
      var role=document.getElementById('eyMRole'); role.textContent=p.r||''; role.style.display=p.r?'inline-block':'none';
      document.getElementById('eyMCountry').textContent = p.c||'';
      document.getElementById('eyMBio').textContent = p.b||'';
      modal.classList.add('open'); document.body.style.overflow='hidden';
    };
    function eyClose(){ modal.classList.remove('open'); document.body.style.overflow=''; }
    modal.addEventListener('click', function(e){ if(e.target===modal) eyClose(); });
    modal.querySelector('.ey-modal-x').addEventListener('click', eyClose);
    document.addEventListener('keydown', function(e){ if(e.key==='Escape') eyClose(); });
  })();
  </script>`;

const html = `<!-- ============================================================
  ABOUT (versión ligera) para el sitio VIEJO (Agrikon).
  Pegar en un widget "HTML" de Elementor. Es autocontenido y no
  toca el tema. Arreglo temporal hasta migrar al tema nuevo.
  Generado con scripts/build-old-about.js
============================================================ -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="ey-about">
<style>${css}
</style>

${section('Lab Team', 'fa-flask', 'Our Laboratory', `    <div class="ey-grid">
${by('lab').map(card).join('\n')}
    </div>`)}

${section('Collaborators', 'fa-handshake', 'Research Collaborators', `    <div class="ey-grid">
${by('collaborator').map(card).join('\n')}
    </div>`)}

${section('Previous', 'fa-clock-rotate-left', 'Previous Team Members &amp; Collaborators', `    <div class="ey-past-grid">
${by('past').map(pastItem).join('\n')}
    </div>`)}
${modal}
</div>
`;

const out = path.join(__dirname, '..', 'paste-en-wordpress', 'about-viejo.html');
fs.mkdirSync(path.dirname(out), { recursive: true });
fs.writeFileSync(out, html, 'utf8');
console.log('Generado:', out, '(' + html.length + ' bytes)');
console.log('Lab:', by('lab').length, ' Collaborators:', by('collaborator').length, ' Previous:', by('past').length);
