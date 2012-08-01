<?php
/**
 * @package Common
 * @author Bharat Chodvadiya
 * @version 1.0
 */
/*
Plugin Name: Bpc_Slideshow
Description: Jquery Slideshow for wordpress.
Author: Bharat Chodvadiya, Sr Web Developer
Version: 1.0
Author URI: http://bharatchodvadiya.wordpress.com
*/

  $pluginDir=get_option('siteurl').'/wp-content/plugins/bpc_slideshow/';

    require_once(ABSPATH . 'wp-config.php');
	require_once(ABSPATH . 'wp-load.php');
   	
    /* Runs when plugin is activated */
	register_activation_hook(__FILE__,'install'); 

	/* Runs on plugin deactivation*/
	register_deactivation_hook( __FILE__, 'uninstall' );
    
	function install()
	{  
	    global $wpdb;
	   
	   $table=$wpdb->prefix . 'slideshow';
		$sql = "CREATE TABLE IF NOT EXISTS `$table` (`id` INT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
									   `image_name` VARCHAR( 80 ) NOT NULL
									  );";		 
		$wpdb->query($sql);
	}
	function uninstall()
	{
	    global $wpdb;
	    $table=$wpdb->prefix . 'slideshow';
	    $sql1 = "DROP TABLE IF EXISTS `$table`";
		$wpdb->query($sql1);
				
	}

function slideshow_plugin_menu() {
	 add_options_page('Slideshow Management', 'Bpc_Slideshow', 'administrator', 'slideshow', 'slideshow_mgt');
}

add_action('admin_menu', 'slideshow_plugin_menu');

wp_enqueue_script( 'ajax-script', $pluginDir.'js/data.js', array('jquery'), 1.0 );
wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

add_action( 'wp_ajax_ajax_action', 'ajax_action_delete' ); 
add_action( 'wp_ajax_nopriv_ajax_action', 'ajax_action_delete' );

function ajax_action_delete() 
{
	$name = $_POST['iname'];
	global $wpdb;
	$table=$wpdb->prefix . 'slideshow';

	$sql="delete from $table where id=".$_POST['uid'];
	$data=$wpdb->get_results($sql,ARRAY_A);

	$imagefile = ABSPATH.'wp-content/plugins/bpc_slideshow/images/'.$name;
	unlink($imagefile);
	echo 'yes';
	die(); 
}
function slideshow_mgt() {
$pluginDir=get_option('siteurl').'/wp-content/plugins/bpc_slideshow/';
echo '<script language="javascript" src="'.$pluginDir.'js/data.js"></script>';
echo '<div class="wrap">
	<h2>Slide Show Settings</h2>
	<form method="post" action="options.php">';
	wp_nonce_field('update-options');
	$checked= "";
	if(get_option('slideshow_enable')==1) {
		$checked = 'checked';
	}
	
	if(get_option('slideshow_only_images')==1) {
		$onlyimagechecked = 'checked';
	}	
	
	echo '<table class="form-table">
		<tr valign="top">
		<th scope="row">Duration:</th>
		<td><input type="text" name="slideshow_duration" value="'.get_option('slideshow_duration').'" size="10" /></td>
		</tr>
		<tr valign="top">
		<th scope="row">SlideShow Width:</th>
		<td><input type="text" name="slideshow_width" value="'.get_option('slideshow_width').'"  size="10" /></td>
		</tr>
		<tr valign="top">
		<th scope="row">Image Height:</th>
		<td><input type="text" name="slideshow_image_height" value="'.get_option('slideshow_image_height').'"  size="10" /></td>
		</tr>
		<tr valign="top">
		<th scope="row">Image Width:</th>
		<td><input type="text" name="slideshow_image_width" value="'.get_option('slideshow_image_width').'"  size="10" /></td>
		</tr>		
		<tr valign="top">
		<th scope="row">Enable Slideshow:</th>
		<td><input type="checkbox" name="slideshow_enable" value="1" '.$checked.'   /></td>
		</tr>
		<tr valign="top"><td>
		<input type="submit" class="button" value="Save Changes" />
		</td></tr>
		</table>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="slideshow_duration,slideshow_width,slideshow_image_height,slideshow_image_width,slideshow_enable" />
		</form>';
				
		echo '<br />
		<h2>Please select file to upload:</h2><br />';
		echo '<form action="'.$pluginDir.'upload.php" method="post" enctype="multipart/form-data" name="upload">
		Filename:<input type="file" name="file" id="file" />
		<input type="submit" name="submit" value="Submit" />
		</form>';
		
		echo '<br />';
		global $wpdb;
	    $table=$wpdb->prefix . 'slideshow';
		$sqlget="select * from $table";
        $data=$wpdb->get_results($sqlget,ARRAY_A);
		
		if($data)
		{
		 echo '<h2>All image for Slide Show</h2>';
		 echo '<table width="50%" bordercolor="#999999">
		       <tr bordercolor="#999999"><td width="20%"><h2>Image</h2></td>
		       <td width="50%"><h2>Image Name</h2></td>
			   <td width="20%"><h2>Delete</h2></td></tr>';
		 
		 foreach($data as $a)
		 {
		  echo '<tr>';	
          echo '<td width="20%">		  
		  <img src="'.$pluginDir.'images/'.$a["image_name"].'" width="50px" height="40px" title="'.$a["image_name"].'" /></td>';
		  echo '<td width="50%" align="left"><h3>'.$a["image_name"].'</h3></td>';
		  echo '<td width="20%"><img src="'.$pluginDir.'b_drop.png" width="15px" height="15px" onclick="delete1('.$a["id"].','."'".$a["image_name"]."'".');" title="Delete" /></td>';
		  echo '</tr>';
		 }
		 echo '</table>';
		}
		echo '</div>';
		echo '<br /><br />';
		echo '<div style="border:1px solid #666; width:60%;">
		      <h2>HOW TO PUT THE SLIDESHOW INTO A PAGE/POST</h2>
			  <h4>Simply insert the following shortcode anywhere you want into the page/post:[myslideshow]</h4>
			  </div>';
}	          

