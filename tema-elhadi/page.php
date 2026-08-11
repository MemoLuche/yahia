<?php
/**
 * Template para páginas (About, Links, Views, etc.).
 * El contenido de cada página se edita desde el panel de WordPress.
 */
get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

get_footer();
