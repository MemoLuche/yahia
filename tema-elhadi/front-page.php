<?php
/**
 * Portada (Home). Se usa automáticamente cuando en
 * Ajustes > Lectura se elige "una página estática" como portada.
 *
 * Las imágenes apuntan a la Biblioteca de medios ($up). Para que se vean,
 * copia la carpeta wp-content/uploads del sitio original al nuevo,
 * o sube las imágenes y ajusta las rutas.
 */
get_header();
$up = wp_get_upload_dir()['baseurl'];
?>

<!-- HERO -->
<section id="hero">
  <div class="hero-bubbles"></div>
  <div class="container hero-inner">

    <div class="hero-text">
      <span class="hero-welcome">Welcome to Elhadi Yahia Website</span>

      <h1 class="hero-title hero-title-script">Research &amp; Development</h1>

      <p class="hero-desc">On Food and Nutrition</p>

      <div class="hero-actions">
        <a href="#focus" class="btn btn-blue">
          <i class="fa-solid fa-seedling"></i> What We Study
        </a>
        <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn btn-outline">
          Meet the Team
        </a>
      </div>

    </div>

  </div>
</section>

<!-- STATS STRIP -->
<section id="strip">
  <div class="container">
    <div class="strip-inner">
      <div class="strip-item">
        <span class="strip-num">40+</span>
        <span class="strip-lbl">Years of Experience</span>
      </div>
      <div class="strip-item">
        <span class="strip-num"><?php echo (int) elhadi_pub_total(); ?></span>
        <span class="strip-lbl">Publications</span>
      </div>
    </div>
  </div>
</section>

<!-- WELCOME / ABOUT -->
<section id="welcome" class="section-pad">
  <div class="container welcome-grid">

    <div class="welcome-img-wrap">
      <img class="welcome-img"
           src="<?php echo esc_url( $up ); ?>/2022/02/Picture121.png"
           onerror="this.src='<?php echo esc_url( $up ); ?>/2022/07/imagen47.jpg'; this.onerror=null;"
           alt="Fresh fruits" />
      <div class="welcome-img-badge">
        <div style="font-family:var(--font-head); font-weight:900; font-size:1.6rem; line-height:1;">40+</div>
        <div style="font-size:.7rem; font-weight:600; opacity:.9; margin-top:2px;">Years<br/>of Passion</div>
      </div>
    </div>

    <div>
      <span class="eyebrow">About Us</span>
      <h2 class="section-title">Passionate About <span style="color:var(--green)">Food</span>, Committed to People</h2>
      <p class="section-sub" style="margin-bottom:32px;">
        Dr. Elhadi Yahia has dedicated his life to making sufficient, nutritious, and safe food
        available for all. His work — carried out with academic and research institutions, food
        producers and distributors, governments and international organizations in more than
        90 countries — spans tropical fruits, vegetables, and postharvest technologies, to make
        food systems smarter, safer, and more sustainable.
      </p>

      <div style="display:flex; gap:32px; flex-wrap:wrap; margin-bottom:36px;">
        <div>
          <div style="font-family:var(--font-head); font-size:1.5rem; font-weight:700; color:var(--green);"><?php echo (int) elhadi_pub_count( 'books' ); ?></div>
          <div style="font-size:.82rem; color:var(--muted); font-weight:500;">Books Authored</div>
        </div>
        <div>
          <div style="font-family:var(--font-head); font-size:1.5rem; font-weight:700; color:var(--blue);"><?php echo (int) elhadi_pub_count( 'chapters' ); ?></div>
          <div style="font-size:.82rem; color:var(--muted); font-weight:500;">Book Chapters</div>
        </div>
        <div>
          <div style="font-family:var(--font-head); font-size:1.5rem; font-weight:700; color:var(--orange);"><?php echo (int) elhadi_pub_count( 'articles' ); ?></div>
          <div style="font-size:.82rem; color:var(--muted); font-weight:500;">Scientific Articles</div>
        </div>
      </div>

      <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn btn-green">
        Learn More About Us <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

  </div>
</section>

