<?php

// Check whether WordPress and ACF are available; bail if not.
if (! function_exists('get_field')) {
    return;
}
if (! function_exists('add_filter')) {
    return;
}
if (! function_exists('add_action')) {
    return;
}

/**
 * Add a JSON load point to ACF to add our fields
 */
add_filter('acf/settings/load_json', function ($paths) {

    // append path
    $paths[] = dirname(__FILE__). '/src/json';

    // return
    return $paths;
}, 10);


/**
 * Add ACF menu icon to menus if present
 */
add_filter('wp_nav_menu_objects', function ($items, $args) {
    // Loop through menu items
    foreach ($items as &$item) {
        // Get data from ACF fields
        $icon = get_field('icon', $item);
        $hideLabel = get_field('hide_label', $item);
        $position = get_field('icon_position', $item);

        // Get the icon contents
        $iconPath = get_attached_file($icon);
        $iconContents = ($iconPath) ? file_get_contents($iconPath) : '';

        // Maybe hide label
        if ($hideLabel) {
            // Wrap the title in a span with Bootstrap's screen-reader-only class
            $item->title = '<span class="sr-only">' . $item->title . '</span>';
        }

        // If the icon exists, append icon
        if ($icon) {
            if ($position == 'before') {
                $item->title = $iconContents . $item->title;
            } else {
                $item->title = $item->title . $iconContents;
            }
        }
    }

    // Return the items with icons
    return $items;
}, 10, 2);
