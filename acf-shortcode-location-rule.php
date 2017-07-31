<?php
/*
 * Plugin Name:       Advanced Custom Fields Shortcode Location Rule
 * Description:       Adds a new location rule to Advanced Custom Fields to make a field group appear on the post editing screen only when a certain shortcode is being used.
 * Version:           1.0.0
 * Author:            Timo Klemm
 * Author URI:        https://github.com/team-ok
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * 
 * Add shortcode location rule type to ACF
 * 
 */
add_filter( 'acf/location/rule_types', 'acf_shortcode_location_rule_type' );
function acf_shortcode_location_rule_type( $choices ){

	if ( ! isset( $choices['Shortcode'] ) ){
		$choices['Shortcode'] = array();	
	}
	
	$choices['Shortcode']['shortcode'] = 'Shortcode';

	return $choices;
}

/**
 * 
 * Populate shortcode location rule select field with registered shortcode names
 * 
 */
add_filter( 'acf/location/rule_values/shortcode', 'acf_shortcode_location_rule_values' );
function acf_shortcode_location_rule_values( $choices ){

	global $shortcode_tags;

	foreach ( array_keys( $shortcode_tags ) as $shortcode ){

		$choices[$shortcode] = $shortcode;
	}

	asort( $choices );

	return $choices;
}

/**
 *
 * The rule to check if there's a specific shortcode used in the post content
 * 
 */
add_filter( 'acf/location/rule_match/shortcode', 'acf_shortcode_location_rule_match', 10, 3 );
function acf_shortcode_location_rule_match( $match, $rule, $options ){

	$post = get_post( $options['post_id'] );
	
	$match = $post instanceof WP_Post && has_shortcode( $post->post_content, $rule['value'] );

	if ( $rule['operator'] == '!=' ){
		
		$match = ! $match;
	}

	return $match;
}