<!-- WHAT WE STUDY -->
<section id="focus" class="section-pad" style="background:var(--bg);">
  <div class="container">
    <div class="text-center" style="margin-bottom:52px;">
      <span class="eyebrow">Our Work</span>
      <h2 class="section-title">What We Study</h2>
      <p class="section-sub c">
        Our research involves basic and applied research ranging from the identification of food
        components, especially phytochemicals, of importance to human nutrition and health, their
        contribution and effects on certain illnesses, their changes in different foods and during
        different stages and handling practices, different mechanisms of deterioration, and develops
        means and techniques to reduce losses and waste and preserve quality and safety.
      </p>
    </div>

    <div class="focus-grid">

      <div class="focus-card blue">
        <div class="focus-icon"><i class="fa-solid fa-apple-whole"></i></div>
        <h3 class="focus-title">Postharvest Technology</h3>
        <p class="focus-text">
          Investigating the mechanisms and developing the techniques needed to keep food,
          especially perishable, nutritious and safe for the longest period of time after harvest.
        </p>
        <a href="<?php echo esc_url( home_url( '/publications/' ) ); ?>" class="focus-link">Explore publications <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="focus-card green">
        <div class="focus-icon"><i class="fa-solid fa-leaf"></i></div>
        <h3 class="focus-title">Food Quality &amp; Nutrition</h3>
        <p class="focus-text">
          We investigate the different components of food quality, including nutritional components,
          and the mechanisms and factors that contribute to their preservation and deterioration, to
          maintain foods at the highest possible quality for the longest period of time after harvest.
        </p>
        <a href="<?php echo esc_url( home_url( '/publications/' ) ); ?>" class="focus-link">Explore publications <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="focus-card orange">
        <div class="focus-icon"><i class="fa-solid fa-temperature-low"></i></div>
        <h3 class="focus-title">Cold Chain</h3>
        <p class="focus-text">
          Temperature control is the most important technique for the preservation of foods, to
          reduce losses and waste, and maintain quality and safety. We investigate and promote the
          different components of the cold chain — pre-cooling, refrigerated storage and transport —
          and the techniques that complement it, such as modified and controlled atmospheres, among
          several others.
        </p>
        <a href="<?php echo esc_url( home_url( '/publications/' ) ); ?>" class="focus-link">Explore publications <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="focus-card purple">
        <div class="focus-icon"><i class="fa-solid fa-globe-americas"></i></div>
        <h3 class="focus-title">Perishable Foods</h3>
        <p class="focus-text">
          Perishable foods, especially fresh fruits and vegetables, are essential for human nutrition
          and health because of the great number of phytochemicals they contain, known to help prevent
          various diseases. Yet their losses and waste are very high — higher than all other types of
          food — and still require extensive research and development. We work with diverse perishables
          in many countries across America, Africa, Asia and Europe.
        </p>
        <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>" class="focus-link">See our gallery <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="focus-card teal">
        <div class="focus-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <h3 class="focus-title">Food Safety</h3>
        <p class="focus-text">
          We investigate and develop means to prevent the different sources of food contamination,
          including microbial, to ensure good quality and safety.
        </p>
        <a href="<?php echo esc_url( home_url( '/publications/' ) ); ?>" class="focus-link">Explore publications <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="focus-card yellow">
        <div class="focus-icon"><i class="fa-solid fa-wheat-awn"></i></div>
        <h3 class="focus-title">Reducing Food Waste</h3>
        <p class="focus-text">
          Roughly one-third of all food produced in the world is lost and wasted, and for perishable
          foods the losses are even higher. Our research and development activities tackle this very
          serious problem to help improve food security.
        </p>
        <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="focus-link">Read our news <i class="fa-solid fa-arrow-right"></i></a>
      </div>

    </div>
  </div>
</section>

