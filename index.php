<?php

/**
 * Plugin Name:       Jamaa GP Elements add costume column
 * Description:       Add's columns and field to GP Elements Post Typse - NEEDS ACF Fields (Free).
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.htmls
 */



//Filed Type -> Select. Variable is for the seltabæle choices:
$use_ACF_from_inside_this_file = true;
$allow_for_multiple_choises = 0; //0 for false and 1 for true
$label = 'Category'; //Readable name with paces and so on...
$name = 'category'; //unik name with NO space
$headline_inside_elements = 'Category';
$choices = array(
	'Head' => 'Head',
	'Template' => 'Template',
	'Hook' => 'Hook',
	'Page' => 'Page',
	'Post' => 'Post',
	'Blog' => 'Blog',
	'Test' => 'Test',
	'Test2' => 'Test2',
);


// add column
add_filter('manage_gp_elements_posts_columns', function ($columns) {
	return array_merge($columns, ['category' => __('Category', 'jamaa_columns_plugin')]);
});
//add content to column
add_action('manage_gp_elements_posts_custom_column', function ($column_key, $post_id) {
	if ($column_key == 'category') {
		$category = get_post_meta($post_id, 'category', true);
		if ($category) {
			if (is_array($category)) {
				echo '<span>';
				foreach ($category as $value) {
					_e($value, 'textdomain');
					echo '<br>';
				}
				echo '</span>';
			} else {
				echo '<span>';
				_e($category, 'textdomain');
				echo '</span>';
			}
		}
	}
}, 10, 2);
//add sorable to column
add_filter('manage_edit-gp_elements_sortable_columns', function ($columns) {
	$columns['category'] = 'category';
	return $columns;
});
// edit query and 'orderby' to make ordering function
add_action('pre_get_posts', function ($query) {
	if (!is_admin()) {
		return;
	}

	$orderby = $query->get('orderby');
	if ($orderby == 'category') {
		$query->set('meta_key', 'category');
		$query->set('orderby', 'meta_value');
	}
});

/*ACF
*
*
*/
//ACF fields php for field and fields group - see variables in top of this file or make new content inside the plugin it self ($use_ACF_from_inside_this_file to false)
if (function_exists('acf_add_local_field_group') && $use_ACF_from_inside_this_file) :

	acf_add_local_field_group(array(
		'key' => 'group_6375ef98652fd',
		'title' => $headline_inside_elements,
		'fields' => array(
			array(
				'key' => 'field_6375ef98210c9',
				'label' => $label,
				'name' => $name,
				'aria-label' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => $choices,
				'default_value' => array(),
				'return_format' => 'value',
				'multiple' => $allow_for_multiple_choises,
				'allow_null' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'gp_elements',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'side',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

endif;
