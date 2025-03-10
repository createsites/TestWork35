<?php
/**
 * Test_work_35 Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package test_work_35
 */

add_action( 'wp_enqueue_scripts', 'storefront_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function storefront_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'test_work_35-style',
		get_stylesheet_directory_uri() . '/style.css',
		[ 'storefront-style' ]
	);
}

// создание custom post types для городов
require get_stylesheet_directory() . '/inc/custom-post-types/cities.php';

// кастомные поля с шириной и долготой
require get_stylesheet_directory() . '/inc/custom-post-fields/lat-long.php';

// кастомное поле с id города
require get_stylesheet_directory() . '/inc/custom-post-fields/id.php';

// кастомные таксономии (страны)
require get_stylesheet_directory() . '/inc/custom-taxonomies/countries.php';

// виджет погоды
require get_stylesheet_directory() . '/inc/widgets/weather.php';

// ajax для таблицы городов
require get_stylesheet_directory() . '/inc/ajax/cities.php';