<?php
/**
 * Plantilla dinámica de la página "About" (slug: about).
 * El equipo sale del CPT "team_member", separado en Lab Team y Collaborators.
 * Un colaborador agrega/edita en: Equipo → Añadir nuevo
 * (Título = nombre, Imagen destacada = foto, y los campos Grupo/Cargo/Bio/Etiquetas).
 */
get_header();
$up = wp_get_upload_dir()['baseurl'];

/** Pinta una tarjeta de miembro a partir del post actual del loop. */
if ( ! function_exists( 'elhadi_member_card' ) ) {
	function elhadi_member_card() {
		$role = get_field( 'team_role' );
		$bio  = get_field( 'team_bio' );
		$tags = array_filter( array_map( 'trim', explode( ',', (string) get_field( 'team_tags' ) ) ) );
		// Tiene perfil propio solo si hay info suficiente (foto, bio corta o contenido).
		$has_detail = has_post_thumbnail() || $bio || '' !== trim( get_the_content() );
		$tag        = $has_detail ? 'a' : 'div';
		$href       = $has_detail ? ' href="' . esc_url( get_permalink() ) . '"' : '';
		echo '<' . $tag . ' class="member-card"' . $href . '>'; // phpcs:ignore
		?>
			<div class="member-card-top">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'member-photo', 'alt' => esc_attr( get_the_title() ) ) ); ?>
				<?php else : ?>
					<div class="member-fallback"><i class="fa-solid fa-user"></i></div>
				<?php endif; ?>
				<div>
					<div class="member-name"><?php the_title(); ?></div>
					<?php if ( $role ) : ?><div class="member-pos"><?php echo esc_html( $role ); ?></div><?php endif; ?>
				</div>
			</div>
			<?php if ( $bio ) : ?>
				<div class="member-card-body">
					<p class="member-bio"><?php echo esc_html( $bio ); ?></p>
				</div>
			<?php endif; ?>
			<?php if ( $tags ) : ?>
				<div class="member-card-foot">
					<?php foreach ( $tags as $t ) : ?>
						<span class="member-tag"><i class="fa-solid fa-tag"></i> <?php echo esc_html( $t ); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		<?php
		echo '</' . $tag . '>'; // phpcs:ignore
	}
}

/** Consulta de un grupo del equipo (lab / collaborator), ordenado por "Orden" y luego nombre. */
function elhadi_team_query( $group ) {
	return new WP_Query(
		array(
			'post_type'      => 'team_member',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
			'meta_query'     => array(
				array(
					'key'   => 'team_group',
					'value' => $group,
				),
			),
		)
	);
}
?>

