<?php
add_action( 'ux_builder_setup', function () {
    add_ux_builder_shortcode( 'tab_projects', array(
        'name' => __( 'Tab Projects' ),
        'thumbnail' =>  flatsome_ux_builder_thumbnail( 'categories' ),
        'category' => __( 'Content' ),

        'options' => array(
            'ids' => array(
                'type' => 'select',
                'heading' => 'Select Category',
                'param_name' => 'ids',
                'config' => array(         
                    'multiple' => true,           
                    'placeholder' => 'Select..',
                    'termSelect' => array(
                        'post_type' => 'chi-tiet-du-an',
                        'taxonomies' => 'du-an'
                    ),
                )
            ),
            'number' => array(
                'type' => 'textfield',
                'heading' => 'Number Post',
                'default' => '12',
            ),
            'advanced_options' => require( get_template_directory() . '/inc/builder/shortcodes/commons/advanced.php'),
        ),
    ) );
});
