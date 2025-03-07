<?php

function search_cities_ajax() {
    global $wpdb;
    $search = sanitize_text_field($_POST['search']);
    $cities = $wpdb->get_results($wpdb->prepare("
        SELECT p.ID, p.post_title, t.name AS country
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->term_relationships} tr ON (p.ID = tr.object_id)
        LEFT JOIN {$wpdb->terms} t ON (tr.term_taxonomy_id = t.term_id)
        WHERE p.post_type = 'cities' AND p.post_status = 'publish' AND p.post_title LIKE %s
    ", '%' . $wpdb->esc_like($search) . '%'));

    if ($cities) {
        foreach ($cities as $city) {
            $latitude = get_post_meta($city->ID, '_latitude', true);
            $longitude = get_post_meta($city->ID, '_longitude', true);

            try {
                $weather = (new OpenWeather())->getByGeo($latitude, $longitude);
                $temperature = $weather->getTemperature();
            } catch (\Exception $e) {
                $temperature = $e->getMessage();
            }
            ?>
            <tr>
                <td><?php echo esc_html($city->country); ?></td>
                <td><?php echo esc_html($city->post_title); ?></td>
                <td><?php echo esc_html($temperature); ?></td>
            </tr>
        <?php }
    } else {
        echo '<tr><td colspan="3">' . __('No cities found') . '</td></tr>';
    }

    wp_die();
}
add_action('wp_ajax_search_cities', 'search_cities_ajax');
add_action('wp_ajax_nopriv_search_cities', 'search_cities_ajax');