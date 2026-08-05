<?php 

define( 'BURGER_THEME_PATH',    get_template_directory() );
define( 'BURGER_THEME_URL',     get_template_directory_uri() );
define( 'BURGER_TITLE',         get_bloginfo('title') );
define( 'BURGER_URL',           get_bloginfo('url') );
define( 'BURGER_VERSION',       get_bloginfo('version') );

if ( function_exists('get_field') AND $options = get_fields( 'option' ) ) 
    define( 'BURGER_OPTIONS', $options );

require_once BURGER_THEME_PATH . '/inc/tools.php';
require_once BURGER_THEME_PATH . '/inc/setup.php';
require_once BURGER_THEME_PATH . '/inc/blocks.php';
require_once BURGER_THEME_PATH . '/inc/assets.php';
//require_once BURGER_THEME_PATH . '/inc/mail.php';

?>