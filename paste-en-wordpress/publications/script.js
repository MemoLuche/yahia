/* PÁGINA: publications  ·  Pega ESTE archivo en la pestaña JavaScript (sin etiquetas <script>). */
// Tabs
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
    });
  });

  // Hash routing
  const hash = location.hash.replace('#','');
  const map = {books:'books',chapters:'chapters',articles:'articles',technical:'technical',abstracts:'abstracts'};
  if (map[hash]) document.querySelector(`[data-tab="${map[hash]}"]`).click();

  // Filter chips (cosmetic)
  document.querySelectorAll('.filter-chips').forEach(g => {
    g.querySelectorAll('.chip').forEach(c => c.addEventListener('click', () => { g.querySelectorAll('.chip').forEach(x => x.classList.remove('active')); c.classList.add('active'); }));
  });
