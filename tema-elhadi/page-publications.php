<?php
/**
 * Plantilla dinámica de la página "Publications" (slug: publications).
 * Las publicaciones salen del CPT "publication", agrupadas por el tipo
 * (taxonomía pub_type: books / chapters / articles / technical / abstracts).
 * Un colaborador agrega una en: Publications → Añadir nueva
 * (Título, Tipo [barra derecha], y los campos Autores/Año/Fuente/Enlace/Etiqueta).
 */
get_header();

$types = elhadi_pub_types();

// Una consulta por tipo (para contar y para pintar).
$data = array();
foreach ( $types as $slug => $cfg ) {
	$data[ $slug ] = new WP_Query(
		array(
			'post_type'      => 'publication',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			// Orden por año (campo pub_year) descendente: lo más nuevo arriba.
			// El OR incluye también las que no tienen año (quedan al final).
			'meta_query'     => array(
				'relation' => 'OR',
				'has_year' => array(
					'key'     => 'pub_year',
					'compare' => 'EXISTS',
					'type'    => 'NUMERIC',
				),
				array(
					'key'     => 'pub_year',
					'compare' => 'NOT EXISTS',
				),
			),
			'orderby'        => array(
				'has_year' => 'DESC',
				'date'     => 'ASC',
			),
			'tax_query'      => array(
				array(
					'taxonomy' => 'pub_type',
					'field'    => 'slug',
					'terms'    => $slug,
				),
			),
		)
	);
}
?>