<!-- PUBLICATIONS -->
<section id="pubs" class="section-pad" style="background:var(--white);">
  <div class="container">
    <div class="text-center" style="margin-bottom:52px;">
      <span class="eyebrow">Knowledge Shared</span>
      <h2 class="section-title">Our Publications</h2>
      <p class="section-sub c">
        Decades of research and development products are shared, including textbooks used in
        universities and institutions around the world, book chapters, scientific and technical
        publications, and articles in global scientific journals.
      </p>
    </div>

    <div class="pub-grid">
      <div class="pub-card pub-c1">
        <div class="pub-icon"><i class="fa-solid fa-book"></i></div>
        <div class="pub-num"><?php echo (int) elhadi_pub_count( 'books' ); ?></div>
        <div class="pub-label">Books</div>
        <p class="pub-desc">Comprehensive textbooks used in universities across 5 continents, including the landmark <em>Postharvest Physiology and Biochemistry of Fruits &amp; Vegetables</em>.</p>
        <a href="<?php echo esc_url( home_url( '/publications/#books' ) ); ?>" class="pub-link">View Books <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="pub-card pub-c2">
        <div class="pub-icon"><i class="fa-solid fa-bookmark"></i></div>
        <div class="pub-num"><?php echo (int) elhadi_pub_count( 'chapters' ); ?></div>
        <div class="pub-label">Book Chapters</div>
        <p class="pub-desc">In-depth contributions to specialized volumes on food science, postharvest biology, and tropical fruit handling.</p>
        <a href="<?php echo esc_url( home_url( '/publications/#chapters' ) ); ?>" class="pub-link">View Chapters <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="pub-card pub-c3">
        <div class="pub-icon"><i class="fa-solid fa-file-lines"></i></div>
        <div class="pub-num"><?php echo (int) elhadi_pub_count( 'articles' ); ?></div>
        <div class="pub-label">Refereed Articles</div>
        <p class="pub-desc">Peer-reviewed studies in leading international journals on food quality, safety, and postharvest technology.</p>
        <a href="<?php echo esc_url( home_url( '/publications/#articles' ) ); ?>" class="pub-link">View Articles <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="pub-card pub-c4">
        <div class="pub-icon"><i class="fa-solid fa-flask"></i></div>
        <div class="pub-num"><?php echo (int) elhadi_pub_count( 'technical' ); ?></div>
        <div class="pub-label">Technical Articles</div>
        <p class="pub-desc">Practical guides and technical reports for farmers, producers, and food industry professionals worldwide.</p>
        <a href="<?php echo esc_url( home_url( '/publications/#technical' ) ); ?>" class="pub-link">View Technical <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="pub-card pub-c5">
        <div class="pub-icon"><i class="fa-solid fa-align-left"></i></div>
        <div class="pub-num"><?php echo (int) elhadi_pub_count( 'abstracts' ); ?></div>
        <div class="pub-label">Abstracts</div>
        <p class="pub-desc">Conference presentations and research summaries from international events across more than 90 countries.</p>
        <a href="<?php echo esc_url( home_url( '/publications/#abstracts' ) ); ?>" class="pub-link">View Abstracts <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- NEWS -->
<section id="news" class="section-pad" style="background:var(--bg);">
  <div class="container">
    <div style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:48px;">
      <div>
        <span class="eyebrow">Stay Informed</span>
        <h2 class="section-title">Latest News</h2>
      </div>
      <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="btn btn-green">
        All News <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    <div class="news-grid">

      <div class="news-card">
        <div class="news-img">
          <img src="<?php echo esc_url( $up ); ?>/2022/07/fao-doc.jpg"
               style="width:100%;height:100%;object-fit:cover;"
               alt="FAO collaboration" />
          <span class="news-badge" style="background:var(--blue);">International</span>
        </div>
        <div class="news-body">
          <div class="news-date"><i class="fa-regular fa-calendar"></i> 2024</div>
          <h3 class="news-title">FAO Collaboration on Reducing Postharvest Losses in Developing Countries</h3>
          <p class="news-excerpt">
            Dr. Yahia joins the FAO panel to develop new strategies for reducing food loss
            across sub-Saharan Africa and Southeast Asia.
          </p>
          <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="news-link">Read more <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="news-card">
        <div class="news-img">
          <img src="<?php echo esc_url( $up ); ?>/2022/02/elhadiyahia-Postharvest-Physiology-and-Biochemistry-of-Fruits-and-Vegetables.jpg"
               style="width:100%;height:100%;object-fit:cover;object-position:top;"
               alt="Book publication" />
          <span class="news-badge" style="background:var(--green);">Publication</span>
        </div>
        <div class="news-body">
          <div class="news-date"><i class="fa-regular fa-calendar"></i> 2023</div>
          <h3 class="news-title">New Edition: "Postharvest Physiology and Biochemistry of Fruits &amp; Vegetables"</h3>
          <p class="news-excerpt">
            Updated and expanded edition of the landmark reference book, now used in
            universities across five continents.
          </p>
          <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="news-link">Read more <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="news-card">
        <div class="news-img">
          <img src="<?php echo esc_url( $up ); ?>/2022/07/imagen47.jpg"
               style="width:100%;height:100%;object-fit:cover;"
               alt="International event" />
          <span class="news-badge" style="background:var(--orange);">Event</span>
        </div>
        <div class="news-body">
          <div class="news-date"><i class="fa-regular fa-calendar"></i> 2023</div>
          <h3 class="news-title">International Symposium on Postharvest Quality of Tropical Fruits</h3>
          <p class="news-excerpt">
            Dr. Yahia presented the latest findings on mango postharvest handling and
            cold chain management before an international audience.
          </p>
          <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="news-link">Read more <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- VIEWS -->
