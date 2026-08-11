/* PÁGINA: links  ·  Pega ESTE archivo en la pestaña JavaScript (sin etiquetas <script>). */
document.querySelectorAll('.tab-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
      document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
      document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('tab-'+btn.dataset.tab).classList.add('active');
    });
  });
  const hash=location.hash.replace('#','');
  const m={websites:'websites',videos:'videos',books:'books',journals:'journals'};
  if(m[hash]) document.querySelector(`[data-tab="${m[hash]}"]`).click();
