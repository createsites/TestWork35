<?php
function add_lat_long_metabox() {
	add_meta_box(
		'city_lat_long',
		__('City Coordinates'),
		'city_lat_long_callback',
		'cities',
		'side',
		'high'
	);
}
add_action('add_meta_boxes', 'add_lat_long_metabox');

function city_lat_long_callback($post) {
	$latitude = get_post_meta($post->ID, '_latitude', true);
	$longitude = get_post_meta($post->ID, '_longitude', true);
	?>
	<p>
		<label for="latitude"><?php _e('Latitude'); ?></label>
		<input type="text" name="latitude" id="latitude" value="<?php echo esc_attr($latitude); ?>" />
	</p>
	<p>
		<label for="longitude"><?php _e('Longitude'); ?></label>
		<input type="text" name="longitude" id="longitude" value="<?php echo esc_attr($longitude); ?>" />
	</p>
	<?php
}

function save_city_lat_long($post_id) {
	if (array_key_exists('latitude', $_POST)) {
		update_post_meta($post_id, '_latitude', sanitize_text_field($_POST['latitude']));
	}
	if (array_key_exists('longitude', $_POST)) {
		update_post_meta($post_id, '_longitude', sanitize_text_field($_POST['longitude']));
	}
}
add_action('save_post', 'save_city_lat_long');