<style>
  .page-hero { background: linear-gradient(135deg, var(--green) 0%, #145c35 100%); padding: calc(var(--nav-h) + 56px) 0 64px; position: relative; overflow: hidden; }
  .page-hero::after { content:''; position:absolute; bottom:-2px; left:0; right:0; height:60px; background:var(--bg); clip-path:ellipse(55% 100% at 50% 100%); }
  .page-hero-bg { position:absolute; inset:0; background-image: radial-gradient(circle at 80% 30%, rgba(82,183,136,.1) 0%, transparent 55%), radial-gradient(circle at 20% 70%, rgba(224,122,95,.06) 0%, transparent 50%); pointer-events:none; }
  .page-hero-inner { position:relative; z-index:1; text-align:center; }
  .page-hero h1 { font-family:var(--font-head); font-size:clamp(2rem,4vw,3rem); color:#fff; margin-bottom:12px; }
  .page-hero p { color:rgba(255,255,255,.7); font-size:1.05rem; max-width:560px; margin:0 auto; }
  .breadcrumb { display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:20px; font-size:.82rem; color:rgba(255,255,255,.55); }
  .breadcrumb a { color:var(--green-l); } .breadcrumb span { color:rgba(255,255,255,.35); }
  .tab-nav { background:#fff; border-bottom:2px solid var(--border); position:sticky; top:var(--nav-h); z-index:90; }
  .tab-nav-inner { display:flex; gap:0; overflow-x:auto; }
  .tab-btn { display:flex; align-items:center; gap:8px; padding:16px 22px; font-size:.88rem; font-weight:600; color:var(--muted); cursor:pointer; border:none; background:none; border-bottom:3px solid transparent; margin-bottom:-2px; white-space:nowrap; transition:all .2s; }
  .tab-btn:hover { color:var(--green); }
  .tab-btn.active { color:var(--green); border-bottom-color:var(--green); }
  .tab-btn .count { background:var(--green-l); color:var(--green); font-size:.72rem; font-weight:700; padding:2px 8px; border-radius:50px; }
  .tab-btn.active .count { background:var(--green); color:#fff; }
  .tab-panel { display:none; padding:56px 0; }
  .tab-panel.active { display:block; }
  .books-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(200px,1fr)); gap:28px; }
  .book-card { background:#fff; border-radius:var(--r-lg); overflow:hidden; border:1px solid var(--border); box-shadow:var(--shadow); transition:transform .2s, box-shadow .2s; }
  .book-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-lg); }
  .book-cover { width:100%; height:220px; object-fit:cover; display:block; }
  .book-cover-placeholder { width:100%; height:220px; background:linear-gradient(135deg, var(--green) 0%, var(--green-m) 100%); display:flex; flex-direction:column; align-items:center; justify-content:center; color:#fff; gap:10px; }
  .book-cover-placeholder i { font-size:2.5rem; opacity:.7; }
  .book-cover-placeholder span { font-size:.75rem; text-transform:uppercase; letter-spacing:.08em; opacity:.6; }
  .book-body { padding:16px; }
  .book-body h3 { font-family:var(--font-head); font-size:.95rem; color:var(--dark); margin-bottom:6px; line-height:1.35; }
  .book-body h3 a { color:inherit; }
  .book-meta { font-size:.78rem; color:var(--muted); display:flex; flex-direction:column; gap:3px; }
  .book-meta span { display:flex; align-items:center; gap:5px; }
  .book-tag { display:inline-block; margin-top:10px; font-size:.72rem; font-weight:700; padding:3px 10px; border-radius:50px; background:var(--green-l); color:var(--green); }
  .article-list { display:flex; flex-direction:column; gap:0; }
  .article-item { padding:20px 24px; border:1px solid var(--border); background:#fff; border-radius:var(--r-md); margin-bottom:12px; display:grid; grid-template-columns:1fr auto; gap:16px; align-items:start; transition:box-shadow .2s, border-color .2s; }
  .article-item:hover { box-shadow:var(--shadow); border-color:var(--green-l); }
  .article-num { font-size:.8rem; font-weight:700; color:var(--orange); margin-bottom:4px; }
  .article-title { font-family:var(--font-head); font-size:1rem; color:var(--dark); margin-bottom:6px; line-height:1.4; }
  .article-title a { color:inherit; }
  .article-authors { font-size:.82rem; color:var(--muted); margin-bottom:4px; }
  .article-journal { font-size:.82rem; color:var(--green-m); font-weight:600; font-style:italic; }
  .article-badges { display:flex; gap:6px; flex-wrap:wrap; margin-top:8px; }
  .badge { font-size:.7rem; font-weight:700; padding:2px 9px; border-radius:50px; }
  .badge-year { background:#f3f4f6; color:var(--text); }
  .badge-oa { background:rgba(45,106,79,.1); color:var(--green); }
  .article-actions { display:flex; flex-direction:column; gap:6px; align-items:flex-end; }
  .icon-btn { width:36px; height:36px; border-radius:8px; border:1.5px solid var(--border); background:#fff; display:flex; align-items:center; justify-content:center; color:var(--muted); cursor:pointer; transition:all .2s; font-size:.85rem; }
  /* Tarjeta/fila completa clickeable cuando hay enlace */
  a.book-card, a.article-item { text-decoration:none; color:inherit; cursor:pointer; }
  a.article-item:hover .icon-btn { background:var(--green); color:#fff; border-color:var(--green); }
  a.book-card:hover .book-body h3 { color:var(--green); }
  .stats-bar { background:var(--green); color:#fff; padding:28px 0; }
  .stats-bar-inner { display:flex; align-items:center; justify-content:center; gap:48px; flex-wrap:wrap; text-align:center; }
  .stat-item .num { font-family:var(--font-head); font-size:2.2rem; font-weight:700; display:block; }
  .stat-item .lbl { font-size:.75rem; text-transform:uppercase; letter-spacing:.08em; opacity:.65; }
  .pub-empty { text-align:center; padding:40px 0; color:var(--muted); }
  @media(max-width:600px){ .stats-bar-inner{gap:24px;} }

  /* Hero más compacto en esta página (subtítulo largo de 2 líneas) */
  body .page-hero { padding-bottom: 50px; }
  body .page-hero::after { height: 34px; clip-path: ellipse(80% 100% at 50% 100%); }
  /* Pestañas: centradas y sin barra de desplazamiento */
  body .tab-nav-inner { overflow: visible; flex-wrap: wrap; justify-content: center; }
  /* Paginación por pestaña (JS) */
  .pub-hidden { display: none !important; }
  .pub-pagination { display:flex; justify-content:center; gap:6px; margin-top:40px; flex-wrap:wrap; }
  .pub-pagination button { min-width:38px; height:38px; padding:0 12px; border-radius:8px; border:1.5px solid var(--border); background:#fff; font-weight:600; color:var(--text); cursor:pointer; font-size:.875rem; transition:all .2s; }
  .pub-pagination button.active { background:var(--green); color:#fff; border-color:var(--green); cursor:default; }
  .pub-pagination button:hover:not(.active):not(:disabled) { border-color:var(--green); color:var(--green); }
  .pub-pagination button:disabled { opacity:.4; cursor:default; }
  .pub-pagination .ellipsis { align-self:center; color:var(--muted); padding:0 4px; }
</style>

<!-- HERO -->
<div class="page-hero">
  <div class="page-hero-bg"></div>
  <div class="container page-hero-inner">
    <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>/</span><span style="color:rgba(255,255,255,.8)">Publications</span></div>
    <h1>Our Publications</h1>
    <p>Over 30 years of peer-reviewed research, books, and technical contributions to food science and nutrition.</p>
  </div>
</div>

<!-- TAB NAV -->
<div class="tab-nav">
  <div class="container tab-nav-inner">
    <?php $first = true; foreach ( $types as $slug => $cfg ) : ?>
      <button class="tab-btn<?php echo $first ? ' active' : ''; ?>" data-tab="<?php echo esc_attr( $slug ); ?>">
        <i class="fas <?php echo esc_attr( $cfg['icon'] ); ?>"></i> <?php echo esc_html( $cfg['label'] ); ?>
        <span class="count"><?php echo (int) $data[ $slug ]->found_posts; ?></span>
      </button>
    <?php $first = false; endforeach; ?>
  </div>
</div>

<!-- TAB PANELS -->
<?php
$first = true;
foreach ( $types as $slug => $cfg ) :
	$q = $data[ $slug ];
	?>
  <div class="tab-panel<?php echo $first ? ' active' : ''; ?>" id="tab-<?php echo esc_attr( $slug ); ?>" data-pagesize="<?php echo ( 'books' === $cfg['layout'] ) ? 15 : 10; ?>">
    <div class="container">
      <div style="margin-bottom:28px;">
        <span class="eyebrow"><?php echo esc_html( $cfg['label'] ); ?></span>
        <h2 class="section-title"><?php echo esc_html( $cfg['label'] ); ?></h2>
      </div>

      <?php if ( ! $q->have_posts() ) : ?>
        <p class="pub-empty">Aún no hay <?php echo esc_html( strtolower( $cfg['label'] ) ); ?>. Agrégalas en <strong>Publications → Añadir nueva</strong> y elige el tipo "<?php echo esc_html( $cfg['label'] ); ?>".</p>

      <?php elseif ( 'books' === $cfg['layout'] ) : ?>
        <!-- LIBROS: tarjetas con portada -->
        <div class="books-grid">
          <?php
          while ( $q->have_posts() ) :
            $q->the_post();
            $authors = get_field( 'pub_authors' );
            $year    = get_field( 'pub_year' );
            $source  = get_field( 'pub_source' );
            $link    = get_field( 'pub_link' );
            $note    = get_field( 'pub_note' );
            ?>
            <?php $tag = $link ? 'a' : 'div'; ?>
            <<?php echo $tag; ?> class="book-card"<?php echo $link ? ' href="' . esc_url( $link ) . '" target="_blank" rel="noopener"' : ''; ?>>
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'large', array( 'class' => 'book-cover', 'alt' => esc_attr( get_the_title() ) ) ); ?>
              <?php else : ?>
                <div class="book-cover-placeholder"><i class="fas fa-book"></i><span>Book Cover</span></div>
              <?php endif; ?>
              <div class="book-body">
                <h3><?php the_title(); ?></h3>
                <div class="book-meta">
                  <?php if ( $source ) : ?><span><i class="fas fa-building"></i> <?php echo esc_html( $source ); ?></span><?php endif; ?>
                  <?php if ( $year ) : ?><span><i class="far fa-calendar"></i> <?php echo esc_html( $year ); ?></span><?php endif; ?>
                  <?php if ( $authors ) : ?><span><i class="fas fa-user-edit"></i> <?php echo esc_html( $authors ); ?></span><?php endif; ?>
                </div>
                <?php if ( $note ) : ?><span class="book-tag"><?php echo esc_html( $note ); ?></span><?php endif; ?>
              </div>
            </<?php echo $tag; ?>>
          <?php endwhile; ?>
        </div>

      <?php else : ?>
        <!-- LISTA: chapters / articles / technical / abstracts -->
        <div class="article-list">
          <?php
          $i = 0;
          $pad = ( 'chapters' === $slug ) ? 2 : 3;
          while ( $q->have_posts() ) :
            $q->the_post();
            $i++;
            $authors = get_field( 'pub_authors' );
            $year    = get_field( 'pub_year' );
            $source  = get_field( 'pub_source' );
            $link    = get_field( 'pub_link' );
            $note    = get_field( 'pub_note' );
            ?>
            <?php $tag = $link ? 'a' : 'div'; ?>
            <<?php echo $tag; ?> class="article-item"<?php echo $link ? ' href="' . esc_url( $link ) . '" target="_blank" rel="noopener"' : ''; ?>>
              <div>
                <div class="article-num"><?php echo esc_html( trim( $cfg['prefix'] . ' ' . str_pad( $i, $pad, '0', STR_PAD_LEFT ) ) ); ?></div>
                <div class="article-title"><?php the_title(); ?></div>
                <?php if ( $authors ) : ?><div class="article-authors"><?php echo esc_html( $authors ); ?></div><?php endif; ?>
                <?php if ( $source ) : ?><div class="article-journal"><?php echo esc_html( $source ); ?></div><?php endif; ?>
                <div class="article-badges">
                  <?php if ( $year ) : ?><span class="badge badge-year"><?php echo esc_html( $year ); ?></span><?php endif; ?>
                  <?php if ( $note ) : ?><span class="badge badge-oa"><?php echo esc_html( $note ); ?></span><?php endif; ?>
                </div>
              </div>
              <div class="article-actions">
                <?php if ( $link ) : ?><span class="icon-btn" aria-hidden="true"><i class="fas fa-external-link-alt"></i></span><?php endif; ?>
              </div>
            </<?php echo $tag; ?>>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
<?php $first = false; endforeach; ?>

<script>
  // Pestañas
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      const panel = document.getElementById('tab-' + btn.dataset.tab);
      if (panel) panel.classList.add('active');
    });
  });
  // Abrir la pestaña según el #ancla del menú (#books, #chapters, …)
  function elhadiOpenTabFromHash() {
    const hash = location.hash.replace('#', '');
    const btn = document.querySelector('.tab-btn[data-tab="' + hash + '"]');
    if (btn) btn.click();
  }
  elhadiOpenTabFromHash();
  window.addEventListener('hashchange', elhadiOpenTabFromHash);

  // Paginación por pestaña (20 en Books, 10 en el resto) — sin recargar.
  document.querySelectorAll('.tab-panel').forEach(panel => {
    const size = parseInt(panel.dataset.pagesize, 10) || 10;
    const list = panel.querySelector('.books-grid, .article-list');
    if (!list) return;
    const items = Array.from(list.children);
    if (items.length <= size) return;
    const pages = Math.ceil(items.length / size);
    let current = 1;
    const pag = document.createElement('div');
    pag.className = 'pub-pagination';
    list.after(pag);

    function btn(label, page, cls, disabled) {
      const b = document.createElement('button');
      b.textContent = label;
      if (cls) b.classList.add(cls);
      if (disabled) b.disabled = true;
      else if (page && cls !== 'active') b.addEventListener('click', () => show(page, true));
      return b;
    }
    function render() {
      pag.innerHTML = '';
      pag.appendChild(btn('‹', current - 1, '', current === 1));
      const nums = [1, pages, current, current - 1, current + 1].filter(n => n >= 1 && n <= pages);
      const uniq = Array.from(new Set(nums)).sort((a, b) => a - b);
      let prev = 0;
      uniq.forEach(i => {
        if (prev && i - prev > 1) { const e = document.createElement('span'); e.className = 'ellipsis'; e.textContent = '…'; pag.appendChild(e); }
        pag.appendChild(btn(String(i), i, i === current ? 'active' : '', false));
        prev = i;
      });
      pag.appendChild(btn('›', current + 1, '', current === pages));
    }
    function show(p, scroll) {
      current = Math.min(Math.max(1, p), pages);
      items.forEach((it, i) => it.classList.toggle('pub-hidden', !(i >= (current - 1) * size && i < current * size)));
      render();
      if (scroll) {
        const top = list.getBoundingClientRect().top + window.scrollY - 130;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    }
    show(1, false);
  });
</script>

<?php get_footer(); ?>
