<?php
/*
Plugin Name: Simple Hijri Calendar
Plugin URI: https://wordpress.org/plugins/simple-hijri-calendar/
Description: A simple hijri calendar widget
Version: 2.1.2
Author: Tommy Pradana
Author URI: http://dakwahstudio.com/
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * load text domain
 */
function simple_hijri_calendar_load_textdomain() {
	load_plugin_textdomain( 'simple-hijri-calendar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'init', 'simple_hijri_calendar_load_textdomain' );

/**
 * load css
 */
function simple_hijri_calendar_scripts() {
	wp_enqueue_style( 'simple-hijri-calendar', plugins_url( '/assets/css/style.css', __FILE__ ), array(), '1.0', 'all' );
	wp_enqueue_script( 'simple-hijri-calendar', plugins_url( '/assets/js/scripts.js', __FILE__ ), array( 'jquery' ), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'simple_hijri_calendar_scripts' );

/**
 * load uCal Class
 */
function simple_hijri_calendar_ucal() {
	if ( ! class_exists( 'uCal' ) ) {
		include_once( plugin_dir_path( __FILE__ ) . 'class/uCal.php' );
	}
}
add_action( 'init', 'simple_hijri_calendar_ucal' );


/**
 * convert date to arabic letter
 */
function simple_hijri_calendar_arabic( $string = '' ) {
	$match;

	if ( ! empty( $string ) ) {
		while ( preg_match( '/([0-9]+)/', $string, $match ) ) {
			$string = preg_replace( '/' . $match[0] . '/', simple_hijri_calendar_arabic_number( $match[0] ), $string, 1 );
		}
	}

	return $string;
}

/**
 * convert english to arabic number
 */
function simple_hijri_calendar_arabic_number( $digit ) {
	if ( empty( $digit ) ) {
		return '.';
	}

	$arabic_number = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩','-'=>'','.'=>'.' );
	$arabic_digit = '';
	$length = strlen( $digit );

	for ( $i = 0;  $i < $length;  $i++) { 
		if ( isset( $arabic_number[$digit[$i]] ) ) {
			$arabic_digit .= $arabic_number[$digit[$i]];
		}
	}

	return $arabic_digit;
}

/**
 * hour
 */
function simple_hijri_calendar_hour( $selected = '0' ) {
	for ( $h = 1; $h <= 24; $h++ ) {
		$time = $h;
		
		if ( $h <= 9 ) {
			$time = '0' . $h . ':00';
		} else if ( $h >= 10 && $h <= 23 ) {
			$time = $h . ':00';
		} else {
			$time = '00:00';
		}

		printf( '<option value="%1$s" %2$s>%3$s</option>',
			esc_attr( $h ),
			selected( $selected, $h, false ),
			esc_attr( $time )
		);
	}
}

/**
 * font options
 */
function simple_hijri_calendar_fonts() {
	$options = array();

	$options = array(
		'droidarabickufi' 		=> 'Droid Arabic Kufi',
		'droidarabicnaskh'		=> 'Droid Arabic Naskh',
		'notokufiarabic'		=> 'Noto Kufi Arabic',
		'notonaskharabic'		=> 'Noto Naskh Arabic',
		'notosanskufiarabic'	=> 'Noto Sans Kufi Arabic',
		'thabit'				=> 'Thabit'
	);

	return $options;
}

/**
 * inline fonts
 */
function simple_hijri_calendar_fonts_css( $handle ) {
	$fonts 	= simple_hijri_calendar_fonts();
	$css 	= $handle;
	$family = $fonts[$handle];
	
	return $font_css = '
	<style>
		@import url(http://fonts.googleapis.com/earlyaccess/' . $css . '.css);
		.hijri-calendar .hijri {
			font-family: "' . $family . '", Sans;
		}
	</style>';
}

/**
 * load widget class
 */
function simple_hijri_calendar_widget() {
	require_once( plugin_dir_path( __FILE__ ) . 'simple-hijri-calendar-widget.php' );
}
add_action( 'plugins_loaded', 'simple_hijri_calendar_widget' );

/**
 * register widget
 */
function simple_hijri_calendar_init() {
	register_widget( 'Simple_Hijri_Calendar' );
}
add_action( 'widgets_init', 'simple_hijri_calendar_init' );
/*EOF*/