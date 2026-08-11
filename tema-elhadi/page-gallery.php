<?php
/**
 * Plantilla dinámica de la página "Gallery" (slug: gallery).
 * Mosaico masonry + filtro por categoría + lightbox con navegación.
 * Fotos = CPT "gallery_item" (imagen destacada = foto, título = leyenda opcional),
 * categoría = taxonomía "gallery_cat".
 */
get_header();
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

  .g-filters{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-bottom:38px;}
  .g-chip{display:inline-flex;align-items:center;gap:7px;padding:8px 18px;border-radius:50px;border:1.5px solid var(--border);font-size:.85rem;font-weight:600;color:var(--muted);cursor:pointer;background:#fff;transition:all .2s;}
  .g-chip .n{font-size:.72rem;background:var(--green-l);color:var(--green);padding:1px 8px;border-radius:50px;transition:all .2s;}
  .g-chip:hover{border-color:var(--green);color:var(--green);}
  .g-chip.active{background:var(--green);color:#fff;border-color:var(--green);}
  .g-chip.active .n{background:rgba(255,255,255,.25);color:#fff;}

  .g-masonry{columns:4 260px;column-gap:14px;}
  @media(max-width:1100px){.g-masonry{columns:3 220px;}}
  @media(max-width:700px){.g-masonry{columns:2 150px;column-gap:10px;}}
  .g-item{break-inside:avoid;margin-bottom:14px;position:relative;border-radius:14px;overflow:hidden;cursor:zoom-in;display:block;background:var(--green-l);box-shadow:0 1px 3px rgba(0,0,0,.06);transition:box-shadow .28s,transform .28s;}
  .g-item:hover{box-shadow:0 14px 34px rgba(10,45,18,.20);transform:translateY(-3px);}
  .g-item img{width:100%;display:block;transition:transform .55s ease;}
  .g-item:hover img{transform:scale(1.06);}
  .g-item-ov{position:absolute;inset:0;background:linear-gradient(to top,rgba(10,45,18,.72),transparent 55%);opacity:0;transition:opacity .3s;display:flex;align-items:flex-end;padding:14px;}
  .g-item:hover .g-item-ov{opacity:1;}
  .g-cap{color:#fff;font-size:.85rem;font-weight:500;line-height:1.35;}
  .g-zoom{position:absolute;top:12px;right:12px;width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,.92);color:var(--green);display:flex;align-items:center;justify-content:center;font-size:.8rem;opacity:0;transform:scale(.7) rotate(-15deg);transition:all .3s;box-shadow:var(--shadow);}
  .g-item:hover .g-zoom{opacity:1;transform:scale(1) rotate(0);}
  .g-empty{text-align:center;padding:60px 0;color:var(--muted);}

  /* Lightbox */
  #lb{position:fixed;inset:0;background:rgba(8,20,12,.94);z-index:9999;display:none;align-items:center;justify-content:center;}
  #lb.open{display:flex;}
  #lb figure{margin:0;max-width:92vw;max-height:90vh;display:flex;flex-direction:column;align-items:center;gap:14px;animation:lbin .28s ease;}
  @keyframes lbin{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
  #lb img{max-width:92vw;max-height:82vh;object-fit:contain;border-radius:10px;box-shadow:0 20px 60px rgba(0,0,0,.5);}
  #lb figcaption{color:rgba(255,255,255,.9);font-size:.92rem;text-align:center;min-height:1em;}
  .lb-btn{position:absolute;background:rgba(255,255,255,.1);color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:50%;transition:background .2s;}
  .lb-btn:hover{background:var(--orange);}
  #lb-close{top:20px;right:24px;width:44px;height:44px;font-size:1.3rem;}
  #lb-prev,#lb-next{top:50%;transform:translateY(-50%);width:52px;height:52px;font-size:1.2rem;}
  #lb-prev{left:20px;} #lb-next{right:20px;}
  #lb-count{position:absolute;bottom:22px;left:50%;transform:translateX(-50%);color:rgba(255,255,255,.7);font-size:.82rem;letter-spacing:.05em;background:rgba(0,0,0,.35);padding:5px 14px;border-radius:50px;}
  @media(max-width:600px){#lb-prev,#lb-next{width:42px;height:42px;}#lb-prev{left:8px;}#lb-next{right:8px;}}
</style>

<div class="page-hero">
  <div class="page-hero-bg"></div>
  <div class="container page-hero-inner">
    <div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>/</span><span style="color:rgba(255,255,255,.8)">Gallery</span></div>
    <h1>Gallery</h1>
    <p>A look at our world — the laboratory, the foods we study, our city, and the people behind the research.</p>
  </div>
</div>

<section class="section-pad" style="background:var(--bg);">
  <div class="container">
    <?php
    $gq = new WP_Query(
      array(
        'post_type'      => 'gallery_item',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
      )
    );

    if ( ! $gq->have_posts() ) :
      ?>
      <p class="g-empty">Aún no hay fotos. Agrégalas en <strong>Gallery → Añadir foto</strong> (imagen destacada + categoría).</p>
    <?php else : ?>

      <?php
      $cats  = get_terms( array( 'taxonomy' => 'gallery_cat', 'hide_empty' => true ) );
      $total = (int) $gq->found_posts;
      ?>
      <?php if ( ! is_wp_error( $cats ) && count( $cats ) > 1 ) : ?>
        <div class="g-filters">
          <span class="g-chip active" data-cat="all">All <span class="n"><?php echo $total; ?></span></span>
          <?php foreach ( $cats as $c ) : ?>
            <span class="g-chip" data-cat="<?php echo esc_attr( $c->slug ); ?>"><?php echo esc_html( $c->name ); ?> <span class="n"><?php echo (int) $c->count; ?></span></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="g-masonry">
        <?php
        while ( $gq->have_posts() ) :
          $gq->the_post();
          if ( ! has_post_thumbnail() ) {
            continue;
          }
          $full  = get_the_post_thumbnail_url( get_the_ID(), 'large' );
          $thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium' ) ?: $full;
          $terms = get_the_terms( get_the_ID(), 'gallery_cat' );
          $slugs = ( $terms && ! is_wp_error( $terms ) ) ? implode( ' ', wp_list_pluck( $terms, 'slug' ) ) : '';
          $cap   = get_the_title();
          ?>
          <div class="g-item" data-cat="<?php echo esc_attr( $slugs ); ?>" data-full="<?php echo esc_url( $full ); ?>" data-cap="<?php echo esc_attr( $cap ); ?>">
            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $cap ); ?>" loading="lazy" />
            <span class="g-zoom"><i class="fas fa-expand"></i></span>
            <?php if ( $cap ) : ?><div class="g-item-ov"><span class="g-cap"><?php echo esc_html( $cap ); ?></span></div><?php endif; ?>
          </div>
        <?php endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php endif; ?>
  </div>
</section>

<!-- Lightbox con navegación -->
<div id="lb">
  <button class="lb-btn" id="lb-close" aria-label="Cerrar"><i class="fas fa-times"></i></button>
  <button class="lb-btn" id="lb-prev" aria-label="Anterior"><i class="fas fa-chevron-left"></i></button>
  <button class="lb-btn" id="lb-next" aria-label="Siguiente"><i class="fas fa-chevron-right"></i></button>
  <figure><img id="lb-img" src="" alt="" /><figcaption id="lb-cap"></figcaption></figure>
  <div id="lb-count"></div>
</div>

<script>
(function(){
  var items = Array.prototype.slice.call(document.querySelectorAll('.g-item'));
  var chips = document.querySelectorAll('.g-chip');
  var lb = document.getElementById('lb');
  var lbImg = document.getElementById('lb-img');
  var lbCap = document.getElementById('lb-cap');
  var lbCount = document.getElementById('lb-count');
  var visible = items.slice();   // items actualmente visibles (según filtro)
  var idx = 0;

  // ── Filtro ──
  chips.forEach(function(chip){
    chip.addEventListener('click', function(){
      chips.forEach(function(c){ c.classList.remove('active'); });
      chip.classList.add('active');
      var cat = chip.dataset.cat;
      items.forEach(function(it){
        var cats = (it.dataset.cat || '').split(' ');
        it.style.display = (cat === 'all' || cats.indexOf(cat) !== -1) ? '' : 'none';
      });
    });
  });

  // ── Lightbox ──
  function show(i){
    if (!visible.length) return;
    idx = (i + visible.length) % visible.length;
    var el = visible[idx];
    lbImg.src = el.dataset.full;
    lbCap.textContent = el.dataset.cap || '';
    lbCount.textContent = (idx + 1) + ' / ' + visible.length;
  }
  function open(el){
    visible = items.filter(function(it){ return it.style.display !== 'none'; });
    show(visible.indexOf(el));
    lb.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function close(){ lb.classList.remove('open'); lbImg.src=''; document.body.style.overflow=''; }

  items.forEach(function(it){ it.addEventListener('click', function(){ open(it); }); });
  document.getElementById('lb-close').addEventListener('click', close);
  document.getElementById('lb-prev').addEventListener('click', function(e){ e.stopPropagation(); show(idx-1); });
  document.getElementById('lb-next').addEventListener('click', function(e){ e.stopPropagation(); show(idx+1); });
  lb.addEventListener('click', function(e){ if (e.target === lb) close(); });
  document.addEventListener('keydown', function(e){
    if (!lb.classList.contains('open')) return;
    if (e.key === 'Escape') close();
    else if (e.key === 'ArrowLeft') show(idx-1);
    else if (e.key === 'ArrowRight') show(idx+1);
  });
})();
</script>

<?php get_footer(); ?>