<section id="views-preview" class="section-pad" style="background:var(--white);">
  <div class="container">
    <div style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:48px;">
      <div>
        <span class="eyebrow">Perspectives</span>
        <h2 class="section-title">Views &amp; Opinions</h2>
      </div>
      <a href="<?php echo esc_url( home_url( '/views/' ) ); ?>" class="btn btn-green">
        All Views <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    <div class="views-grid">

      <div class="view-card">
        <div class="view-card-top">
          <span class="view-type opinion">Opinion</span>
          <h3>Why Postharvest Losses Are a Global Food Security Crisis</h3>
          <p>More than one billion tons of food is lost every year after harvest — a human rights issue affecting millions of people worldwide.</p>
        </div>
        <div class="view-card-foot">
          <span class="view-author"><i class="fa-solid fa-user fa-fw" style="color:var(--green);"></i> Dr. Elhadi Yahia</span>
          <a href="<?php echo esc_url( home_url( '/views/' ) ); ?>" class="view-link">Read <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="view-card">
        <div class="view-card-top">
          <span class="view-type review">Commentary</span>
          <h3>The Nutritional Value of Fresh Fruits: What Happens After Harvest?</h3>
          <p>Vitamins, antioxidants, and phytochemicals change once a fruit is picked. Understanding this is key to delivering truly nutritious food.</p>
        </div>
        <div class="view-card-foot">
          <span class="view-author"><i class="fa-solid fa-user fa-fw" style="color:var(--green);"></i> Dr. Elhadi Yahia</span>
          <a href="<?php echo esc_url( home_url( '/views/' ) ); ?>" class="view-link">Read <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="view-card">
        <div class="view-card-top">
          <span class="view-type interview">Interview</span>
          <h3>Tropical Fruits and Their Untapped Potential for Global Markets</h3>
          <p>Mangoes, avocados, papayas — nutritious and in demand. Yet farmers often lose much of their crop due to poor postharvest handling.</p>
        </div>
        <div class="view-card-foot">
          <span class="view-author"><i class="fa-solid fa-user fa-fw" style="color:var(--green);"></i> Dr. Elhadi Yahia</span>
          <a href="<?php echo esc_url( home_url( '/views/' ) ); ?>" class="view-link">Read <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- GALLERY PREVIEW -->
<section id="gallery-preview" class="section-pad" style="background:var(--bg);">
  <div class="container">
    <div class="text-center" style="margin-bottom:40px;">
      <span class="eyebrow">Our Gallery</span>
      <h2 class="section-title">A Look at Our World</h2>
      <p class="section-sub c">Some of the foods we work with, our laboratory and its members over the years, and views from our city (Querétaro) and country (Mexico).</p>
    </div>

    <div class="gallery-grid">
      <?php
      // Selección variada (aleatoria) de fotos reales de la galería.
      $gallery_preview = new WP_Query(
        array(
          'post_type'      => 'gallery_item',
          'post_status'    => 'publish',
          'posts_per_page' => 7,
          'orderby'        => 'rand',
          'meta_query'     => array(
            array( 'key' => '_thumbnail_id', 'compare' => 'EXISTS' ),
          ),
        )
      );
      if ( $gallery_preview->have_posts() ) :
        $gi = 0;
        while ( $gallery_preview->have_posts() ) :
          $gallery_preview->the_post();
          $gi++;
          ?>
          <div class="gallery-thumb<?php echo ( 1 === $gi ) ? ' wide' : ''; ?>">
            <?php the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
          </div>
          <?php
        endwhile;
        wp_reset_postdata();
      else :
        // Respaldo si aún no hay fotos en la galería.
        ?>
        <div class="gallery-thumb wide">
          <img src="<?php echo esc_url( $up ); ?>/2022/07/Bodegon_de_frutas_1_by_RonnyGye.jpg" alt="Fresh fruits" />
        </div>
      <?php endif; ?>
    </div>

    <div class="gallery-cta">
      <a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>" class="btn btn-green">
        <i class="fa-solid fa-images"></i> View Full Gallery
      </a>
    </div>
  </div>
