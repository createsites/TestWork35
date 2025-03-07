<?php

function create_cities_post_type() {
	$labels = array(
		'name' => __('Cities'),
		'singular_name' => __('City'),
		'menu_name' => __('Cities'),
		'add_new_item' => __('Add New City'),
		'edit_item' => __('Edit City'),
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'has_archive' => true,
		'supports' => array('title'),
		'show_in_rest' => true,
		'menu_icon' => 'dashicons-location-alt',
	);
	register_post_type('cities', $args);
}
add_action('init', 'create_cities_post_type');
