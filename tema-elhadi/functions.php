<?php
/**
 * Funciones del tema Elhadi Yahia.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Sin acceso directo.
}

/**
 * Soportes y registros del tema.
 */
function elhadi_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Menú principal', 'elhadi' ),
			'footer'  => __( 'Menú del pie', 'elhadi' ),
		)
	);
}
add_action( 'after_setup_theme', 'elhadi_setup' );

/**
 * Encolar estilos y fuentes.
 */
function elhadi_assets() {
	// Fuentes Google.
	wp_enqueue_style(
		'elhadi-fonts',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Caveat:wght@600;700&display=swap',
		array(),
		null
	);

	// Font Awesome.
	wp_enqueue_style(
		'font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
		array(),
		'6.5.0'
	);

	// Hoja de estilos principal del rediseño.
	wp_enqueue_style(
		'elhadi-main',
		get_theme_file_uri( '/assets/styles-v2.css' ),
		array(),
		filemtime( get_theme_file_path( '/assets/styles-v2.css' ) )
	);

	// JavaScript del tema (menú móvil, scroll suave, formulario).
	wp_enqueue_script(
		'elhadi-main',
		get_theme_file_uri( '/assets/main.js' ),
		array(),
		filemtime( get_theme_file_path( '/assets/main.js' ) ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'elhadi_assets' );

/**
 * Menú principal por defecto (cuando aún no se asigna un menú en Apariencia > Menús).
 * Reproduce la estructura del rediseño, incluido el submenú de Publications.
 */
function elhadi_default_menu() {
	$home = home_url( '/' );
	?>
	<ul class="nav-menu">
		<li><a href="<?php echo esc_url( $home ); ?>"<?php echo is_front_page() ? ' class="active"' : ''; ?>>Home</a></li>
		<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
		<li class="has-sub">
			<a href="<?php echo esc_url( home_url( '/publications/' ) ); ?>">Publications</a>
			<ul class="sub-menu">
				<li><a href="<?php echo esc_url( home_url( '/publications/#books' ) ); ?>"><i class="fa-solid fa-book fa-fw"></i> Books</a></li>
				<li><a href="<?php echo esc_url( home_url( '/publications/#chapters' ) ); ?>"><i class="fa-solid fa-bookmark fa-fw"></i> Book Chapters</a></li>
				<li><a href="<?php echo esc_url( home_url( '/publications/#articles' ) ); ?>"><i class="fa-solid fa-file-alt fa-fw"></i> Refereed Articles</a></li>
				<li><a href="<?php echo esc_url( home_url( '/publications/#technical' ) ); ?>"><i class="fa-solid fa-flask fa-fw"></i> Technical Articles</a></li>
				<li><a href="<?php echo esc_url( home_url( '/publications/#abstracts' ) ); ?>"><i class="fa-solid fa-align-left fa-fw"></i> Abstracts</a></li>
			</ul>
		</li>
		<li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">News</a></li>
		<li><a href="<?php echo esc_url( home_url( '/views/' ) ); ?>">Views</a></li>
		<li><a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">Gallery</a></li>
		<li><a href="<?php echo esc_url( home_url( '/links/' ) ); ?>">Links</a></li>
		<li><a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="nav-cta">Contact</a></li>
	</ul>
	<?php
}

/* =============================================================
   CONTENIDO DINÁMICO
   ============================================================= */

/**
 * CPT "Views" — opiniones, comentarios, editoriales, entrevistas.
 */
function elhadi_register_views_cpt() {
	register_post_type(
		'view',
		array(
			'labels'       => array(
				'name'          => 'Views',
				'singular_name' => 'View',
				'add_new'       => 'Añadir nueva',
				'add_new_item'  => 'Añadir nueva View',
				'edit_item'     => 'Editar View',
				'new_item'      => 'Nueva View',
				'view_item'     => 'Ver View',
				'search_items'  => 'Buscar Views',
				'not_found'     => 'No hay Views todavía',
				'menu_name'     => 'Views',
			),
			'public'       => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-format-quote',
			'menu_position'=> 6,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'rewrite'      => array( 'slug' => 'view' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'elhadi_register_views_cpt' );

/**
 * Campos ACF para las Views (Tipo, Autor, Tiempo de lectura).
 * Requiere el plugin Advanced Custom Fields activo.
 */
function elhadi_views_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	acf_add_local_field_group(
		array(
			'key'      => 'group_view_fields',
			'title'    => 'Detalles de la View',
			'fields'   => array(
				array(
					'key'           => 'field_view_type',
					'label'         => 'Tipo',
					'name'          => 'view_type',
					'type'          => 'select',
					'instructions'  => 'Define el color y la etiqueta de la tarjeta.',
					'choices'       => array(
						'opinion'    => 'Opinion',
						'commentary' => 'Commentary',
						'editorial'  => 'Editorial',
						'interview'  => 'Interview',
					),
					'default_value' => 'opinion',
					'required'      => 1,
				),
				array(
					'key'           => 'field_view_author',
					'label'         => 'Autor(es)',
					'name'          => 'view_author',
					'type'          => 'text',
					'default_value' => 'Dr. Elhadi M. Yahia',
				),
				array(
					'key'         => 'field_view_readtime',
					'label'       => 'Tiempo de lectura',
					'name'        => 'view_readtime',
					'type'        => 'text',
					'placeholder' => '5 min read',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'view',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'elhadi_views_acf_fields' );

/**
 * Icono FontAwesome según el tipo de View.
 */
function elhadi_view_icon( $type ) {
	$map = array(
		'opinion'    => 'fa-comment-dots',
		'commentary' => 'fa-pen-nib',
		'editorial'  => 'fa-newspaper',
		'interview'  => 'fa-microphone',
	);
	return isset( $map[ $type ] ) ? $map[ $type ] : 'fa-pen-nib';
}

/**
 * Configuración de los 5 tipos de Publicación (orden, etiqueta, icono, prefijo, layout).
 * Se usa tanto para crear los términos como para pintar la plantilla.
 */
function elhadi_pub_types() {
	return array(
		'books'     => array( 'label' => 'Books',            'icon' => 'fa-book',           'prefix' => '',         'layout' => 'books' ),
		'chapters'  => array( 'label' => 'Book Chapters',    'icon' => 'fa-book-open',      'prefix' => 'Chapter',  'layout' => 'list' ),
		'articles'  => array( 'label' => 'Refereed Articles','icon' => 'fa-bookmark',       'prefix' => 'Article',  'layout' => 'list' ),
		'technical' => array( 'label' => 'Technical Articles','icon' => 'fa-file-pdf',      'prefix' => 'Tech',     'layout' => 'list' ),
		'abstracts' => array( 'label' => 'Abstracts',        'icon' => 'fa-file-signature', 'prefix' => 'Abstract', 'layout' => 'list' ),
	);
}

/**
 * Conteo real de publicaciones de un tipo (por el término de pub_type).
 */
function elhadi_pub_count( $slug ) {
	$t = get_term_by( 'slug', $slug, 'pub_type' );
	return ( $t && ! is_wp_error( $t ) ) ? (int) $t->count : 0;
}

/**
 * Conteo total real de publicaciones (suma de los 5 tipos).
 */
function elhadi_pub_total() {
	$n = 0;
	foreach ( array_keys( elhadi_pub_types() ) as $slug ) {
		$n += elhadi_pub_count( $slug );
	}
	return $n;
}

/**
 * CPT "Publications" + taxonomía "pub_type" (con sus 5 términos creados solos).
 */
function elhadi_register_publications_cpt() {
	register_post_type(
		'publication',
		array(
			'labels'       => array(
				'name'          => 'Publications',
				'singular_name' => 'Publication',
				'add_new'       => 'Añadir nueva',
				'add_new_item'  => 'Añadir nueva publicación',
				'edit_item'     => 'Editar publicación',
				'new_item'      => 'Nueva publicación',
				'search_items'  => 'Buscar publicaciones',
				'not_found'     => 'No hay publicaciones todavía',
				'menu_name'     => 'Publications',
			),
			'public'       => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-book-alt',
			'menu_position'=> 7,
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'      => array( 'slug' => 'publication' ),
			'show_in_rest' => true,
		)
	);

	register_taxonomy(
		'pub_type',
		'publication',
		array(
			'labels'            => array(
				'name'          => 'Tipos',
				'singular_name' => 'Tipo',
				'menu_name'     => 'Tipos',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'pub-type' ),
		)
	);

	// Crear los 5 términos si no existen (para que los slugs coincidan con el menú).
	foreach ( elhadi_pub_types() as $slug => $cfg ) {
		if ( ! term_exists( $slug, 'pub_type' ) ) {
			wp_insert_term( $cfg['label'], 'pub_type', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'init', 'elhadi_register_publications_cpt' );

/**
 * Campos ACF para las Publicaciones.
 */
function elhadi_publications_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	acf_add_local_field_group(
		array(
			'key'      => 'group_publication_fields',
			'title'    => 'Detalles de la publicación',
			'fields'   => array(
				array(
					'key'   => 'field_pub_authors',
					'label' => 'Autores / Editores',
					'name'  => 'pub_authors',
					'type'  => 'text',
				),
				array(
					'key'          => 'field_pub_year',
					'label'        => 'Año',
					'name'         => 'pub_year',
					'type'         => 'text',
					'instructions' => 'Ej. 2019  ·  o "2018 (2nd Ed.)"',
				),
				array(
					'key'          => 'field_pub_source',
					'label'        => 'Fuente (revista / editorial)',
					'name'         => 'pub_source',
					'type'         => 'text',
					'instructions' => 'Ej. "Food Chemistry · Vol. 312, 2024"  o  "Woodhead Publishing"',
				),
				array(
					'key'          => 'field_pub_link',
					'label'        => 'Enlace (DOI / descarga)',
					'name'         => 'pub_link',
					'type'         => 'url',
					'instructions' => 'Opcional. Si lo pones, aparece un botón para abrirlo.',
				),
				array(
					'key'          => 'field_pub_note',
					'label'        => 'Etiqueta corta',
					'name'         => 'pub_note',
					'type'         => 'text',
					'instructions' => 'Opcional. Ej. "Open Access", "DOI Available", o un tema para libros como "Mango".',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'publication',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'elhadi_publications_acf_fields' );

/**
 * CPT "Equipo" — miembros del laboratorio y colaboradores.
 */
function elhadi_register_team_cpt() {
	register_post_type(
		'team_member',
		array(
			'labels'       => array(
				'name'          => 'Equipo',
				'singular_name' => 'Miembro',
				'add_new'       => 'Añadir nuevo',
				'add_new_item'  => 'Añadir miembro',
				'edit_item'     => 'Editar miembro',
				'new_item'      => 'Nuevo miembro',
				'search_items'  => 'Buscar miembros',
				'not_found'     => 'No hay miembros todavía',
				'menu_name'     => 'Equipo',
			),
			'public'       => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-groups',
			'menu_position'=> 8,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'rewrite'      => array( 'slug' => 'team' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'elhadi_register_team_cpt' );

/**
 * Al guardar un miembro del equipo sin "Orden" definido (menu_order = 0),
 * se le asigna automáticamente el siguiente número (el máximo actual + 1)
 * para que aparezca AL FINAL de su grupo, no al inicio. Si el editor pone
 * un "Orden" manual (>0), se respeta.
 */
function elhadi_team_member_default_order( $post_id, $post, $update ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}
	if ( 'auto-draft' === $post->post_status ) {
		return;
	}
	// Ya tiene un orden asignado manualmente: no tocar.
	if ( (int) $post->menu_order > 0 ) {
		return;
	}
	global $wpdb;
	$max = (int) $wpdb->get_var(
		"SELECT MAX(menu_order) FROM {$wpdb->posts} WHERE post_type = 'team_member'"
	);
	// Actualiza directo en la BD para no volver a disparar save_post.
	$wpdb->update( $wpdb->posts, array( 'menu_order' => $max + 1 ), array( 'ID' => $post_id ) );
	clean_post_cache( $post_id );
}
add_action( 'save_post_team_member', 'elhadi_team_member_default_order', 20, 3 );

/**
 * Campos ACF para los miembros del equipo.
 */
function elhadi_team_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	acf_add_local_field_group(
		array(
			'key'      => 'group_team_fields',
			'title'    => 'Datos del miembro',
			'fields'   => array(
				array(
					'key'           => 'field_team_group',
					'label'         => 'Grupo',
					'name'          => 'team_group',
					'type'          => 'select',
					'instructions'  => 'En qué sección aparece.',
					'choices'       => array(
						'lab'          => 'Lab Team',
						'collaborator' => 'Collaborators',
						'past'         => 'Previous Members',
					),
					'default_value' => 'lab',
					'required'      => 1,
				),
				array(
					'key'         => 'field_team_role',
					'label'       => 'Cargo / Rol',
					'name'        => 'team_role',
					'type'        => 'text',
					'placeholder' => 'Researcher',
				),
				array(
					'key'   => 'field_team_bio',
					'label' => 'Bio',
					'name'  => 'team_bio',
					'type'  => 'textarea',
					'rows'  => 4,
				),
				array(
					'key'          => 'field_team_tags',
					'label'        => 'Etiquetas (separadas por coma)',
					'name'         => 'team_tags',
					'type'         => 'text',
					'instructions' => 'Opcional. Ej. Phytochemicals, Quality',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'team_member',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'elhadi_team_acf_fields' );

/**
 * Configuración de los 4 tipos de recurso de la página Links.
 */
function elhadi_link_types() {
	return array(
		'websites' => array( 'label' => 'Web Sites',            'icon' => 'fa-globe' ),
		'videos'   => array( 'label' => 'Videos',               'icon' => 'fa-play-circle' ),
		'books'    => array( 'label' => 'Books of Interest',    'icon' => 'fa-book' ),
		'journals' => array( 'label' => 'Journals of Interest', 'icon' => 'fa-journal-whills' ),
	);
}

/**
 * CPT "Recursos (Links)" — sitios web, videos, libros y revistas de interés.
 */
function elhadi_register_links_cpt() {
	register_post_type(
		'link_resource',
		array(
			'labels'       => array(
				'name'          => 'Links (recursos)',
				'singular_name' => 'Recurso',
				'add_new'       => 'Añadir nuevo',
				'add_new_item'  => 'Añadir recurso',
				'edit_item'     => 'Editar recurso',
				'new_item'      => 'Nuevo recurso',
				'search_items'  => 'Buscar recursos',
				'not_found'     => 'No hay recursos todavía',
				'menu_name'     => 'Links (recursos)',
			),
			'public'       => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-admin-links',
			'menu_position'=> 9,
			'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
			'rewrite'      => array( 'slug' => 'link-resource' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'elhadi_register_links_cpt' );

/**
 * Campos ACF para los recursos de Links.
 */
function elhadi_links_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	acf_add_local_field_group(
		array(
			'key'      => 'group_link_fields',
			'title'    => 'Detalles del recurso',
			'fields'   => array(
				array(
					'key'           => 'field_link_type',
					'label'         => 'Tipo',
					'name'          => 'link_type',
					'type'          => 'select',
					'instructions'  => 'En qué pestaña aparece.',
					'choices'       => array(
						'websites' => 'Web Sites',
						'videos'   => 'Videos',
						'books'    => 'Books of Interest',
						'journals' => 'Journals of Interest',
					),
					'default_value' => 'websites',
					'required'      => 1,
				),
				array(
					'key'          => 'field_link_group',
					'label'        => 'Grupo / Categoría',
					'name'         => 'link_group',
					'type'         => 'text',
					'instructions' => 'Sub-sección dentro de la pestaña. Web: NGOs, Food Security… · Video: Master Classes, Clips… · Books: el año · Journals: opcional.',
				),
				array(
					'key'   => 'field_link_url',
					'label' => 'Enlace (URL)',
					'name'  => 'link_url',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_link_desc',
					'label' => 'Descripción',
					'name'  => 'link_desc',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'          => 'field_link_source',
					'label'        => 'Fuente',
					'name'         => 'link_source',
					'type'         => 'text',
					'instructions' => 'Web: dominio (ej. fao.org). Video: plataforma. Libro: autor/editorial. Revista: editorial · tema.',
				),
				array(
					'key'          => 'field_link_meta',
					'label'        => 'Dato extra',
					'name'         => 'link_meta',
					'type'         => 'text',
					'instructions' => 'Revista: "IF: 8.8". Video: "45 min · 2023". Libro: categoría (ej. "Nutrition").',
				),
				array(
					'key'          => 'field_link_icon',
					'label'        => 'Icono (solo Web Sites)',
					'name'         => 'link_icon',
					'type'         => 'text',
					'instructions' => 'Opcional. Clase FontAwesome, ej. "fas fa-globe-americas". Si se deja vacío usa uno por defecto.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'link_resource',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'elhadi_links_acf_fields' );

/**
 * CPT "Gallery" + taxonomía "gallery_cat" (con 5 categorías creadas solas).
 * No usa ACF: la foto es la imagen destacada, el título es la leyenda.
 */
function elhadi_register_gallery_cpt() {
	register_post_type(
		'gallery_item',
		array(
			'labels'       => array(
				'name'          => 'Gallery',
				'singular_name' => 'Foto',
				'add_new'       => 'Añadir foto',
				'add_new_item'  => 'Añadir foto',
				'edit_item'     => 'Editar foto',
				'new_item'      => 'Nueva foto',
				'search_items'  => 'Buscar fotos',
				'not_found'     => 'No hay fotos todavía',
				'menu_name'     => 'Gallery',
			),
			'public'       => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-format-gallery',
			'menu_position'=> 10,
			'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
			'rewrite'      => array( 'slug' => 'gallery-item' ),
			'show_in_rest' => true,
		)
	);

	register_taxonomy(
		'gallery_cat',
		'gallery_item',
		array(
			'labels'            => array(
				'name'          => 'Categorías',
				'singular_name' => 'Categoría',
				'menu_name'     => 'Categorías',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'gallery-cat' ),
		)
	);

	// Crear las categorías por defecto (una vez).
	$defaults = array(
		'lab'       => 'Laboratory',
		'foods'     => 'Foods',
		'queretaro' => 'Querétaro',
		'mexico'    => 'Mexico',
		'people'    => 'People',
	);
	foreach ( $defaults as $slug => $name ) {
		if ( ! term_exists( $slug, 'gallery_cat' ) ) {
			wp_insert_term( $name, 'gallery_cat', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'init', 'elhadi_register_gallery_cpt' );
