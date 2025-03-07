<?php

function add_custom_id_metabox() {
    add_meta_box(
        'city_custom_id',
        __('External City ID'),
        'custom_id_callback',
        'cities',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_custom_id_metabox');

function custom_id_callback($post) {
    $custom_id = get_post_meta($post->ID, '_custom_id', true);
    ?>
    <p>
        <label for="custom_id"><?php _e('External city ID'); ?></label>
        <input type="text" name="custom_id" id="custom_id" value="<?php echo esc_attr($custom_id); ?>" />
    </p>
    <?php
}

function save_custom_id($post_id) {
    if (array_key_exists('custom_id', $_POST)) {
        update_post_meta($post_id, '_custom_id', sanitize_text_field($_POST['custom_id'])); // Сохранение кастомного поля ID
    }
}
add_action('save_post', 'save_custom_id');
