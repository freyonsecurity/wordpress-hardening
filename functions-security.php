<?php


/* functions-security.php
 *
 * SECURITY SETTINGS
 *
 * include this with the following line in your theme function.php
 * require_once( 'functions-security.php' );
*/


// ------------------------------------------------------------------
// Supresss version tag scripts and styles

function remove_wp_tag_cssjs( $src ) {
    if ( strpos( $src, 'ver=' ) )
      $src = remove_query_arg( 'ver', $src );

    return $src;
}
add_filter( 'style_loader_src', 'remove_wp_tag_cssjs', 9999 );
add_filter( 'script_loader_src', 'remove_wp_tag_cssjs', 9999 );


// ------------------------------------------------------------------
// Hide wordpress generator  tag

function my_remove_version_info() {
    return '';
}
if (!is_admin()) {
        add_filter('the_generator', 'my_remove_version_info');
}


// ------------------------------------------------------------------
// Stop the user enumeration via links and author archives

if (!is_admin()) {
        if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die();
        add_filter('redirect_canonical', 'shapeSpace_check_enum', 10, 2);
}
function shapeSpace_check_enum($redirect, $request) {
        // permalink URL format
        if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
        else return $redirect;
}


// ------------------------------------------------------------------
// Stop the user enumeration through the REST API

add_filter( 'rest_endpoints', function( $endpoints ){
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
});


// ------------------------------------------------------------------
// Disable XMLRPC
if  (!is_admin()) {
    add_filter( 'xmlrpc_enabled', '__return_false' );
}


// ------------------------------------------------------------------
// Remove RSD Link from header (Windows Live Writer)
//
if  (!is_admin()) {
    remove_action ('wp_head', 'rsd_link');
    remove_action ('wp_head', 'wlwmanifest_link');
}


?>
