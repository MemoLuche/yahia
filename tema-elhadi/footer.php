<?php
/**
 * Pie de página.
 */
?>

<!-- FOOTER -->
<footer id="footer">
	<div class="container">
		<div class="footer-grid">

			<div class="footer-brand">
				<div style="font-family:var(--font-head); font-weight:900; font-size:1.2rem; color:#fff; margin-bottom:12px; display:flex; align-items:center; gap:10px;">
					<i class="fa-solid fa-seedling" style="color:#FFD700;"></i> <?php bloginfo( 'name' ); ?>
				</div>
				<p class="footer-tagline">
					Food science for a better world — keeping food fresh, nutritious,
					and available for everyone, everywhere.
				</p>
				<div class="footer-socials">
					<a href="#" aria-label="ResearchGate"><i class="fa-brands fa-researchgate"></i></a>
					<a href="#" aria-label="Google Scholar"><i class="fa-brands fa-google"></i></a>
					<a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
					<a href="#" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
				</div>
			</div>

			<div class="footer-col">
				<h4 class="footer-heading">Navigation</h4>
				<ul class="footer-links">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
					<li><a href="<?php echo esc_url( home_url( '/publications/' ) ); ?>">Publications</a></li>
					<li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">News</a></li>
					<li><a href="<?php echo esc_url( home_url( '/views/' ) ); ?>">Views</a></li>
					<li><a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">Gallery</a></li>
					<li><a href="<?php echo esc_url( home_url( '/links/' ) ); ?>">Links</a></li>
				</ul>
			</div>

			<div class="footer-col">
				<h4 class="footer-heading">Publications</h4>
				<ul class="footer-links">
					<li><a href="<?php echo esc_url( home_url( '/publications/#books' ) ); ?>">Books</a></li>
					<li><a href="<?php echo esc_url( home_url( '/publications/#chapters' ) ); ?>">Book Chapters</a></li>
					<li><a href="<?php echo esc_url( home_url( '/publications/#articles' ) ); ?>">Refereed Articles</a></li>
					<li><a href="<?php echo esc_url( home_url( '/publications/#technical' ) ); ?>">Technical Articles</a></li>
					<li><a href="<?php echo esc_url( home_url( '/publications/#abstracts' ) ); ?>">Abstracts</a></li>
				</ul>
			</div>

			<div class="footer-col">
				<h4 class="footer-heading">Contact</h4>
				<ul class="footer-links">
					<li><i class="fa-solid fa-location-dot fa-fw" style="color:var(--blue);"></i> Querétaro, México</li>
					<li><i class="fa-solid fa-envelope fa-fw" style="color:var(--blue);"></i> yahia@uaq.mx</li>
					<li><i class="fa-solid fa-globe fa-fw" style="color:var(--blue);"></i> elhadiyahia.net</li>
				</ul>
			</div>

		</div>

		<div class="footer-bottom">
			<span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</span>
			<span>Universidad Autónoma de Querétaro &middot; Faculty of Natural Sciences</span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
