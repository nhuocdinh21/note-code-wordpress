<?php
/**
 * Add language attribute to the index
 *
 * @param array $shared_attributes
 * @param WP_Post $post
 * @return array
 */
function swa_add_attributes($shared_attributes, $post)
{
    $post_language_details = apply_filters('wpml_post_language_details', NULL, $post->ID);

    if (!empty($post_language_details['language_code']))
        $shared_attributes['language'] = $post_language_details['language_code'];

    return $shared_attributes;
}
add_filter('algolia_searchable_post_shared_attributes', 'swa_add_attributes', 10, 2);
add_filter('algolia_post_shared_attributes', 'swa_add_attributes', 10, 2);

/**
 * Add Faceting settings
 *
 * @param array $settings
 * @param string $post_type
 * @return array
 */
function swa_index_settings($settings, $post_type = '')
{
    $settings['attributesForFaceting'][] = 'language';

    return $settings;
}
add_filter('algolia_posts_index_settings', 'swa_index_settings', 10, 2);
add_filter('algolia_searchable_posts_index_settings', 'swa_index_settings', 10, 1);

/**
 * Add pre_get_posts filter
 *
 * @param array $params
 * @return array
 */
function swa_search_params($params)
{
    $current_lang = apply_filters('wpml_current_language', NULL);

    if (!$current_lang) return $params;

    if (!empty($post_language_details))
        $params['filters'][] = 'language:' . $current_lang;

    return $params;
}
add_filter('algolia_search_params', 'swa_search_params', 10, 1);

/**
 * Add custom options to WP Search with Algolia config
 *
 * @param array $config
 * @return array
 */
function swa_algolia_config($config)
{
    $current_lang = apply_filters('wpml_current_language', NULL);

    if (!$current_lang) return $config;

    $config['autocomplete']['options'] = [
        'filters' => 'language:' . $current_lang
    ];

    $config['instantsearch']['options'] = [
        'language' => [$current_lang]
    ];

    return $config;
}
add_filter('algolia_config', 'swa_algolia_config', 10, 1);

/**
 * Set new template path
 *
 * @param string $locations
 * @param string $file
 * @return string
 */
function swa_algolia_default_template($locations, $file)
{
    // allow devs to implement their own template
    $template_override = apply_filters('swa_template_override', true);

    if (!$template_override) return $locations;

    return get_stylesheet_directory_uri() . '/templates/' . $file;
}
// add_filter('algolia_default_template', 'swa_algolia_default_template', 10, 2);