<?php
/**
 * Template de respaldo (fallback) que WordPress exige en todo tema.
 * Se usa cuando no hay un template más específico.
 */
get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
else :
	echo '<div class="container" style="padding:120px 0;"><p>No hay contenido todavía.</p></div>';
endif;

get_footer();