</section>

<!-- PARTNERS -->
<section id="partners" class="section-pad" style="background:var(--white);">
  <div class="container">
    <div class="text-center" style="margin-bottom:40px;">
      <span class="eyebrow">Institutions &amp; Partners</span>
      <h2 class="section-title">Who We Work With</h2>
      <p class="section-sub c">
        We are proud to collaborate with leading international organizations, universities, and
        research institutions dedicated to food and nutrition in Mexico and around the world.
      </p>
    </div>

    <div class="partners-row">
      <a class="partner-item" href="https://www.uaq.mx/" target="_blank" rel="noopener">
        <img src="<?php echo esc_url( $up ); ?>/2022/02/Logo_de_la_UAQ.png"
             alt="Universidad Autónoma de Querétaro" />
      </a>
      <a class="partner-item" href="https://www.fao.org/" target="_blank" rel="noopener">
        <img src="<?php echo esc_url( $up ); ?>/2022/02/Fao-logo.png"
             alt="FAO – Food and Agriculture Organization" />
      </a>
      <a class="partner-item" href="https://fcn.uaq.mx/" target="_blank" rel="noopener">
        <img src="<?php echo esc_url( $up ); ?>/2022/02/logo-facultada-naturales.png"
             alt="Facultad de Ciencias Naturales" />
      </a>
      <a class="partner-item partner-gcca" href="https://www.gcca.org/" target="_blank" rel="noopener">
        <img src="<?php echo esc_url( $up ); ?>/2026/07/gcca-logo@2x.png"
             alt="Global Cold Chain Alliance" />
        <span class="partner-caption">Global Cold Chain Alliance</span>
      </a>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="section-pad contact-section">
  <div class="container">
    <div class="contact-grid">

      <div class="contact-info">
        <span class="eyebrow" style="color:rgba(255,255,255,.7);">Get in Touch</span>
        <h2 class="section-title" style="color:#fff; margin-bottom:16px;">Let's Talk About Food</h2>
        <p style="color:rgba(255,255,255,.78); font-size:1rem; line-height:1.75; margin-bottom:36px;">
          Whether you're a student, a journalist, a farmer, or just curious about
          how food works — we love hearing from people who care about food.
          Reach out and let's start a conversation.
        </p>

        <div class="contact-detail">
          <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
          <div>
            <div class="contact-label">Location</div>
            <div class="contact-value">Facultad de Ciencias Naturales, UAQ<br />Querétaro, México</div>
          </div>
        </div>

        <div class="contact-detail">
          <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
          <div>
            <div class="contact-label">Email</div>
            <div class="contact-value">yahia@uaq.mx</div>
          </div>
        </div>

        <div class="contact-detail">
          <div class="contact-icon"><i class="fa-solid fa-globe"></i></div>
          <div>
            <div class="contact-label">Web</div>
            <div class="contact-value">elhadiyahia.net</div>
          </div>
        </div>
      </div>

      <div class="contact-form-wrap">
        <h3 style="font-family:var(--font-head); font-weight:800; font-size:1.3rem; color:var(--dark); margin-bottom:24px;">
          Send a Message
        </h3>
        <div class="contact-form">
          <?php echo do_shortcode( '[contact-form-7 id="82d1c3d" title="FORMCONTACTOHOMEPAGE"]' ); ?>
        </div>
      </div>

    </div>
  </div>
</section>

<?php get_footer(); ?>