add_action('wp_head', 'add_slideshowjs');
function add_slideshowjs() {
	$ss = get_option('slideshow_enable');
	if($ss) {
		$url = get_bloginfo("url").'/wp-content/plugins/bpc_slideshow';
		
		echo '<script language="javascript" src="'.$url.'/js/jquery-1.4.2.min.js"></script>';
		echo '<script language="javascript" src="'.$url.'/js/yahoo-dom-event.js"></script>';
		echo '<script language="javascript" src="'.$url.'/js/slideshow.js"></script>';
		echo '<link rel="stylesheet" href="'.$url.'/css/slideshow.css" type="text/css" media="screen" />';
		echo '<script language="javascript">';
		$duration = get_option('slideshow_duration');
		if(!$duration) $duration = 2000;
		echo "duration = ".$duration.";";
		$width = get_option('slideshow_width');
		if(!$width) $width = 400;
		echo "width = ".$width.";";
		$height = get_option('slideshow_image_height');
		if(!$height) $height = 160;
		echo "imgheight=".$height.";";
		$imgwidth  = get_option('slideshow_image_width');
		if(!$imgwidth) $imgwidth = 240;
		echo "imgwidth=".$imgwidth.";";
		
		$onlyimages  = "1";
		echo "onlyimages='".$onlyimages."';";
		echo "base_url='".get_bloginfo("url")."';";		
		echo "content_url='".get_bloginfo("url")."?slideshow_content=1'";
				
		echo '</script>';
		
	}
	
}

add_filter('the_content', 'contentss');

function contentss($content) {
	$ss = get_option('slideshow_enable');
	if($ss) {
		return str_replace('[myslideshow]','<div id="slideshow"><div id="seg1"></div><div id="seg2"></div></div><br style="clear:both;">',$content);
	}else {
		return str_replace('[myslideshow]','',$content);
	}
}

function slideshow_content() {
	if($_GET['slideshow_content']==1) {
		$template_name = 'content';
	}

	if (isset($template_name)) {
		if (file_exists(ABSPATH."wp-content/plugins/bpc_slideshow/{$template_name}.php")) require_once (ABSPATH."wp-content/plugins/bpc_slideshow/{$template_name}.php");
		exit;
	} 
}

add_action('template_redirect', 'slideshow_content');
?>
