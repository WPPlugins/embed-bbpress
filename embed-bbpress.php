<?php
/*
Plugin Name: embed bbpress
Plugin URI: http://wordpress.freeall.org/כתובת התוסף באתר
Description: A simple way to embed the bbpress forum into your WordPress theme
Author: Asaf Chertkoff (FreeAllWeb GUILD)
Author URI: http://wordpress.freeall.org
Version: 0.8
Text Domain:embed-bbpress
*/

/*  Copyright 2009  Asaf Chertkoff  (email : asaf@freeallweb.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('admin_menu', 'ebb_create_menu');
add_action('init','ebb_activate_shortcode');
add_action('init','loadlang');

function ebb_create_menu() {
	add_submenu_page('plugins.php','Embed BBpress','Embed BBpress','administrator','embed_BBpress','ebb_show_menu_page');
}

function loadlang() {
load_plugin_textdomain('embed-bbpress','',plugin_basename( dirname( __FILE__ ).'/translation'));
}

function ebb_show_menu_page() {
	$ebb_opt_name = 'ebb_name';
	$ebb_frame_width = 'ebb_opt_frame_width';
	$ebb_frame_height = 'ebb_opt_frame_height';
	$hidden_field_name = 'ebb_submit_hidden';
   $data_field_name = 'ebb_field_name';
   $ebb_width_field_name = 'width_of_frame';
   $ebb_height_field_name = 'height_of_frame';

	$opt_val = get_option( $ebb_opt_name );
	$ebb_width_val = get_option( $ebb_frame_width );
	$ebb_height_val = get_option( $ebb_frame_height );	  	
  	
  	if( $_POST[ $hidden_field_name ] == 'Y' ) {
   	$opt_val = $_POST[ $data_field_name ];
   	$ebb_width_val = $_POST[ $ebb_width_field_name ];
		$ebb_height_val = $_POST[ $ebb_height_field_name ];
   	update_option( $ebb_opt_name, $opt_val );
   	update_option( $ebb_frame_width, $ebb_width_val );
   	update_option( $ebb_frame_height, $ebb_height_val );
    	 '<div class="updated"><p><strong>'. _e('Options saved.','embed-bbpress').'</strong></p></div>';
	}

 	echo '<div class="wrap">';
 	echo '<h1>'. __('embed BBpress 0.8','embed-bbpress') .'</h1>';
  	echo '<p>'. __('technically, this plugin was built for embeding the external home page of BBpress, after the installation, in the easiest way i could think of. However, because the method is so simple, one can use it for embeding any HTML page he wants.','embed-bbpress') .'</p>';
  	echo '<p>'. __('note: the purpose was the ease of use, and not elegancy. we could made it more complicated by trying to force the forum to have the site template or something like this. but we didn\'t.','embed-bbpress').'</p>';
  	echo '<p>'. __('for suggestions about further development ','embed-bbpress') .'<a href="mailto:asaf@freeallweb.org" alt="'.__('mail me','embed-bbpress').'">'.__('mail me','embed-bbpress').'</a>.</p>';
  	echo '<h2>'. __('Step-By-Step Setting ','embed-bbpress').'</h2>';
  	echo '<p>'. __('1. Install BBpress.','embed-bbpress') .'</p>';
  	echo '<p>'. __('2. Load the special embedable template ','embed-bbpress').'<a href="">'.__('from here','embed-bbpress').'</a>'.__(' and upload the entire folder as it is to \'bb-templates\' in the root of the forum directory.','embed-bbpress').'</p>';
  	echo '<p>'. __('3. Activate \'Embed BBpress\', copy the forum home URL to the first field and choose the width and height of your desire (without "px\'s").','embed-bbpress') .'</p>';
  	echo '<p>'. __('4. Create a new page, and paste the shortcode [EmbedBBpress] inside it .','embed-bbpress') .'</p>';
  	echo '<p>'. __('5. You\'re good to go.','embed-bbpress').'</p>';
  	echo '</div><hr/>';

 	echo '<div class="wrap"><h2>'.__('Settings','embed-bbpress').'</h2>';
	echo '<form name="ebb_form" method="post" action="">';
	echo '<input type="hidden" name="'. $hidden_field_name .'" value="Y">';

	echo '<p>'. _e('URL of Home page to embed:','embed-bbpress'); 
	echo '<input type="text" name="'. $data_field_name .'" value="'. $opt_val .'" size="40">';
	echo '</p>';
	echo '<p>'. __('width of frame:','embed-bbpress').'<input type="text" name="'.$ebb_width_field_name.'" value="'. $ebb_width_val .'" size="20"></p>';
	echo '<p>'. __('height of frame:','embed-bbpress').'<input type="text" name="'.$ebb_height_field_name.'" value="'.$ebb_height_val .'" size="20"></p>'; 
	echo '<p class="submit">';
	echo '<input type="submit" name="Submit" value="'. __('save settings','embed-bbpress').'" />';
	echo '</p></form></div>';
	echo '<hr/><div class="warp"><h3>'.__('If you want to give something back:','embed-bbpress').'</h3>';
	echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="9810099"><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"></form>';
	echo '<p><a href="http://www.amazon.com/wishlist/21SEN5UC15V17/ref=reg_hu-wl_goto-registry?_encoding=UTF8&sort=date-added" alt="'.__('My Amazon Wishlist','embed-bbpress').'">'.__('My Amazon Wishlist','embed-bbpress').'</a></p></div>';
}

function ebb_activate_shortcode() {
	add_shortcode( 'EmbedBBpress', 'ebb_embeding_the_object' );
}

function ebb_embeding_the_object (){
		$ebb_show_opt = get_option(ebb_name);
		$ebb_show_width_frame = get_option(ebb_opt_frame_width);
		$ebb_show_height_frame = get_option(ebb_opt_frame_height);
		
		$ebb_output .='<div style="width:100%"><object data="'.$ebb_show_opt.'" type="text/html" style="width:'.$ebb_show_width_frame.'px;height:'.$ebb_show_height_frame.'px">';
		$ebb_output .=' alt : <a href="'.$ebb_show_opt.'">' .__('Click here if the forum doesn\'t open by itself','embed-bbpress'). '</a>';
		$ebb_output .='</object></div>';
		return $ebb_output;
}


?>