<?php

/**
 * The Shortcode
 */
function ebor_toggles_shortcode( $atts, $content = null ) {
	global $ebor_toggles_count;
	global $rand;
	$ebor_toggles_count = 0;
	$rand = false;
	
	extract( 
		shortcode_atts( 
			array(
				'type' => ''
			), $atts 
		) 
	);
	$output = false;
	
	$rand = wp_rand(0,10000);
	
	$output .= '
		<div class="panel-group '. esc_attr($type) .' ebor-'. $rand .'" id="accordion-'. $rand .'">'. do_shortcode($content) .'</div>
		<script type="text/javascript">
			jQuery(document).ready(function() {	
				jQuery(\'.panel-group.ebor-'. $rand .'\').find(\'.panel-default:has(".in")\').addClass(\'panel-active\');
				
			    jQuery(\'.panel-group.ebor-'. $rand .'\').on(\'shown.bs.collapse\', function(e) {
			        jQuery(e.target).closest(\'.panel-default\').addClass(\' panel-active\');
			    }).on(\'hidden.bs.collapse\', function(e) {
			        jQuery(e.target).closest(\'.panel-default\').removeClass(\' panel-active\');
			    });
			});  
		</script>
	';

	return $output;
}
add_shortcode( 'lydia_toggles', 'ebor_toggles_shortcode' );

/**
 * The Shortcode
 */
function ebor_toggles_content_shortcode( $atts, $content = null ) {
	global $ebor_toggles_count;
	global $rand;
	
	extract( 
		shortcode_atts( 
			array(
				'title' => ''
			), $atts 
		) 
	);
	
	$ebor_toggles_count++;
	$active = $in = false;
	
	if( 1 == $ebor_toggles_count ){
		$active = 'active';
		$in = 'in';	
	}
	
	$output = '<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" class="panel-toggle '. esc_attr($active) .'" data-parent="#accordion-'. $rand .'" href="#collapse-'. $rand .'-'. esc_attr($ebor_toggles_count) .'">'. htmlspecialchars_decode($title) .'</a>
						</h4>
					</div>
					<div id="collapse-'. $rand .'-'. esc_attr($ebor_toggles_count) .'" class="panel-collapse collapse '. esc_attr($in) .'">
						<div class="panel-body">'. wpautop(do_shortcode(htmlspecialchars_decode($content))) .'</div>
					</div>
			   </div>';

	return $output;
}
add_shortcode( 'lydia_toggles_content', 'ebor_toggles_content_shortcode' );

// Parent Element
function ebor_toggles_shortcode_vc() {
	vc_map( 
		array(
			"icon" => 'lydia-vc-block',
		    'name'                    => esc_html__( 'Toggles' , 'lydia' ),
		    'base'                    => 'lydia_toggles',
		    'description'             => esc_html__( 'Create Accordion Content', 'lydia' ),
		    'as_parent'               => array('only' => 'lydia_toggles_content'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
		    'content_element'         => true,
		    'show_settings_on_create' => true,
		    "js_view" => 'VcColumnView',
		    "category" => esc_html__('Lydia WP Theme', 'lydia'),
		    'params'          => array(
		    	array(
		    		"type" => "dropdown",
		    		"heading" => esc_html__("Display type", 'lydia'),
		    		"param_name" => "type",
		    		"value" => array_flip(array(
		    			'' => 'Standard',
		    			'boxed' => 'Boxed'
		    		))
		    	),
		    )
		) 
	);
}
add_action( 'vc_before_init', 'ebor_toggles_shortcode_vc' );

// Nested Element
function ebor_toggles_content_shortcode_vc() {
	vc_map( 
		array(
			"icon" => 'lydia-vc-block',
		    'name'            => esc_html__('Toggles Content', 'lydia'),
		    'base'            => 'lydia_toggles_content',
		    'description'     => esc_html__( 'Toggle Content Element', 'lydia' ),
		    "category" => esc_html__('Lydia WP Theme', 'lydia'),
		    'content_element' => true,
		    'as_child'        => array('only' => 'lydia_toggles'), // Use only|except attributes to limit parent (separate multiple values with comma)
		    'params'          => array(
		    	array(
		    		"type" => "textfield",
		    		"heading" => esc_html__("Title", 'lydia'),
		    		"param_name" => "title",
		    		'holder' => 'div'
		    	),
	            array(
	            	"type" => "textarea_html",
	            	"heading" => esc_html__("Block Content", 'lydia'),
	            	"param_name" => "content"
	            ),
		    ),
		) 
	);
}
add_action( 'vc_before_init', 'ebor_toggles_content_shortcode_vc' );

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_lydia_toggles extends WPBakeryShortCodesContainer {}
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode')){
    class WPBakeryShortCode_lydia_toggles_content extends WPBakeryShortCode {}
}