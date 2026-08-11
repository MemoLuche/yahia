<?php
/**
 * Plantilla dinámica de la página "News" (slug: news).
 * Las noticias salen de las Entradas (Posts) nativas de WordPress.
 * Un colaborador agrega una noticia en: Entradas → Añadir nueva
 * (título, contenido, imagen destacada y categoría).
 */
get_header();

/** Etiqueta de categoría con color (naranja para International/Awards, verde el resto). */
if ( ! function_exists( 'elhadi_news_tag' ) ) {
	function elhadi_news_tag( $cat ) {
		$slug   = $cat ? $cat->slug : 'general';
		$name   = $cat ? $cat->name : 'General';
		$orange = in_array( $slug, array( 'international', 'awards' ), true );
		$bg     = $orange ? 'rgba(224,122,95,.1)' : 'rgba(82,183,136,.1)';
		$color  = $orange ? 'var(--orange)' : 'var(--green)';
		return '<span class="news-tag" style="background:' . $bg . ';color:' . $color . ';font-size:.68rem;">' . esc_html( $name ) . '</span>';
	}
}
?>

<style>
  .page-hero { background: linear-gradient(135deg, var(--green) 0%, #145c35 100%); padding: calc(var(--nav-h) + 56px) 0 64px; position: relative; overflow: hidden; }
  .page-hero::after { content:''; position:absolute; bottom:-2px; left:0; right:0; height:60px; background:var(--bg); clip-path:ellipse(55% 100% at 50% 100%); }
  .page-hero-bg { position:absolute; inset:0; background-image: radial-gradient(circle at 80% 30%, rgba(82,183,136,.1) 0%, transparent 55%); pointer-events:none; }
  .page-hero-inner { position:relative; z-index:1; text-align:center; }
  .page-hero h1 { font-family:var(--font-head); font-size:clamp(2rem,4vw,3rem); color:#fff; margin-bottom:12px; }
  .page-hero p { color:rgba(255,255,255,.7); font-size:1.05rem; max-width:560px; margin:0 auto; }
  .breadcrumb { display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:20px; font-size:.82rem; color:rgba(255,255,255,.55); }
  .breadcrumb a { color:var(--green-l); } .breadcrumb span { color:rgba(255,255,255,.35); }
  .featured-news { display:grid; grid-template-columns:1.4fr 1fr; gap:0; border-radius:var(--r-lg); overflow:hidden; box-shadow:var(--shadow-lg); margin-bottom:48px; }
  .featured-news-img { position:relative; min-height:380px; }
  .featured-news-img img { width:100%; height:100%; object-fit:cover; }
  .featured-news-img .overlay { position:absolute; inset:0; background:linear-gradient(to right, transparent, rgba(0,0,0,.3)); }
  .featured-news-body { background:#fff; padding:40px; display:flex; flex-direction:column; justify-content:center; }
  .news-tag { display:inline-flex; align-items:center; gap:6px; font-size:.72rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#fff; background:var(--orange); padding:4px 12px; border-radius:50px; margin-bottom:14px; width:fit-content; }
  .featured-news-body .news-tag { color:var(--orange); background:rgba(224,122,95,.1); }
  .featured-news-body h2 { font-family:var(--font-head); font-size:1.6rem; color:var(--dark); line-height:1.3; margin-bottom:12px; }
  .featured-news-body p { color:var(--muted); line-height:1.7; margin-bottom:20px; }
  .read-more { display:inline-flex; align-items:center; gap:8px; color:var(--green); font-weight:600; font-size:.9rem; transition:gap .2s; }
  .read-more:hover { gap:14px; color:var(--orange); }
  .news-filters { display:flex; align-items:center; gap:12px; margin:0 0 36px; flex-wrap:wrap; }
  .news-filters label { font-weight:600; font-size:.875rem; color:var(--text); }
  .filter-chip { padding:7px 16px; border-radius:50px; border:1.5px solid var(--border); font-size:.82rem; font-weight:600; color:var(--muted); cursor:pointer; transition:all .2s; background:#fff; }
  .filter-chip.active, .filter-chip:hover { background:var(--green); color:#fff; border-color:var(--green); }
  .news-grid-main { display:grid; grid-template-columns:repeat(3, 1fr); gap:28px; }
  .news-item { background:#fff; border-radius:var(--r-lg); overflow:hidden; border:1px solid var(--border); transition:transform .2s, box-shadow .2s; display:flex; flex-direction:column; text-decoration:none; color:inherit; }
  .news-item:hover { transform:translateY(-5px); box-shadow:var(--shadow-md); }
  .news-item-img { width:100%; height:190px; object-fit:cover; display:block; }
  .news-item-img-placeholder { width:100%; height:190px; background:linear-gradient(135deg, var(--green-l) 0%, #d1e8db 100%); display:flex; align-items:center; justify-content:center; color:var(--green-m); font-size:2.5rem; }
  .news-item-body { padding:20px; flex:1; display:flex; flex-direction:column; }
  .news-item-meta { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
  .news-item h3 { font-family:var(--font-head); font-size:1rem; color:var(--dark); line-height:1.4; margin-bottom:8px; flex:1; }
  .news-item p { font-size:.845rem; color:var(--muted); line-height:1.6; flex:1; }
  .news-item-footer { display:flex; align-items:center; justify-content:space-between; border-top:1px solid var(--border); padding-top:12px; margin-top:14px; font-size:.78rem; color:var(--muted); }
  .news-date { display:flex; align-items:center; gap:5px; }
  .news-layout { display:grid; grid-template-columns:1fr 290px; gap:40px; align-items:start; }
  .sidebar-widget { background:#fff; border-radius:var(--r-lg); border:1px solid var(--border); padding:24px; margin-bottom:24px; }
  .sidebar-widget h4 { font-family:var(--font-head); font-size:1rem; color:var(--dark); margin-bottom:16px; padding-bottom:12px; border-bottom:2px solid var(--green); display:inline-block; }
  .sidebar-recent { display:flex; flex-direction:column; gap:14px; }
  .sidebar-item { display:flex; gap:12px; align-items:flex-start; text-decoration:none; color:inherit; }
  .sidebar-item img { width:60px; height:60px; object-fit:cover; border-radius:8px; flex-shrink:0; }
  .sidebar-item-placeholder { width:60px; height:60px; background:var(--green-l); border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; color:var(--green-m); }
  .sidebar-item p { font-size:.82rem; color:var(--dark); font-weight:500; line-height:1.4; margin-bottom:3px; }
  .sidebar-item span { font-size:.75rem; color:var(--muted); }
  .cat-list { display:flex; flex-direction:column; gap:8px; }
  .cat-item { display:flex; align-items:center; justify-content:space-between; padding:9px 12px; background:var(--bg); border-radius:8px; font-size:.85rem; font-weight:500; color:var(--text); cursor:pointer; transition:background .2s, color .2s; text-decoration:none; }
  .cat-item:hover { background:var(--green); color:#fff; }
  .cat-count { font-size:.75rem; background:#fff; color:var(--muted); padding:2px 8px; border-radius:50px; }
  .cat-item:hover .cat-count { background:rgba(255,255,255,.2); color:#fff; }
  @media(max-width:900px) { .news-grid-main{grid-template-columns:1fr 1fr;} .featured-news{grid-template-columns:1fr;} .featured-news-img{min-height:240px;} .news-layout{grid-template-columns:1fr;} }
  @media(max-width:600px) { .news-grid-main{grid-template-columns:1fr;} }
</style>

<div class="page-hero">
  <div class="page-hero-bg"></div>
  <div class="container page-hero-inner">
    <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>/</span><span style="color:rgba(255,255,255,.8)">News</span></div>
    <h1>News &amp; Updates</h1>
    <p>Stay up to date with the latest research, events, awards, and activities from the laboratory.</p>
  </div>
</div>

<section class="section-pad" style="background:var(--bg);">
  <div class="container">
    <?php
    $all_news = new WP_Query(
      array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 30,
        'ignore_sticky_posts' => true,
      )
    );

    if ( $all_news->have_posts() ) :
      $all_news->the_post();           // El más reciente = destacado.
      $cats = get_the_category();
      $cat  = $cats ? $cats[0] : null;
      ?>
      <!-- Destacada (la noticia más reciente) -->
      <div class="featured-news">
        <div class="featured-news-img">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
          <?php else : ?>
            <div style="width:100%;height:100%;min-height:380px;background:linear-gradient(135deg,var(--green),#145c35);"></div>
          <?php endif; ?>
          <div class="overlay"></div>
        </div>
        <div class="featured-news-body">
          <span class="news-tag"><i class="fas fa-star"></i> Featured</span>
          <h2><?php the_title(); ?></h2>
          <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 40 ) ); ?></p>
          <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;font-size:.82rem;color:var(--muted);">
            <span><i class="far fa-calendar"></i> <?php echo esc_html( get_the_date( 'F Y' ) ); ?></span>
            <?php if ( $cat ) : ?><span><i class="fas fa-tag"></i> <?php echo esc_html( $cat->name ); ?></span><?php endif; ?>
          </div>
          <a href="<?php the_permalink(); ?>" class="read-more">Read full story <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="news-layout">
        <div>
          <!-- Filtros por categoría -->
          <div class="news-filters">
            <label>Filter:</label>
            <span class="filter-chip active" onclick="filterNews(this,'all')">All</span>
            <?php foreach ( get_categories( array( 'hide_empty' => true ) ) as $c ) : ?>
              <span class="filter-chip" onclick="filterNews(this,'<?php echo esc_attr( $c->slug ); ?>')"><?php echo esc_html( $c->name ); ?></span>
            <?php endforeach; ?>
          </div>

          <div class="news-grid-main">
            <?php
            // El resto de las noticias (todas menos la destacada).
            while ( $all_news->have_posts() ) :
              $all_news->the_post();
              $cats = get_the_category();
              $cat  = $cats ? $cats[0] : null;
              $slug = $cat ? $cat->slug : 'general';
              ?>
              <a href="<?php the_permalink(); ?>" class="news-item" data-cat="<?php echo esc_attr( $slug ); ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                  <?php the_post_thumbnail( 'medium', array( 'class' => 'news-item-img', 'alt' => esc_attr( get_the_title() ) ) ); ?>
                <?php else : ?>
                  <div class="news-item-img-placeholder"><i class="fas fa-newspaper"></i></div>
                <?php endif; ?>
                <div class="news-item-body">
                  <div class="news-item-meta"><?php echo elhadi_news_tag( $cat ); // phpcs:ignore ?></div>
                  <h3><?php the_title(); ?></h3>
                  <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
                  <div class="news-item-footer"><span class="news-date"><i class="far fa-calendar"></i> <?php echo esc_html( get_the_date( 'F Y' ) ); ?></span><span style="color:var(--green);font-weight:600;">Read →</span></div>
                </div>
              </a>
            <?php endwhile; ?>
          </div>

          <?php
          // Paginación nativa.
          echo '<div style="display:flex;align-items:center;justify-content:center;gap:6px;margin-top:40px;">';
          echo paginate_links( array( 'total' => $all_news->max_num_pages ) );
          echo '</div>';
          ?>
        </div>

        <!-- Sidebar -->
        <aside>
          <div class="sidebar-widget">
            <h4>Categories</h4>
            <div class="cat-list">
              <?php foreach ( get_categories( array( 'hide_empty' => true ) ) as $c ) : ?>
                <a class="cat-item" href="<?php echo esc_url( get_category_link( $c ) ); ?>">
                  <span><i class="fas fa-folder" style="color:var(--green);margin-right:8px;"></i><?php echo esc_html( $c->name ); ?></span>
                  <span class="cat-count"><?php echo (int) $c->count; ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="sidebar-widget">
            <h4>Recent Posts</h4>
            <div class="sidebar-recent">
              <?php
              foreach ( get_posts( array( 'numberposts' => 4 ) ) as $rp ) :
                ?>
                <a class="sidebar-item" href="<?php echo esc_url( get_permalink( $rp ) ); ?>">
                  <?php if ( has_post_thumbnail( $rp ) ) : ?>
                    <img src="<?php echo esc_url( get_the_post_thumbnail_url( $rp, 'thumbnail' ) ); ?>" alt="" />
                  <?php else : ?>
                    <div class="sidebar-item-placeholder"><i class="fas fa-seedling"></i></div>
                  <?php endif; ?>
                  <div><p><?php echo esc_html( get_the_title( $rp ) ); ?></p><span><i class="far fa-calendar"></i> <?php echo esc_html( get_the_date( 'M Y', $rp ) ); ?></span></div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="sidebar-widget" style="background:var(--green);border-color:var(--green);">
            <h4 style="color:#fff;border-bottom-color:var(--green-l);">Stay Updated</h4>
            <p style="font-size:.85rem;color:rgba(255,255,255,.7);margin-bottom:16px;">Subscribe to receive lab news and new publications directly in your inbox.</p>
            <input type="email" placeholder="your@email.com" style="width:100%;padding:10px 14px;border-radius:8px;border:none;font-family:var(--font-body);font-size:.875rem;margin-bottom:10px;" />
            <button style="width:100%;padding:10px;background:var(--orange);color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">Subscribe <i class="fas fa-arrow-right"></i></button>
          </div>
        </aside>
      </div>
      <?php
      wp_reset_postdata();
    else :
      ?>
      <p style="text-align:center;padding:60px 0;color:var(--muted);">Aún no hay noticias publicadas. Crea una en <strong>Entradas → Añadir nueva</strong>.</p>
    <?php endif; ?>
  </div>
</section>

<script>
  function filterNews(el, cat) {
    document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('.news-item').forEach(item => {
      item.style.display = (cat === 'all' || item.dataset.cat === cat) ? 'flex' : 'none';
    });
  }
</script>

<?php get_footer(); ?>
