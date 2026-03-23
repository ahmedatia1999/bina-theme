<?php
// Add Meta Box in Admin
function add_transparent_header_meta_box() {
    $post_types = get_post_types(array('public' => true), 'names');
    foreach ($post_types as $post_type) {
        add_meta_box(
            'transparent_header_meta_box',
            'Transparent Header',
            'render_transparent_header_meta_box',
            $post_type,
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'add_transparent_header_meta_box');

// Render Meta Box Content
function render_transparent_header_meta_box($post) {
    $value = get_post_meta($post->ID, '_transparent_header', true);
    wp_nonce_field('transparent_header_nonce', 'transparent_header_nonce');
    ?>
    <p>
        <label for="transparent_header_checkbox">
            <input type="checkbox" id="transparent_header_checkbox" name="transparent_header_checkbox" value="1" <?php checked($value, 1); ?> />
            Enable Transparent Header
        </label>
    </p>
    <?php
}

// Save Meta Box Content
function save_transparent_header_meta_box($post_id) {
    if (!isset($_POST['transparent_header_nonce']) || !wp_verify_nonce($_POST['transparent_header_nonce'], 'transparent_header_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['transparent_header_checkbox'])) {
        update_post_meta($post_id, '_transparent_header', 1);
    } else {
        delete_post_meta($post_id, '_transparent_header');
    }
}
add_action('save_post', 'save_transparent_header_meta_box');

// Add Body Class Based on Post Meta
function add_transparent_header_body_class($classes) {
    if (is_singular()) {
        global $post;
        $transparent_header = get_post_meta($post->ID, '_transparent_header', true);
        if ($transparent_header) {
            $classes[] = 'transparent-header';
        }
    }
    return $classes;
}
add_filter('body_class', 'add_transparent_header_body_class');
