<?php
/**
 * Plantilla dinámica de la página "Links" (slug: links).
 * Directorio agrupado con sidebar de categorías + buscador por pestaña.
 * Videos muestran la portada de YouTube. Datos en el CPT "link_resource".
 */
get_header();

$types      = elhadi_link_types();
$type_icons = array(
	'websites' => 'fa-globe',
	'videos'   => 'fa-play',
	'books'    => 'fa-book',
	'journals' => 'fa-book-open',
);

/** ID de un video de YouTube a partir de su URL (para la miniatura). */
if ( ! function_exists( 'elhadi_youtube_id' ) ) {
	function elhadi_youtube_id( $url ) {
		if ( ! $url ) {
			return '';
		}
		if ( preg_match( '~youtu\.be/([A-Za-z0-9_-]{11})~', $url, $m ) ) {
			return $m[1];
		}
		if ( preg_match( '~[?&]v=([A-Za-z0-9_-]{11})~', $url, $m ) ) {
			return $m[1];
		}
		if ( preg_match( '~youtube\.com/embed/([A-Za-z0-9_-]{11})~', $url, $m ) ) {
			return $m[1];
		}
		return '';
	}
}

// Cargar todo agrupado por tipo → grupo.
$data = array();
foreach ( $types as $slug => $cfg ) {
	$q = new WP_Query(
		array(
			'post_type'      => 'link_resource',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
			'meta_query'     => array( array( 'key' => 'link_type', 'value' => $slug ) ),
		)
	);
	$groups = array();
	while ( $q->have_posts() ) {
		$q->the_post();
		$g = get_field( 'link_group' );
		$g = $g ? $g : '';
		$groups[ $g ][] = array( 'title' => get_the_title(), 'url' => get_field( 'link_url' ) );
	}
	wp_reset_postdata();
	$data[ $slug ] = $groups;
}
?>

