/* PÁGINA: news  ·  Pega ESTE archivo en la pestaña JavaScript (sin etiquetas <script>). */
function filterNews(el, cat) {
    document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active')); el.classList.add('active');
    document.querySelectorAll('.news-item').forEach(item => {
      item.style.display = (cat === 'all' || item.dataset.cat === cat) ? 'flex' : 'none';
    });
  }
