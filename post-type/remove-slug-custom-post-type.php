<?php
// Remove the slug from published post permalinks. ----------------------------------------------------------------------------------------------------------------------------
function custom_remove_cpt_slug($post_link, $post, $leavename)
{
    if ( ( 'chi-tiet-doi-tac' != $post->post_type && 'chi-tiet-tai-lieu' != $post->post_type && 'chi-tiet-giai-phap' != $post->post_type && 'chi-tiet-du-an' != $post->post_type && 'chi-tiet-dich-vu' != $post->post_type && 'product' != $post->post_type ) || 'publish' != $post->post_status )
    {
        return $post_link;
    }
    $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);

    return $post_link;
}
add_filter('post_type_link', 'custom_remove_cpt_slug', 10, 3);

function custom_parse_request_tricksy($query)
{
    // Only noop the main query
    if (!$query->is_main_query())
        return;

    // Only noop our very specific rewrite rule match
    if (2 != count($query->query) || !isset($query->query['page']))
    {
        return;
    }

    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if (!empty($query->query['name']))
    {
        $query->set('post_type', array('post', 'page', 'chi-tiet-doi-tac', 'chi-tiet-tai-lieu', 'chi-tiet-giai-phap', 'chi-tiet-du-an', 'chi-tiet-dich-vu', 'product'));
    }
}
add_action('pre_get_posts', 'custom_parse_request_tricksy');

// Remove the slug of taxonomy use plugin "WP htaccess Control" ----------------------------------------------------------------------------------------------------------------------------