<?php
/**
 * Cabecera: <head>, navbar y nav móvil.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- NAVBAR -->
<nav id="navbar">
	<div class="container nav-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo">
			<img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/logo-white.png' ) ); ?>"
				style="height:40px;width:auto;filter:brightness(10);"
				alt="<?php bloginfo( 'name' ); ?>" />
		</a>

		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-menu',
					'fallback_cb'    => 'elhadi_default_menu',
				)
			);
		} else {
			elhadi_default_menu();
		}
		?>

		<div class="nav-hamburger" id="hamburger" aria-label="Open menu">
			<span></span><span></span><span></span>
		</div>
	</div>
</nav>

<!-- NAV MÓVIL -->
<nav id="mobile-nav">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
	<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
	<a href="<?php echo esc_url( home_url( '/publications/' ) ); ?>">Publications</a>
	<a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">News</a>
	<a href="<?php echo esc_url( home_url( '/views/' ) ); ?>">Views</a>
	<a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">Gallery</a>
	<a href="<?php echo esc_url( home_url( '/links/' ) ); ?>">Links</a>
	<a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Contact</a>
</nav>