<style>
  .page-hero { padding-top:var(--nav-h); background:linear-gradient(130deg,#145c35 0%,#1e8c4e 55%,#0d6e40 100%); padding-bottom:64px; position:relative; overflow:hidden; }
  .page-hero::after { content:''; position:absolute; bottom:-2px; left:0; right:0; height:56px; background:var(--bg); clip-path:ellipse(55% 100% at 50% 100%); }
  .page-hero-inner { padding-top:56px; text-align:center; position:relative; z-index:1; }
  .page-hero h1 { font-family:var(--font-head); font-size:clamp(2rem,4vw,2.8rem); font-weight:700; color:#fff; margin-bottom:12px; }
  .page-hero p { color:rgba(255,255,255,.75); font-size:1rem; max-width:540px; margin:0 auto; }
  .breadcrumb { display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:18px; font-size:.8rem; color:rgba(255,255,255,.5); }
  .breadcrumb a { color:rgba(255,255,255,.75); }

  .about-section { padding:64px 0; }
  .about-section.alt { background:var(--bg); }
  .section-divider { display:flex; align-items:center; gap:16px; margin-bottom:40px; }
  .section-divider h2 { font-family:var(--font-head); font-size:1.4rem; font-weight:700; color:var(--dark); white-space:nowrap; }
  .section-divider::after { content:''; flex:1; height:1px; background:var(--border); }
  .section-divider .sd-pill { display:inline-flex; align-items:center; gap:6px; background:var(--green); color:#fff; padding:4px 12px; border-radius:var(--r-sm); font-size:.72rem; font-weight:700; white-space:nowrap; }

  .members-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:24px; }
  .member-card { background:#fff; border-radius:var(--r-lg); overflow:hidden; border:1px solid var(--border); box-shadow:var(--shadow); transition:transform var(--transition), box-shadow var(--transition); }
  .member-card:hover { transform:translateY(-4px); box-shadow:var(--shadow-md); }
  .member-card-top { padding:28px 24px 20px; display:flex; gap:16px; align-items:flex-start; border-bottom:1px solid var(--border); }
  .member-photo { width:76px; height:76px; border-radius:50%; object-fit:cover; object-position:top; flex-shrink:0; border:3px solid var(--green-l); }
  .member-fallback { width:76px; height:76px; border-radius:50%; background:var(--green-l); display:flex; align-items:center; justify-content:center; font-size:2rem; color:var(--green); flex-shrink:0; }
  .member-name { font-family:var(--font-head); font-size:1rem; font-weight:700; color:var(--dark); margin-bottom:3px; line-height:1.3; }
  .member-pos { display:inline-block; font-size:.69rem; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:var(--green); background:var(--green-l); padding:2px 9px; border-radius:var(--r-sm); margin-top:4px; }
  .member-card-body { padding:18px 24px; }
  .member-bio { font-size:.855rem; color:var(--muted); line-height:1.65; display:-webkit-box; -webkit-line-clamp:5; -webkit-box-orient:vertical; overflow:hidden; }
  .member-card-foot { padding:12px 24px; background:var(--bg); border-top:1px solid var(--border); display:flex; gap:8px; flex-wrap:wrap; }
  .member-tag { display:inline-flex; align-items:center; gap:5px; font-size:.73rem; font-weight:600; color:var(--green); background:var(--green-l); padding:3px 9px; border-radius:var(--r-sm); border:1px solid rgba(30,140,78,.15); }
  a.member-card, a.past-item { text-decoration:none; color:inherit; cursor:pointer; }
  a.member-card:hover .member-name { color:var(--green); }

  .mission-strip { background:var(--green-d); padding:64px 0; }
  .mission-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:32px; }
  .mission-item { text-align:center; }
  .mission-icon { width:64px; height:64px; border-radius:var(--r-lg); background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2); display:flex; align-items:center; justify-content:center; font-size:1.6rem; color:#fff; margin:0 auto 18px; }
  .mission-item h3 { font-family:var(--font-head); font-size:1.05rem; font-weight:700; color:#fff; margin-bottom:8px; }
  .mission-item p { font-size:.875rem; color:rgba(255,255,255,.72); line-height:1.65; }
  .team-empty { color:var(--muted); font-size:.9rem; }

  /* Miembros anteriores: tarjetas compactas (son muchos) */
  .past-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(230px,1fr)); gap:12px; }
  .past-item { background:#fff; border:1px solid var(--border); border-radius:var(--r-md); padding:13px 15px; display:flex; gap:12px; align-items:center; transition:border-color .2s; }
  .past-item:hover { border-color:var(--green-l); }
  .pi-icon { width:38px; height:38px; border-radius:50%; background:var(--green-l); color:var(--green); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.85rem; }
  .pi-photo { width:38px; height:38px; border-radius:50%; object-fit:cover; object-position:top; flex-shrink:0; }
  .pi-name { font-weight:600; color:var(--dark); font-size:.875rem; line-height:1.3; }
  .pi-meta { font-size:.72rem; color:var(--muted); margin-top:2px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }

  @media(max-width:900px){ .mission-grid { grid-template-columns:1fr; gap:24px; } }
  @media(max-width:600px){ .members-grid { grid-template-columns:1fr; } }
</style>

<!-- HERO -->
<section class="page-hero">
  <div class="container page-hero-inner">
    <div class="breadcrumb">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
      <span>›</span>
      <span>About Us</span>
    </div>
    <h1>About Us</h1>
    <p>Meet the people behind the science — a dedicated team working to keep food fresh, nutritious, and available for everyone.</p>
  </div>
</section>

<!-- LAB TEAM -->
<section class="about-section">
  <div class="container">
    <div class="section-divider">
      <span class="sd-pill"><i class="fa-solid fa-flask"></i> Lab Team</span>
      <h2>Our Laboratory</h2>
    </div>
    <div class="members-grid">
      <?php
      $lab = elhadi_team_query( 'lab' );
      if ( $lab->have_posts() ) :
        while ( $lab->have_posts() ) :
          $lab->the_post();
          elhadi_member_card();
        endwhile;
        wp_reset_postdata();
      else :
        echo '<p class="team-empty">Aún no hay miembros. Agrégalos en <strong>Equipo → Añadir nuevo</strong> (Grupo: Lab Team).</p>';
      endif;
      ?>
    </div>
  </div>
</section>

<!-- MISSION STRIP -->
<section class="mission-strip">
  <div class="container">
    <div class="mission-grid">
      <div class="mission-item">
        <div class="mission-icon"><i class="fa-solid fa-seedling"></i></div>
        <h3>Our Mission</h3>
        <p>To improve the quality, safety, and availability of fresh food for everyone — from smallholder farmers to consumers worldwide.</p>
      </div>
      <div class="mission-item">
        <div class="mission-icon"><i class="fa-solid fa-microscope"></i></div>
        <h3>Our Research</h3>
        <p>We study postharvest biology, phytochemicals, nutrition, food safety, and the science of keeping produce fresh longer.</p>
      </div>
      <div class="mission-item">
        <div class="mission-icon"><i class="fa-solid fa-handshake"></i></div>
        <h3>Our Impact</h3>
        <p>We work with the FAO, governments, and producers in 40+ countries to reduce food loss and improve food security for millions.</p>
      </div>
    </div>
  </div>
</section>

<!-- COLLABORATORS -->
<section class="about-section alt">
  <div class="container">
    <div class="section-divider">
      <span class="sd-pill"><i class="fa-solid fa-handshake"></i> Collaborators</span>
      <h2>Research Collaborators</h2>
    </div>
    <div class="members-grid">
      <?php
      $col = elhadi_team_query( 'collaborator' );
      if ( $col->have_posts() ) :
        while ( $col->have_posts() ) :
          $col->the_post();
          elhadi_member_card();
        endwhile;
        wp_reset_postdata();
      else :
        echo '<p class="team-empty">Aún no hay colaboradores. Agrégalos en <strong>Equipo → Añadir nuevo</strong> (Grupo: Collaborators).</p>';
      endif;
      ?>
    </div>
  </div>
</section>

<!-- PREVIOUS MEMBERS -->
<section class="about-section">
  <div class="container">
    <div class="section-divider">
      <span class="sd-pill"><i class="fa-solid fa-clock-rotate-left"></i> Previous</span>
      <h2>Previous Team Members &amp; Collaborators</h2>
    </div>
    <div class="past-grid">
      <?php
      $past = elhadi_team_query( 'past' );
      if ( $past->have_posts() ) :
        while ( $past->have_posts() ) :
          $past->the_post();
          $bio  = get_field( 'team_bio' );
          $tags = get_field( 'team_tags' );
          $meta = trim( implode( ' · ', array_filter( array( $bio, $tags ) ) ) );
          $has_detail = has_post_thumbnail() || $bio || '' !== trim( get_the_content() );
          $ptag = $has_detail ? 'a' : 'div';
          $phref = $has_detail ? ' href="' . esc_url( get_permalink() ) . '"' : '';
          echo '<' . $ptag . ' class="past-item"' . $phref . '>'; // phpcs:ignore
          ?>
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'pi-photo', 'alt' => esc_attr( get_the_title() ) ) ); ?>
            <?php else : ?>
              <div class="pi-icon"><i class="fa-solid fa-user"></i></div>
            <?php endif; ?>
            <div>
              <div class="pi-name"><?php the_title(); ?></div>
              <?php if ( $meta ) : ?><div class="pi-meta"><?php echo esc_html( $meta ); ?></div><?php endif; ?>
            </div>
          <?php
          echo '</' . $ptag . '>'; // phpcs:ignore
        endwhile;
        wp_reset_postdata();
      else :
        echo '<p class="team-empty">Aún no hay miembros anteriores. Agrégalos en <strong>Equipo → Añadir nuevo</strong> (Grupo: Previous Members).</p>';
      endif;
      ?>
    </div>
  </div>
</section>

<!-- LAB PHOTOS STRIP -->
<section class="about-section" style="background:var(--white); padding:48px 0;">
  <div class="container">
    <div class="text-center" style="margin-bottom:36px;">
      <span class="eyebrow">Our Space</span>
      <h2 class="section-title">Inside the Laboratory</h2>
    </div>
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px;">
      <div style="border-radius:var(--r-lg); overflow:hidden; height:200px;">
        <img src="<?php echo esc_url( $up ); ?>/2022/07/imagen1.jpg" onerror="this.parentElement.style.background='var(--green-l)'; this.remove();" style="width:100%;height:100%;object-fit:cover;" alt="Lab" />
      </div>
      <div style="border-radius:var(--r-lg); overflow:hidden; height:200px;">
        <img src="<?php echo esc_url( $up ); ?>/2022/07/imagen2.jpg" onerror="this.parentElement.style.background='var(--blue-l)'; this.remove();" style="width:100%;height:100%;object-fit:cover;" alt="Lab" />
      </div>
      <div style="border-radius:var(--r-lg); overflow:hidden; height:200px;">
        <img src="<?php echo esc_url( $up ); ?>/2022/07/imagen3.jpg" onerror="this.parentElement.style.background='var(--green-l)'; this.remove();" style="width:100%;height:100%;object-fit:cover;" alt="Lab" />
      </div>
      <div style="border-radius:var(--r-lg); overflow:hidden; height:200px;">
        <img src="<?php echo esc_url( $up ); ?>/2022/07/fao-doc.jpg" onerror="this.parentElement.style.background='var(--blue-l)'; this.remove();" style="width:100%;height:100%;object-fit:cover;" alt="FAO Meeting" />
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
