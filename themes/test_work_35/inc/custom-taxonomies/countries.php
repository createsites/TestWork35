<?php

function create_countries_taxonomy() {
	$labels = array(
		'name' => __('Countries'),
		'singular_name' => __('Country'),
		'search_items' => __('Search Countries'),
		'all_items' => __('All Countries'),
		'parent_item' => __('Parent Country'),
		'parent_item_colon' => __('Parent Country:'),
		'edit_item' => __('Edit Country'),
		'update_item' => __('Update Country'),
		'add_new_item' => __('Add New Country'),
		'new_item_name' => __('New Country Name'),
		'menu_name' => __('Countries'),
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'show_in_rest' => true,
	);
	register_taxonomy('countries', 'cities', $args);
}
add_action('init', 'create_countries_taxonomy');
