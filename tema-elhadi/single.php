<?php
/**
 * Plantilla de la entrada individual (una noticia).
 * Le da el diseño del sitio: hero verde con título/fecha + columna legible.
 */
get_header();

// Sección "padre" según el tipo de contenido (para breadcrumb y enlace de regreso).
$elhadi_parents = array(
	'post'        => array( 'News', home_url( '/news/' ) ),
	'view'        => array( 'Views', home_url( '/views/' ) ),
	'publication' => array( 'Publications', home_url( '/publications/' ) ),
);
$elhadi_pt     = get_post_type();
$elhadi_parent = isset( $elhadi_parents[ $elhadi_pt ] ) ? $elhadi_parents[ $elhadi_pt ] : array( 'Home', home_url( '/' ) );
?>

<style>
  .single-hero { background: linear-gradient(135deg, var(--green) 0%, #145c35 100%); padding: calc(var(--nav-h) + 56px) 0 64px; position: relative; overflow: hidden; }
  .single-hero::after { content:''; position:absolute; bottom:-2px; left:0; right:0; height:60px; background:var(--bg); clip-path:ellipse(55% 100% at 50% 100%); }
  .single-hero-inner { position:relative; z-index:1; text-align:center; max-width:820px; margin:0 auto; }
  .single-hero .breadcrumb { display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:18px; font-size:.82rem; color:rgba(255,255,255,.55); }
  .single-hero .breadcrumb a { color:var(--green-l); }
  .single-hero .breadcrumb span { color:rgba(255,255,255,.35); }
  .single-hero h1 { font-family:var(--font-head); font-size:clamp(1.8rem,3.5vw,2.6rem); color:#fff; line-height:1.25; margin-bottom:14px; }
  .single-meta { display:flex; align-items:center; justify-content:center; gap:18px; font-size:.85rem; color:rgba(255,255,255,.75); }
  .single-meta i { margin-right:5px; }

  .single-wrap { background:var(--bg); padding:0 0 72px; }
  .single-article { max-width:820px; margin:0 auto; }
  .single-feat { width:100%; border-radius:var(--r-lg); overflow:hidden; box-shadow:var(--shadow-lg); margin:-32px 0 40px; position:relative; z-index:2; }
  .single-feat img { width:100%; height:auto; display:block; }
  .single-content { background:#fff; border-radius:var(--r-lg); border:1px solid var(--border); padding:48px; box-shadow:var(--shadow); }
  .single-content p { color:var(--text); line-height:1.8; margin-bottom:20px; font-size:1.02rem; }
  .single-content h2 { font-family:var(--font-head); color:var(--dark); font-size:1.5rem; margin:32px 0 14px; }
  .single-content h3 { font-family:var(--font-head); color:var(--dark); font-size:1.2rem; margin:26px 0 12px; }
  .single-content img { max-width:100%; height:auto; border-radius:12px; margin:16px 0; }
  .single-content a { color:var(--green); text-decoration:underline; }
  .single-content ul, .single-content ol { margin:0 0 20px 22px; color:var(--text); line-height:1.8; }
  .single-back { display:inline-flex; align-items:center; gap:8px; margin-top:32px; color:var(--green); font-weight:600; font-size:.9rem; }
  .single-back:hover { gap:14px; }
  @media(max-width:600px){ .single-content{ padding:28px 22px; } }
</style>

<?php while ( have_posts() ) : the_post(); ?>

  <div class="single-hero">
    <div class="container single-hero-inner">
      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>/</span>
        <a href="<?php echo esc_url( $elhadi_parent[1] ); ?>"><?php echo esc_html( $elhadi_parent[0] ); ?></a><span>/</span>
        <span style="color:rgba(255,255,255,.8)"><?php the_title(); ?></span>
      </div>
      <h1><?php the_title(); ?></h1>
      <div class="single-meta">
        <span><i class="far fa-calendar"></i><?php echo esc_html( get_the_date( 'F Y' ) ); ?></span>
        <?php
        if ( 'view' === $elhadi_pt && function_exists( 'get_field' ) ) {
          $vt = get_field( 'view_type' );
          $va = get_field( 'view_author' );
          if ( $vt ) {
            echo '<span><i class="fas fa-tag"></i>' . esc_html( ucfirst( $vt ) ) . '</span>';
          }
          if ( $va ) {
            echo '<span><i class="fas fa-user"></i>' . esc_html( $va ) . '</span>';
          }
        } else {
          $cats = get_the_category();
          if ( $cats ) {
            echo '<span><i class="fas fa-tag"></i>' . esc_html( $cats[0]->name ) . '</span>';
          }
        }
        ?>
      </div>
    </div>
  </div>

  <div class="single-wrap">
    <div class="container">
      <article class="single-article">
        <?php if ( has_post_thumbnail() ) : ?>
          <div class="single-feat"><?php the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?></div>
        <?php endif; ?>

        <div class="single-content">
          <?php the_content(); ?>
        </div>

        <a class="single-back" href="<?php echo esc_url( $elhadi_parent[1] ); ?>">
          <i class="fas fa-arrow-left"></i> Back to <?php echo esc_html( $elhadi_parent[0] ); ?>
        </a>
      </article>
    </div>
  </div>

<?php endwhile; ?>

<?php get_footer(); ?>
