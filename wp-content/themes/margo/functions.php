<?php
/**
 * Fonctions principales du thème Margo
 */

// -----------------------------------------------------------------------
// Désactiver Gutenberg (éditeur classique)
// -----------------------------------------------------------------------
add_filter( 'use_block_editor_for_post', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );

// -----------------------------------------------------------------------
// Désactiver les Articles (post type "post")
// -----------------------------------------------------------------------
add_action( 'init', function () {
    // Retirer le support des articles du menu principal
    remove_action( 'init', 'register_post_type' );
} );

add_action( 'admin_menu', function () {
    remove_menu_page( 'edit.php' );          // Articles
    remove_menu_page( 'edit-comments.php' ); // Commentaires
} );

// Rediriger si quelqu'un accède directement à /wp-admin/edit.php
add_action( 'admin_init', function () {
    global $pagenow;
    if ( $pagenow === 'edit.php' && ( ! isset( $_GET['post_type'] ) || $_GET['post_type'] === 'post' ) ) {
        wp_redirect( admin_url() );
        exit;
    }
} );

// Désactiver les commentaires sur tous les types de contenu
add_action( 'init', function () {
    foreach ( get_post_types() as $post_type ) {
        if ( post_type_supports( $post_type, 'comments' ) ) {
            remove_post_type_support( $post_type, 'comments' );
            remove_post_type_support( $post_type, 'trackbacks' );
        }
    }
} );

// Retirer la bulle de notification des commentaires dans la barre admin
add_action( 'wp_before_admin_bar_render', function () {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'comments' );
} );

// -----------------------------------------------------------------------
// Support du thème
// -----------------------------------------------------------------------
add_action( 'after_setup_theme', function () {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );

    // Désactiver le flux RSS des articles
    add_filter( 'feed_links_show_posts_feed', '__return_false' );
    add_filter( 'feed_links_show_comments_feed', '__return_false' );
} );

// -----------------------------------------------------------------------
// Enqueue styles et scripts
// -----------------------------------------------------------------------
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'margo-style', get_stylesheet_uri(), [], '1.0.0' );
} );
