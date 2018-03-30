<?php
/**
 * Trigger this file on Plugin uninstall
 * 
 * @package
 */

 if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
     die;
 }

 $properties = get_posts( array( 'post_type' => 'property', 'numberposts' => -1 ) );

 foreach ( $properties as $property ) {
     wp_delete_post( $property->ID, true ); 
 }
