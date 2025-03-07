<?php
/*
Template Name: Cities List
*/

get_header();

?>

	<form id="city-search-form">
		<input type="text" id="city-search" autocomplete="off" placeholder="Search for cities...">
	</form>

<?php do_action('before_cities_table'); ?>

	<table id="cities-table">
		<thead>
		<tr>
			<th><?php _e('Country'); ?></th>
			<th><?php _e('City'); ?></th>
			<th><?php _e('Temperature'); ?> °C</th>
		</tr>
		</thead>
		<tbody>
		<?php
		global $wpdb;
		$cities = $wpdb->get_results("
            SELECT p.ID, p.post_title, t.name AS country
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->term_relationships} tr ON (p.ID = tr.object_id)
            LEFT JOIN {$wpdb->terms} t ON (tr.term_taxonomy_id = t.term_id)
            WHERE p.post_type = 'cities' AND p.post_status = 'publish'
        ");

		// запрашиваем по API за раз все города, у которых есть id
        $cities_id = [];
        foreach ($cities as $city) {
            $latitude = get_post_meta($city->ID, '_latitude', true);
            $longitude = get_post_meta($city->ID, '_longitude', true);
            $external_id = (int)get_post_meta($city->ID, '_custom_id', true);
            if ($external_id > 0) {
                $cities_id[$city->ID] = $external_id;
            }
        }
        if (!empty($cities_id)) {
            try {
                $cities_weather = (new OpenWeather())->getByIds($cities_id);
            } catch (\Exception $e) {
                $cities_weather= [];
            }
        }

        // проходимся по всем городам для вывода
        // и дозапрашиваем API для тех, у кого id не был указан
		foreach ($cities as $city) {
			$latitude = get_post_meta($city->ID, '_latitude', true);
			$longitude = get_post_meta($city->ID, '_longitude', true);
			$external_id = (int)get_post_meta($city->ID, '_custom_id', true);

			if (array_key_exists($city->ID, $cities_weather)) {
                $temperature = $cities_weather[$city->ID]->getTemperature();
            } else {
                try {
                    $weather = (new OpenWeather())->getByGeo($latitude, $longitude);
                    $temperature = $weather->getTemperature();
                } catch (\Exception $e) {
                    $temperature = $e->getMessage();
                }
            }
            ?>
			<tr>
				<td><?php echo esc_html($city->country); ?></td>
				<td><?php echo esc_html($city->post_title); ?></td>
				<td><?php echo esc_html($temperature); ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>

<?php do_action('after_cities_table'); ?>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('city-search');
            const citiesTableBody = document.querySelector('#cities-table tbody');

            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value;

                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'search_cities',
                        search: searchValue
                    })
                })
                    .then(response => response.text())
                    .then(data => {
                        citiesTableBody.innerHTML = data;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>


<?php
get_footer();
