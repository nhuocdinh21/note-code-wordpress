<?php
// check has term child
$obj = get_queried_object();

$term_id = $obj->term_id;
$taxonomy_name = $obj->taxonomy;
$terms = get_term_children( $term_id, $taxonomy_name );

if ( !empty( $terms ) && !is_wp_error( $terms ) ) :
	$catechild = get_terms( $taxonomy_name, array(
	    'orderby'    => 'name',
	    'order'      =>'ASC',
	    'hide_empty' => 0,		    
	    'parent'     => $obj->term_id,
	) );
	$link_all = get_term_link( $obj->term_id );
	$class = 'active';
else:
	$catechild = get_terms( $taxonomy_name, array(
	    'orderby'    => 'name',
	    'order'      =>'ASC',
	    'hide_empty' => 0,		    
	    'parent'     => $obj->parent,
	) );
	$link_all = get_term_link( $obj->parent );
endif;