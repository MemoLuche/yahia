<?php
/**
 * Perfil individual de un miembro del equipo (CPT team_member).
 * Solo se llega aquí desde las tarjetas que tienen info suficiente.
 */
get_header();
$role   = get_field( 'team_role' );
$group  = get_field( 'team_group' );
$short  = get_field( 'team_bio' );
$tags   = array_filter( array_map( 'trim', explode( ',', (string) get_field( 'team_tags' ) ) ) );
$labels = array( 'lab' => 'Lab Team', 'collaborator' => 'Collaborator', 'past' => 'Previous Member' );
$glabel = isset( $labels[ $group ] ) ? $labels[ $group ] : '';
?>

<style>
  .page-hero { background:linear-gradient(130deg,#145c35 0%,#1e8c4e 55%,#0d6e40 100%); position:relative; overflow:hidden; }
  .page-hero::after { content:''; position:absolute; bottom:-2px; left:0; right:0; height:48px; background:var(--bg); clip-path:ellipse(70% 100% at 50% 100%); }
  .page-hero-inner { text-align:center; position:relative; z-index:1; max-width:680px; margin:0 auto; }
  .page-hero .breadcrumb { display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:16px; font-size:.8rem; color:rgba(255,255,255,.55); }
  .page-hero .breadcrumb a { color:rgba(255,255,255,.8); }
  .page-hero h1 { font-family:var(--font-head); font-size:clamp(1.8rem,3.5vw,2.6rem); color:#fff; margin-bottom:10px; }
  .page-hero .subrole { color:rgba(255,255,255,.8); font-size:1rem; }

  .profile-wrap { background:var(--bg); padding:0 0 72px; }
  .profile-card { display:grid; grid-template-columns:280px 1fr; gap:48px; align-items:start; background:#fff; border-radius:var(--r-xl); padding:44px; box-shadow:var(--shadow-lg); border:1px solid var(--border); max-width:940px; margin:-30px auto 0; position:relative; z-index:2; }
  .profile-photo-col { text-align:center; }
  .profile-photo { width:220px; height:220px; border-radius:50%; object-fit:cover; object-position:top; border:4px solid var(--green-l); box-shadow:var(--shadow-md); margin:0 auto 18px; display:block; }
  .profile-fallback { width:220px; height:220px; border-radius:50%; background:var(--green-l); display:flex; align-items:center; justify-content:center; font-size:5rem; color:var(--green); margin:0 auto 18px; }
  .profile-badge { display:inline-flex; align-items:center; gap:6px; background:var(--green); color:#fff; padding:5px 14px; border-radius:var(--r-sm); font-size:.74rem; font-weight:600; }
  .profile-name { font-family:var(--font-head); font-size:1.7rem; font-weight:700; color:var(--dark); margin-bottom:4px; }
  .profile-role { font-size:.95rem; color:var(--muted); margin-bottom:14px; }
  .profile-divider { width:36px; height:3px; background:var(--green); border-radius:2px; margin:16px 0; }
  .profile-tags { display:flex; flex-wrap:wrap; gap:8px; margin:14px 0; }
  .profile-tag { font-size:.73rem; font-weight:600; color:var(--green); background:var(--green-l); padding:3px 10px; border-radius:var(--r-sm); border:1px solid rgba(30,140,78,.15); }
  .profile-bio { font-size:.95rem; color:var(--text); line-height:1.8; }
  .profile-bio p { margin-bottom:16px; }
  .single-back { display:inline-flex; align-items:center; gap:8px; margin-top:24px; color:var(--green); font-weight:600; font-size:.9rem; }
  .single-back:hover { gap:14px; }
  @media(max-width:768px){ .profile-card { grid-template-columns:1fr; gap:28px; padding:28px; } .profile-photo,.profile-fallback{ width:160px;height:160px; } }
</style>

<?php while ( have_posts() ) : the_post(); ?>

  <div class="page-hero">
    <div class="container page-hero-inner">
      <div class="breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>›</span>
        <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a><span>›</span>
        <span style="color:rgba(255,255,255,.85)"><?php the_title(); ?></span>
      </div>
      <h1><?php the_title(); ?></h1>
      <?php if ( $role ) : ?><div class="subrole"><?php echo esc_html( $role ); ?></div><?php endif; ?>
    </div>
  </div>

  <div class="profile-wrap">
    <div class="container">
      <div class="profile-card">
        <div class="profile-photo-col">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'medium', array( 'class' => 'profile-photo', 'alt' => esc_attr( get_the_title() ) ) ); ?>
          <?php else : ?>
            <div class="profile-fallback"><i class="fa-solid fa-user"></i></div>
          <?php endif; ?>
          <?php if ( $glabel ) : ?><span class="profile-badge"><i class="fa-solid fa-id-badge"></i> <?php echo esc_html( $glabel ); ?></span><?php endif; ?>
        </div>

        <div>
          <h2 class="profile-name"><?php the_title(); ?></h2>
          <?php if ( $role ) : ?><p class="profile-role"><?php echo esc_html( $role ); ?></p><?php endif; ?>
          <?php if ( $tags ) : ?>
            <div class="profile-tags"><?php foreach ( $tags as $t ) : ?><span class="profile-tag"><?php echo esc_html( $t ); ?></span><?php endforeach; ?></div>
          <?php endif; ?>
          <div class="profile-divider"></div>
          <?php
          $content = trim( get_the_content() );
          if ( $content ) {
            echo '<div class="profile-bio">';
            the_content();
            echo '</div>';
          } elseif ( $short ) {
            echo '<p class="profile-bio">' . esc_html( $short ) . '</p>';
          }
          ?>
          <a class="single-back" href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><i class="fas fa-arrow-left"></i> Back to About</a>
        </div>
      </div>
    </div>
  </div>

<?php endwhile; ?>

<?php get_footer(); ?>
