/* PÁGINA: gallery  ·  Pega ESTE archivo en la pestaña JavaScript (sin etiquetas <script>). */
// Tabs
  document.querySelectorAll('.tab-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
      document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
      document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('tab-'+btn.dataset.tab).classList.add('active');
    });
  });
  const hash=location.hash.replace('#','');
  const map={lab:'lab',foods:'foods',queretaro:'queretaro',mexico:'mexico',people:'people'};
  if(map[hash]) document.querySelector(`[data-tab="${map[hash]}"]`).click();

  // Lightbox
  function openLightbox(src){ document.getElementById('lightbox-img').src=src; document.getElementById('lightbox').classList.add('open'); document.body.style.overflow='hidden'; }
  function closeLightbox(){ document.getElementById('lightbox').classList.remove('open'); document.getElementById('lightbox-img').src=''; document.body.style.overflow=''; }
  document.getElementById('lightbox').addEventListener('click',e=>{ if(e.target===e.currentTarget) closeLightbox(); });
  document.addEventListener('keydown',e=>{ if(e.key==='Escape') closeLightbox(); });
