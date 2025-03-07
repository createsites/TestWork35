<?php

require get_stylesheet_directory() . '/inc/open-weather-class.php';

class City_Weather_Widget extends WP_Widget
{

	function __construct() {
		parent::__construct(
			'city_weather_widget',
			__('City Weather'),
			array('description' => __('Displays the current weather for a selected city'))
		);
	}

	public function widget($args, $instance) {
		$city_id = $instance['city'];
		$city_name = get_the_title($city_id);
		$latitude = get_post_meta($city_id, '_latitude', true);
		$longitude = get_post_meta($city_id, '_longitude', true);
        $external_id = (int)get_post_meta($city_id, '_custom_id', true);

        $result = "The current temperature is: %.2f°C";

        if ($external_id) {
            $weather = (new OpenWeather())->getByIds([$external_id]);;
            $result = sprintf($result, $weather[0]->getTemperature());
        } else {
            try {
                $weather = (new OpenWeather())->getByGeo($latitude, $longitude);
                $result = sprintf($result, $weather->getTemperature());
            } catch (\Exception $e) {
                $result = $e->getMessage();
            }
        }

		echo $args['before_widget'];
		echo $args['before_title'] . $city_name . $args['after_title'];
		echo "<p>$result</p>";
		echo $args['after_widget'];
	}

	public function form($instance) {
		$city = !empty($instance['city']) ? $instance['city'] : '';
		$cities = get_posts(array('post_type' => 'cities', 'numberposts' => -1));
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('city')); ?>"><?php _e('Select City:'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('city')); ?>" name="<?php echo esc_attr($this->get_field_name('city')); ?>">
				<?php foreach ($cities as $city_post) : ?>
					<option value="<?php echo $city_post->ID; ?>" <?php selected($city, $city_post->ID); ?>><?php echo $city_post->post_title; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['city'] = (!empty($new_instance['city'])) ? strip_tags($new_instance['city']) : '';
		return $instance;
	}
}

function register_city_weather_widget() {
	register_widget('City_Weather_Widget');
}
add_action('widgets_init', 'register_city_weather_widget');
