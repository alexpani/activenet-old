<?php
/**
 * Active Net
 * @version 1.0
 * @date 02/08/2017
 * Based on Genesis Sample Child Theme
 *
 * @package Poppy Framework
 * @author  Active Net
 * @license GPL-2.0+
 * @link    http://www.active-net.it
 */

//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'activenet', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'activenet' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Active Net' );
define( 'CHILD_THEME_URL', 'http://www.active-net.it' );
define( 'CHILD_THEME_VERSION', '1.0' );

//* Start the WooCommerce Active Net Framework (only if WooCommerce is active)
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    include ('woocommerce/woocommerce.php');
}

//* Enqueue Scripts and Styles
add_action( 'wp_enqueue_scripts', 'activenet_enqueue_scripts_styles' );
function activenet_enqueue_scripts_styles() {

	//wp_enqueue_style( 'activenet-fonts', '//fonts.googleapis.com/css?family=Roboto:400,700,500|Montserrat:400,700|Poppins:400,500,700', array(), CHILD_THEME_VERSION );

	//wp_enqueue_style( 'dashicons' );

	//wp_enqueue_style( 'font-awesome', CHILD_URL . '/fonts/font-awesome-4.7.0/css/font-awesome.min.css' );

	wp_enqueue_script( 'activenet-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );
	$output = array(
		'mainMenu' => __( 'Menu', 'activenet' ),
		'subMenu'  => __( 'Menu', 'activenet' ),
	);
	wp_localize_script( 'activenet-responsive-menu', 'genesisSampleL10n', $output );

	wp_enqueue_style( 'custom-css-stylesheet', CHILD_URL . '/css/custom.css', array(), PARENT_THEME_VERSION );

}

//* Enqueue parallax script.
add_action( 'wp_enqueue_scripts', 'enqueue_parallax_script' );
function enqueue_parallax_script() {	

	if ( ! wp_is_mobile() ) {
		wp_enqueue_script( 'parallax-script', get_stylesheet_directory_uri() . '/js/parallax.js', array( 'jquery' ), '1.0.0' );
	}
}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

// Add support for custom logo.
add_theme_support( 'custom-logo', array(
	'width'       => 600,
	'height'      => 160,
	'flex-width' => true,
	'flex-height' => true,
) );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for after entry widget
//add_theme_support( 'genesis-after-entry-widget-area' );

//* Add support for column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add Image Sizes
//add_image_size( 'featured-image', 720, 400, TRUE );

//* Put the secondary navigation menu on topo
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_header', 'genesis_do_subnav', 5 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'activenet_secondary_menu_args' );
function activenet_secondary_menu_args( $args ) {
	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}
	$args['depth'] = 1;
	return $args;
}

//* Modify size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'activenet_author_box_gravatar' );
function activenet_author_box_gravatar( $size ) {
	return 90;
}

//* Modify size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'activenet_comments_gravatar' );
function activenet_comments_gravatar( $args ) {
	$args['avatar_size'] = 60;
	return $args;
}

//* Optimize automatic paragraph in WordPress
remove_filter('the_content', 'wpautop');
add_filter('the_content', 'wpautop', 12);

//* Add slider widget area
genesis_register_sidebar( array(
	'id'            => 'slider',
	'name'          => __( 'Slider', 'activenet' ),
	'description'   => __( 'Slider Area', 'activenet' ),
) );

add_action( 'genesis_after_header', 'slider_widget',20 );
	function slider_widget() {
	if ( is_front_page() )
		genesis_widget_area( 'slider', array(
			'before' => '<div class="slider widget-area"><div class="wrap">',
			'after' => '</div></div>',
	) );
}

//* Customize the footer credits
add_filter( 'genesis_footer_creds_text', 'an_footer_creds_text' );
function an_footer_creds_text() {
	echo '<p>©2017 Active Net Theme · P.IVA 0000000 · Web Design by <b>Active Net</b></p>';
}

//* Add custom header area
add_action( 'genesis_header', 'an_do_header' );
function an_do_header() {
	genesis_widget_area( 'header', array(
		'before'	=> '<div class="header widget-area">',
		'after'		=> '</div>',
	) );
}