<style>
  .page-hero{background:linear-gradient(135deg,var(--green) 0%,#145c35 100%);padding:calc(var(--nav-h) + 56px) 0 64px;position:relative;overflow:hidden;}
  .page-hero::after{content:'';position:absolute;bottom:-2px;left:0;right:0;height:60px;background:var(--bg);clip-path:ellipse(55% 100% at 50% 100%);}
  .page-hero-bg{position:absolute;inset:0;background-image:radial-gradient(circle at 80% 30%,rgba(82,183,136,.1) 0%,transparent 55%);pointer-events:none;}
  .page-hero-inner{position:relative;z-index:1;text-align:center;}
  .page-hero h1{font-family:var(--font-head);font-size:clamp(2rem,4vw,3rem);color:#fff;margin-bottom:12px;}
  .page-hero p{color:rgba(255,255,255,.7);font-size:1.05rem;max-width:560px;margin:0 auto;}
  .breadcrumb{display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:20px;font-size:.82rem;color:rgba(255,255,255,.55);}
  .breadcrumb a{color:var(--green-l);}.breadcrumb span{color:rgba(255,255,255,.35);}
  .tab-nav{background:#fff;border-bottom:2px solid var(--border);position:sticky;top:var(--nav-h);z-index:90;}
  .tab-nav-inner{display:flex;flex-wrap:wrap;justify-content:center;}
  .tab-btn{display:flex;align-items:center;gap:8px;padding:16px 22px;font-size:.88rem;font-weight:600;color:var(--muted);cursor:pointer;border:none;background:none;border-bottom:3px solid transparent;margin-bottom:-2px;white-space:nowrap;transition:all .2s;}
  .tab-btn:hover{color:var(--green);}.tab-btn.active{color:var(--green);border-bottom-color:var(--green);}
  .tab-btn .count{background:var(--green-l);color:var(--green);font-size:.72rem;font-weight:700;padding:2px 8px;border-radius:50px;}
  .tab-btn.active .count{background:var(--green);color:#fff;}
  .tab-panel{display:none;padding:48px 0;}.tab-panel.active{display:block;}

  .lk-layout{display:grid;grid-template-columns:1fr 250px;gap:36px;align-items:start;}
  .lk-sidebar{position:sticky;top:calc(var(--nav-h) + 60px);}
  .lk-search{position:relative;margin-bottom:16px;}
  .lk-search input{width:100%;padding:10px 14px 10px 36px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-body);font-size:.85rem;outline:none;}
  .lk-search input:focus{border-color:var(--green-l);}
  .lk-search i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.85rem;}
  .lk-cats{background:#fff;border:1px solid var(--border);border-radius:var(--r-lg);padding:14px;max-height:65vh;overflow-y:auto;}
  .lk-cats h4{font-family:var(--font-head);font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);margin-bottom:8px;}
  .lk-cat{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:7px 10px;border-radius:8px;font-size:.82rem;color:var(--text);text-decoration:none;cursor:pointer;transition:all .15s;}
  .lk-cat:hover{background:var(--bg);color:var(--green);}
  .lk-cat.active{background:var(--green);color:#fff;}
  .lk-cat .n{font-size:.72rem;opacity:.65;}
  .lk-cat.active .n{opacity:.9;}

  .lk-group{font-family:var(--font-head);font-size:1rem;font-weight:700;color:var(--green);margin:30px 0 14px;padding-bottom:8px;border-bottom:2px solid var(--green-l);}
  .lk-group-block:first-child .lk-group{margin-top:0;}
  .lk-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:10px;}
  .lk-list.cite{grid-template-columns:1fr;gap:8px;}
  .lk-item{display:flex;align-items:center;gap:10px;background:#fff;border:1px solid var(--border);border-radius:10px;padding:11px 14px;text-decoration:none;color:var(--text);font-size:.85rem;transition:all .2s;}
  .lk-item:hover{border-color:var(--green);color:var(--green);box-shadow:var(--shadow);transform:translateY(-1px);}
  .lk-item > i:first-child{color:var(--green);flex-shrink:0;font-size:.9rem;}
  .lk-item > span{flex:1;line-height:1.35;}
  .lk-item .lk-ext{font-size:.7rem;color:var(--muted);flex-shrink:0;}
  .lk-item:hover .lk-ext{color:var(--green);}
  .lk-cite{display:flex;gap:12px;align-items:flex-start;background:#fff;border:1px solid var(--border);border-radius:10px;padding:14px 16px;text-decoration:none;color:var(--text);font-size:.85rem;line-height:1.6;transition:all .2s;}
  .lk-cite:hover{border-color:var(--green-l);box-shadow:var(--shadow);}
  .lk-cite > i{color:var(--green);margin-top:3px;flex-shrink:0;}

  .lk-video-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:18px;}
  .lk-video{background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden;text-decoration:none;color:var(--text);transition:all .2s;display:block;}
  .lk-video:hover{transform:translateY(-3px);box-shadow:var(--shadow-md);border-color:var(--green-l);}
  .lk-video-thumb{position:relative;aspect-ratio:16/9;background:#000;overflow:hidden;}
  .lk-video-thumb img{width:100%;height:100%;object-fit:cover;}
  .lk-video-ph{width:100%;height:100%;background:linear-gradient(135deg,#145c35,#0d2b1e);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.3);font-size:2rem;}
  .lk-play{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:52px;height:52px;background:rgba(224,122,95,.95);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1rem;box-shadow:0 4px 16px rgba(0,0,0,.3);transition:transform .2s;}
  .lk-video:hover .lk-play{transform:translate(-50%,-50%) scale(1.12);}
  .lk-video-title{padding:14px 16px;font-family:var(--font-head);font-size:.9rem;font-weight:600;color:var(--dark);line-height:1.4;}
  .links-empty{text-align:center;padding:40px 0;color:var(--muted);}
  @media(max-width:900px){.lk-layout{grid-template-columns:1fr;}.lk-sidebar{position:static;}}
</style>

<div class="page-hero">
  <div class="page-hero-bg"></div>
  <div class="container page-hero-inner">
    <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>/</span><span style="color:rgba(255,255,255,.8)">Links of Interest</span></div>
    <h1>Links of Interest</h1>
    <p>Curated resources — organizations, videos, books, and journals recommended by the laboratory.</p>
  </div>
</div>

<!-- TAB NAV -->
<div class="tab-nav">
  <div class="container tab-nav-inner">
    <?php $first = true; foreach ( $types as $slug => $cfg ) :
      $total = 0; foreach ( $data[ $slug ] as $items ) { $total += count( $items ); } ?>
      <button class="tab-btn<?php echo $first ? ' active' : ''; ?>" data-tab="<?php echo esc_attr( $slug ); ?>">
        <i class="fas <?php echo esc_attr( $cfg['icon'] ); ?>"></i> <?php echo esc_html( $cfg['label'] ); ?>
        <span class="count"><?php echo (int) $total; ?></span>
      </button>
    <?php $first = false; endforeach; ?>
  </div>
</div>

<?php
$first = true;
foreach ( $types as $slug => $cfg ) :
	$groups   = $data[ $slug ];
	$is_books = ( 'books' === $slug );
	$is_video = ( 'videos' === $slug );
	$icon     = $type_icons[ $slug ];
	// Categorías con nombre (para el sidebar).
	$named = array();
	foreach ( $groups as $g => $items ) {
		if ( '' !== $g ) {
			$named[ $g ] = count( $items );
		}
	}
	$total = 0;
	foreach ( $groups as $items ) {
		$total += count( $items );
	}
	?>
  <div class="tab-panel<?php echo $first ? ' active' : ''; ?>" id="tab-<?php echo esc_attr( $slug ); ?>">
    <div class="container">
      <?php if ( empty( $groups ) ) : ?>
        <p class="links-empty">Aún no hay recursos aquí. Agrégalos en <strong>Links (recursos) → Añadir nuevo</strong> (Tipo: <?php echo esc_html( $cfg['label'] ); ?>).</p>
      <?php else : ?>
        <div class="lk-layout">
          <div class="lk-main">
            <?php foreach ( $groups as $glabel => $items ) : ?>
              <div class="lk-group-block" data-group="<?php echo esc_attr( $glabel ); ?>">
                <?php if ( '' !== $glabel ) : ?><h3 class="lk-group"><?php echo esc_html( $glabel ); ?></h3><?php endif; ?>
                <div class="<?php echo $is_video ? 'lk-video-grid' : ( 'lk-list' . ( $is_books ? ' cite' : '' ) ); ?>">
                  <?php foreach ( $items as $it ) :
                    $url = $it['url'];
                    $tgt = $url ? 'target="_blank" rel="noopener"' : '';
                    if ( $is_video ) :
                      $yt = elhadi_youtube_id( $url );
                      ?>
                      <a class="lk-video" href="<?php echo esc_url( $url ?: '#' ); ?>" <?php echo $tgt; ?>>
                        <div class="lk-video-thumb">
                          <?php if ( $yt ) : ?>
                            <img src="https://img.youtube.com/vi/<?php echo esc_attr( $yt ); ?>/hqdefault.jpg" loading="lazy" alt="<?php echo esc_attr( $it['title'] ); ?>" />
                          <?php else : ?>
                            <div class="lk-video-ph"><i class="fas fa-film"></i></div>
                          <?php endif; ?>
                          <span class="lk-play"><i class="fas fa-play"></i></span>
                        </div>
                        <div class="lk-video-title"><?php echo esc_html( $it['title'] ); ?></div>
                      </a>
                    <?php elseif ( $is_books ) : ?>
                      <a class="lk-cite" href="<?php echo esc_url( $url ?: '#' ); ?>" <?php echo $tgt; ?>>
                        <i class="fas fa-book"></i><span><?php echo esc_html( $it['title'] ); ?></span>
                      </a>
                    <?php else : ?>
                      <a class="lk-item" href="<?php echo esc_url( $url ?: '#' ); ?>" <?php echo $tgt; ?>>
                        <i class="fas <?php echo esc_attr( $icon ); ?>"></i>
                        <span><?php echo esc_html( $it['title'] ); ?></span>
                        <i class="fas fa-arrow-up-right-from-square lk-ext"></i>
                      </a>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <aside class="lk-sidebar">
            <div class="lk-search"><i class="fas fa-search"></i><input type="text" placeholder="Buscar en <?php echo esc_attr( $cfg['label'] ); ?>…" /></div>
            <?php if ( count( $named ) >= 2 ) : ?>
              <div class="lk-cats">
                <h4>Categorías</h4>
                <a class="lk-cat active" data-cat="__all"><span>All</span><span class="n"><?php echo (int) $total; ?></span></a>
                <?php foreach ( $named as $g => $c ) : ?>
                  <a class="lk-cat" data-cat="<?php echo esc_attr( $g ); ?>"><span><?php echo esc_html( $g ); ?></span><span class="n"><?php echo (int) $c; ?></span></a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </aside>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php $first = false; endforeach; ?>

<script>
  // Pestañas
  document.querySelectorAll('.tab-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
      document.querySelectorAll('.tab-btn').forEach(function(b){ b.classList.remove('active'); });
      document.querySelectorAll('.tab-panel').forEach(function(p){ p.classList.remove('active'); });
      btn.classList.add('active');
      var panel = document.getElementById('tab-' + btn.dataset.tab);
      if (panel) panel.classList.add('active');
    });
  });
  function elhadiLinksHash(){
    var h = location.hash.replace('#','');
    var b = document.querySelector('.tab-btn[data-tab="' + h + '"]');
    if (b) b.click();
  }
  elhadiLinksHash();
  window.addEventListener('hashchange', elhadiLinksHash);

  // Filtro por categoría + buscador (por pestaña)
  document.querySelectorAll('.tab-panel').forEach(function(panel){
    var blocks = panel.querySelectorAll('.lk-group-block');
    var cats   = panel.querySelectorAll('.lk-cat');
    var search = panel.querySelector('.lk-search input');

    function showAllItems(b){ b.querySelectorAll('.lk-item,.lk-cite,.lk-video').forEach(function(it){ it.style.display=''; }); }
    function applyCat(cat){
      blocks.forEach(function(b){
        b.style.display = (cat === '__all' || b.dataset.group === cat) ? '' : 'none';
        showAllItems(b);
      });
    }
    cats.forEach(function(c){
      c.addEventListener('click', function(e){
        e.preventDefault();
        cats.forEach(function(x){ x.classList.remove('active'); });
        c.classList.add('active');
        if (search) search.value = '';
        applyCat(c.dataset.cat);
      });
    });
    if (search){
      search.addEventListener('input', function(){
        var q = search.value.trim().toLowerCase();
        cats.forEach(function(x){ x.classList.remove('active'); });
        var all = panel.querySelector('.lk-cat[data-cat="__all"]'); if (all) all.classList.add('active');
        if (!q){ applyCat('__all'); return; }
        blocks.forEach(function(b){
          var any = false;
          b.querySelectorAll('.lk-item,.lk-cite,.lk-video').forEach(function(it){
            var show = it.textContent.toLowerCase().indexOf(q) !== -1;
            it.style.display = show ? '' : 'none';
            if (show) any = true;
          });
          b.style.display = any ? '' : 'none';
        });
      });
    }
  });
</script>

<?php get_footer(); ?>
