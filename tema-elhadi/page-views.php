<?php
/**
 * Plantilla dinámica de la página "Views" (slug: views).
 * Las opiniones salen del CPT "view". Un colaborador agrega una en:
 * Views → Añadir nueva (título, contenido, extracto, Tipo, Autor, Tiempo de lectura).
 * Paginación: 4 por página vía ?vpage=N (la "Featured" sale solo en la página 1).
 */
get_header();
$up       = wp_get_upload_dir()['baseurl'];
$paged    = isset( $_GET['vpage'] ) ? max( 1, intval( $_GET['vpage'] ) ) : 1;
$page_url = get_permalink( get_queried_object_id() );
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
  .views-layout{display:grid;grid-template-columns:1fr 300px;gap:48px;align-items:start;}
  .featured-view{background:var(--green);border-radius:var(--r-lg);overflow:hidden;margin-bottom:32px;display:grid;grid-template-columns:1fr 1fr;}
  .featured-view-body{padding:40px;color:#fff;}
  .view-eyebrow{font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--green-l);margin-bottom:12px;}
  .featured-view h2{font-family:var(--font-head);font-size:1.5rem;color:#fff;line-height:1.3;margin-bottom:14px;}
  .featured-view p{color:rgba(255,255,255,.7);line-height:1.7;font-size:.9rem;margin-bottom:20px;}
  .featured-view-img{background:linear-gradient(135deg,#145c35,#145c35);display:flex;align-items:center;justify-content:center;min-height:280px;}
  .featured-view-img i{font-size:5rem;color:rgba(255,255,255,.15);}
  .view-link{color:var(--green-l);font-weight:600;font-size:.875rem;display:inline-flex;align-items:center;gap:6px;transition:gap .2s;}
  .view-link:hover{gap:12px;}
  .view-card{background:#fff;border-radius:var(--r-lg);border:1px solid var(--border);padding:28px;margin-bottom:20px;display:grid;grid-template-columns:auto 1fr;gap:24px;align-items:start;transition:box-shadow .2s,border-color .2s;}
  .view-card:hover{box-shadow:var(--shadow-md);border-color:var(--green-l);}
  .view-icon{width:52px;height:52px;background:var(--green-l);border-radius:12px;display:flex;align-items:center;justify-content:center;color:var(--green);font-size:1.3rem;flex-shrink:0;}
  .view-meta{display:flex;align-items:center;gap:12px;margin-bottom:8px;font-size:.78rem;color:var(--muted);}
  .view-type{display:inline-block;padding:2px 10px;border-radius:50px;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;}
  .type-opinion{background:rgba(224,122,95,.1);color:var(--orange);}
  .type-commentary{background:rgba(82,183,136,.12);color:var(--green);}
  .type-interview{background:rgba(99,102,241,.1);color:#4f46e5;}
  .type-editorial{background:rgba(245,158,11,.1);color:#d97706;}
  .view-card h3{font-family:var(--font-head);font-size:1.05rem;color:var(--dark);margin-bottom:8px;line-height:1.4;}
  .view-card p{font-size:.875rem;color:var(--muted);line-height:1.65;margin-bottom:12px;}
  .view-card-footer{display:flex;align-items:center;justify-content:space-between;}
  .sidebar-widget{background:#fff;border-radius:var(--r-lg);border:1px solid var(--border);padding:24px;margin-bottom:24px;}
  .sidebar-widget h4{font-family:var(--font-head);font-size:1rem;color:var(--dark);margin-bottom:16px;padding-bottom:10px;border-bottom:2px solid var(--green);display:inline-block;}
  .topic-cloud{display:flex;flex-wrap:wrap;gap:8px;}
  .topic-tag{padding:5px 12px;border-radius:50px;font-size:.78rem;font-weight:600;background:var(--green-l);color:var(--green);border:1px solid rgba(45,106,79,.15);cursor:pointer;transition:all .2s;}
  .topic-tag:hover{background:var(--green);color:#fff;}
  .author-box{display:flex;gap:14px;align-items:flex-start;}
  .author-avatar{width:56px;height:56px;border-radius:50%;object-fit:cover;border:3px solid var(--green-l);}
  .author-avatar-placeholder{width:56px;height:56px;border-radius:50%;background:var(--green);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.4rem;}
  .views-pagination{display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-top:12px;}
  .views-pagination .page-numbers{padding:8px 14px;border:1.5px solid var(--border);border-radius:8px;background:#fff;font-weight:600;color:var(--muted);text-decoration:none;font-size:.85rem;}
  .views-pagination .page-numbers.current{background:var(--green);color:#fff;border-color:var(--green);}
  .views-pagination a.page-numbers:hover{border-color:var(--green);color:var(--green);}
  @media(max-width:900px){.views-layout{grid-template-columns:1fr;}.featured-view{grid-template-columns:1fr;}.featured-view-img{display:none;}}
  @media(max-width:600px){.view-card{grid-template-columns:1fr;}.view-icon{display:none;}}
</style>

<div class="page-hero">
  <div class="page-hero-bg"></div>
  <div class="container page-hero-inner">
    <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>/</span><span style="color:rgba(255,255,255,.8)">Views</span></div>
    <h1>Views &amp; Perspectives</h1>
    <p>Opinions, commentaries, and reflections on food science, nutrition, postharvest technology, and global food security.</p>
  </div>
</div>

<section class="section-pad" style="background:var(--bg);">
  <div class="container">
    <?php
    // La View más reciente = destacada (solo en la página 1).
    $featured_q  = new WP_Query( array( 'post_type' => 'view', 'post_status' => 'publish', 'posts_per_page' => 1 ) );
    $featured_id = $featured_q->have_posts() ? $featured_q->posts[0]->ID : 0;

    if ( $paged <= 1 && $featured_q->have_posts() ) :
      $featured_q->the_post();
      $ftype = get_field( 'view_type' ) ?: 'commentary';
      ?>
      <div class="featured-view">
        <div class="featured-view-body">
          <div class="view-eyebrow"><i class="fas fa-pen-nib"></i> &nbsp;Featured <?php echo esc_html( ucfirst( $ftype ) ); ?></div>
          <h2><?php the_title(); ?></h2>
          <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 45 ) ); ?></p>
          <div style="font-size:.82rem;color:rgba(255,255,255,.6);margin-bottom:16px;">
            <i class="fas fa-user-circle"></i> &nbsp;<?php echo esc_html( get_field( 'view_author' ) ?: 'Dr. Elhadi M. Yahia' ); ?> &nbsp;&nbsp;
            <i class="far fa-calendar"></i> &nbsp;<?php echo esc_html( get_the_date( 'Y' ) ); ?>
            <?php if ( get_field( 'view_readtime' ) ) : ?>&nbsp;&nbsp;<i class="fas fa-clock"></i> &nbsp;<?php echo esc_html( get_field( 'view_readtime' ) ); ?><?php endif; ?>
          </div>
          <a href="<?php the_permalink(); ?>" class="view-link">Read full <?php echo esc_html( strtolower( $ftype ) ); ?> <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="featured-view-img"><i class="fas fa-globe-americas"></i></div>
      </div>
      <?php
      wp_reset_postdata();
    endif;

    // Rejilla: 4 por página, sin la destacada.
    $grid = new WP_Query(
      array(
        'post_type'      => 'view',
        'post_status'    => 'publish',
        'posts_per_page' => 4,
        'paged'          => $paged,
        'post__not_in'   => $featured_id ? array( $featured_id ) : array(),
      )
    );

    if ( $grid->have_posts() || $featured_id ) :
      ?>
      <div class="views-layout">
        <div>
          <?php
          while ( $grid->have_posts() ) :
            $grid->the_post();
            $type = get_field( 'view_type' ) ?: 'opinion';
            ?>
            <div class="view-card">
              <div class="view-icon"><i class="fas <?php echo esc_attr( elhadi_view_icon( $type ) ); ?>"></i></div>
              <div>
                <div class="view-meta">
                  <span class="view-type type-<?php echo esc_attr( $type ); ?>"><?php echo esc_html( ucfirst( $type ) ); ?></span>
                  <span><i class="far fa-calendar"></i> <?php echo esc_html( get_the_date( 'Y' ) ); ?></span>
                  <?php if ( get_field( 'view_readtime' ) ) : ?><span><i class="fas fa-clock"></i> <?php echo esc_html( get_field( 'view_readtime' ) ); ?></span><?php endif; ?>
                </div>
                <h3><?php the_title(); ?></h3>
                <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 35 ) ); ?></p>
                <div class="view-card-footer">
                  <span style="font-size:.78rem;color:var(--muted);"><i class="fas fa-user"></i> <?php echo esc_html( get_field( 'view_author' ) ?: 'Dr. Elhadi M. Yahia' ); ?></span>
                  <a href="<?php the_permalink(); ?>" style="color:var(--green);font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:5px;">Read more <i class="fas fa-arrow-right"></i></a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>

          <?php
          $links = paginate_links(
            array(
              'base'      => $page_url . '?vpage=%#%',
              'format'    => '',
              'current'   => $paged,
              'total'     => $grid->max_num_pages,
              'prev_text' => '‹ Prev',
              'next_text' => 'Next ›',
            )
          );
          if ( $links ) {
            echo '<div class="views-pagination">' . $links . '</div>'; // phpcs:ignore
          }
          wp_reset_postdata();
          ?>
        </div>

        <!-- Sidebar (estático) -->
        <aside>
          <div class="sidebar-widget">
            <h4>About the Author</h4>
            <div class="author-box">
              <img class="author-avatar" src="<?php echo esc_url( $up ); ?>/2024/06/Dr.-Elhadi-Yahia.-Foto-150x150.jpg" alt="Dr. Yahia" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"/>
              <div class="author-avatar-placeholder" style="display:none"><i class="fas fa-user"></i></div>
              <div>
                <p style="font-weight:600;color:var(--dark);font-size:.9rem;margin-bottom:3px;">Dr. Elhadi M. Yahia</p>
                <p style="font-size:.8rem;color:var(--muted);">Emeritus Professor, UAQ. Food Scientist &amp; Expert in Phytochemicals, Postharvest, and Global Food Security.</p>
              </div>
            </div>
          </div>

          <div class="sidebar-widget">
            <h4>Topics</h4>
            <div class="topic-cloud">
              <span class="topic-tag">Phytochemicals</span>
              <span class="topic-tag">Postharvest</span>
              <span class="topic-tag">Food Security</span>
              <span class="topic-tag">Nutrition Policy</span>
              <span class="topic-tag">Cancer Prevention</span>
              <span class="topic-tag">Bioavailability</span>
              <span class="topic-tag">Food Loss</span>
              <span class="topic-tag">Native Fruits</span>
              <span class="topic-tag">FAO</span>
              <span class="topic-tag">Mexico</span>
            </div>
          </div>

          <div class="sidebar-widget">
            <h4>Share Your Thoughts</h4>
            <p style="font-size:.85rem;color:var(--muted);margin-bottom:14px;">Have a question or comment about any of these perspectives? We'd love to hear from you.</p>
            <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn-outline-dark" style="width:100%;justify-content:center;">
              <i class="fas fa-envelope"></i> Contact Us
            </a>
          </div>
        </aside>
      </div>
      <?php
    else :
      ?>
      <p style="text-align:center;padding:60px 0;color:var(--muted);">Aún no hay Views publicadas. Crea una en <strong>Views → Añadir nueva</strong>.</p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