//* Customize the Wordpress login page
add_action('login_head', 'an_custom_login_logo');
function an_custom_login_logo() {
	echo '<style type="text/css">
	h1 a {background-image: url('.get_bloginfo('stylesheet_directory').'/images/logo.png) !important; width: 250px !important; height: 50px !important; background-size: 250px 50px !important; }
	body.login {background: #0c3043;}
	p#backtoblog {display: none;}
	.login #nav a {color: #fff;}
	</style>';
}

add_filter( 'login_headerurl', 'an_login_logo_url' );
function an_login_logo_url() {
return get_bloginfo( 'url' );
}

add_filter( 'login_headertitle', 'an_login_logo_url_title' );
function an_login_logo_url_title() {
return 'Active Net';
}

//* Customize the entry info in the entry header
add_filter( 'genesis_post_info', 'an_post_info_filter' );
function an_post_info_filter($post_info) {
	$post_info = '<i class="fa fa-calendar" aria-hidden="true"></i> [post_date] | <i class="fa fa-pencil" aria-hidden="true"></i> [post_categories before=""] [post_tags before="Etichetta: "] | <i class="fa fa-user-o" aria-hidden="true"></i> <i>[post_author_posts_link]</i> [post_comments] [post_edit]';
	return $post_info;
}

//* Remove post meta
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

//* Customize Read More Text
add_filter( 'excerpt_more', 'child_read_more_link' );
add_filter( 'get_the_content_more_link', 'child_read_more_link' );
add_filter( 'the_content_more_link', 'child_read_more_link' );
function child_read_more_link() { 
return '<a class="more-link" title="Continua a leggere" href="' . get_permalink() . '" rel="nofollow">Continua...</a>';
}


//* Unregister site layouts
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

/**
 * Add an image inline in the site title element for the logo
 *
 * @param string $title Current markup of title.
 * @param string $inside Markup inside the title.
 * @param string $wrap Wrapping element for the title.
 *
 * @author @_AlphaBlossom
 * @author @_neilgee
 * @author @_JiveDig
 * @author @_srikat
 */

add_filter( 'genesis_seo_title', 'custom_header_inline_logo', 10, 3 );

function custom_header_inline_logo( $title, $inside, $wrap ) {
	// If the custom logo function and custom logo exist, set the logo image element inside the wrapping tags.
	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		$inside = sprintf( '<span class="screen-reader-text">%s</span>%s', esc_html( get_bloginfo( 'name' ) ), get_custom_logo() );
	} else {
		// If no custom logo, wrap around the site name.
		$inside	= sprintf( '<a href="%s">%s</a>', trailingslashit( home_url() ), esc_html( get_bloginfo( 'name' ) ) );
	}

	// Build the title.
	$title = genesis_markup( array(
		'open'    => sprintf( "<{$wrap} %s>", genesis_attr( 'site-title' ) ),
		'close'   => "</{$wrap}>",
		'content' => $inside,
		'context' => 'site-title',
		'echo'    => false,
		'params'  => array(
			'wrap' => $wrap,
		),
	) );

	return $title;
}

add_filter( 'genesis_attr_site-description', 'custom_add_site_description_class' );
/**
 * Add class for screen readers to site description.
 * This will keep the site description markup but will not have any visual presence on the page
 * This runs if there is a logo image set in the Customizer.
 *
 * @param array $attributes Current attributes.
 *
 * @author @_neilgee
 * @author @_srikat
 */
function custom_add_site_description_class( $attributes ) {
	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		$attributes['class'] .= ' screen-reader-text';
	}

	return $attributes;
}

/* Remove & reposition the breadcrumbs before product title
 * i.e. Show the breadcrumb in the single product pages only
 */
//remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
//add_action( 'woocommerce_single_product_summary', 'genesis_do_breadcrumbs',4 );

//* Customize breadcrumbs display
add_filter( 'genesis_breadcrumb_args', 'sp_breadcrumb_args' );
function sp_breadcrumb_args( $args ) {
	$args['home'] = 'Home';
	$args['sep'] = ' > ';
	$args['list_sep'] = ', '; // Genesis 1.5 and later
	$args['prefix'] = '<div class="breadcrumb">';
	$args['suffix'] = '</div>';
	$args['heirarchial_attachments'] = true; // Genesis 1.5 and later
	$args['heirarchial_categories'] = true; // Genesis 1.5 and later
	$args['display'] = true;
	$args['labels']['prefix'] = '';
	$args['labels']['author'] = '';
	$args['labels']['category'] = ''; // Genesis 1.6 and later
	$args['labels']['tag'] = '';
	$args['labels']['date'] = '';
	$args['labels']['search'] = 'Cerca per ';
	$args['labels']['tax'] = '';
	$args['labels']['post_type'] = '';
	$args['labels']['404'] = 'Non trovato: '; // Genesis 1.5 and later
return $args;
}

/* Scegliamo il tipo di header
*  Il default è logo-right
*/

//include_once( get_stylesheet_directory() . '/lib/header-logo-mid-right.php' );
include_once( get_stylesheet_directory() . '/lib/header-left-logo-right.php' );

//* Start the Active Net Extension Framework
//include ('activenet-extensions/extensions.php');




