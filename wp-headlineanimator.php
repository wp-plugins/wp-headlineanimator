<?php
/**
 * @package WP-HeadlineAnimator
 * @author Viktoria Rei Bauer
 * @version 1.7.3
 */
/*
 Plugin Name: WP-HeadlineAnimator
 Plugin URI: http://www.stargazer.at/projekte
 Description: Generates a graphic like the FB Headline Animator. More info in my blog posts on my.stargazer.at...
 Version: 1.7.3
 Author: Viktoria Rei Bauer
 Author URI: http://my.stargazer.at/

 Copyright 2011 Viktoria Rei Bauer  (email : headlineanimator@stargazer.at)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 */

// Securing against direct calls
if (!defined('ABSPATH')) die("Called directly. Taking the emergency exit.");

function wpc_write() {
  require_once('GifMerge.class.php');

  if ( !get_option('wpc_font') || !file_exists(get_option('wpc_font')) ) {
	  return 0;
  }

  if ( !file_exists( ABSPATH . '/' .get_option('wpc_image')) ) {
	  return 0;
  }
  $frame	= array();
  $i		= array();
  $d		= array();
  $myposts 	= get_posts('numberposts='.get_option('wpc_artnum').'&order=DESC&orderby=post_date');
  $counter	= 0;

  foreach($myposts as $post) :
  	$text 		= $post->post_title;
  	if ( !get_option('wpc_dateformat') ) {
  		$textdate	= date('M jS',strtotime($post->post_date));
	} else {
		$textdate	= date( get_option('wpc_dateformat'),strtotime($post->post_date));
	}

	if ( function_exists('qtrans_useDefaultLanguage') ) $text = qtrans_useDefaultLanguage($text); // just to be sure

// do some option handling
	if ( get_option('wpc_wantdate') == 'on' && $text ) $text = $textdate  . ' - ' . $text;
	if (strlen($text) > 30) $text = substr($text, 0, 30).'...';
	if (strlen($text) < 20) {
		$xmove       = 200 - (strlen($text) * 3.5);
	} else {
		$xmove       = 200 - (strlen($text) * 3.5 );
	}

// constructing the image
	$picture_src =  ABSPATH . '/' . get_option('wpc_image');
	$font        = get_option('wpc_font');
	$picture     = readpic($picture_src);
	$colarray	 = html2rgb(get_option('wpc_textcol'));
	$color       = ImageColorAllocate( $picture, $colarray[0], $colarray[1], $colarray[2] );


// imagettftext ( image, size, angle,  x,  y, color , font , text )
	if ($text) imagettftext(  $picture,   get_option('wpc_newssize'),     0, $xmove, 58, $color, $font, $text);
	imagettftext(  $picture,   get_option('wpc_textsize'),     0, 100, 30, $color, $font, get_option('wpc_text') );
	$frame[$counter] = gif2string($picture);
	imagedestroy( $picture );
	$counter++;
  endforeach;

// arrange frames
  $counter=0;
  $frame=array_reverse($frame);
  foreach($frame as $pic) :
	$i[$counter] = array_pop($frame);
	$counter++;
	$i[$counter] = gif2string(readpic($picture_src));
	$counter++;

// compute the frame handling delays.
		array_push ($d, get_option('wpc_pictime'), get_option('wpc_nopictime'));
  endforeach;



/*
        GIFEncoder constructor:
        =======================

        image_stream = new GIFEncoder    (
                            URL or Binary data    'Sources'
                            int                    'Delay times'
                            int                    'Animation loops'
                            int                    'Disposal'
                            int                    'Transparent red, green, blue colors'
                            int                    'Source type'
                        );
*/

    $anim = new GIFEncoder ( 	$i,
				$d,
				0,
				0,
				0,0,0,
				'bin'
			);

// now slammin' the remix together for some 'bling bling'
    $animgif = $anim->getAnimation();

// we have to write it anyways
	$f = fopen( ABSPATH . '/' . get_option('wpc_target').'.gif' , "w");
	fwrite($f, $animgif);


	if ( get_option('wpc_remotetarget')=='on' ) {
		export2ftp( ABSPATH . '/' . get_option('wpc_target').'.gif' );
	}

	fclose($f);
}

function readpic($picture_src) {
	/**
	* Picture loader. Life is like a box of chocolate - we don't know what we'll get.
	* So let's pretend the user knows what he's doing, using valid extensions...
	**/

	$extension = strtolower(strrchr($picture_src, '.'));
	switch ($extension) {
		case '.gif':
			$picture     = imagecreatefromgif( $picture_src );
			break;
		case '.jpg':
		case '.jpeg':
			$picture     = imagecreatefromjpeg( $picture_src );
			break;
		case '.png':
			$picture     = imagecreatefrompng( $picture_src );
			break;
		default:
			wp_die('Unknown image format');

	}
	return $picture;
}

function export2ftp($src_image) {
	/**
	 *  Some people want to have the animator offsite - here's the transfer
	 **/
	if ( !get_option('wpc_ftp_server') ) return;
	$ftpcon = ftp_connect(get_option('wpc_ftp_server'));
	$login_result = ftp_login($ftpcon, get_option('wpc_ftp_user'), get_option('wpc_ftp_pass'));
	if ((!$ftpcon) || (!$login_result)) {
		echo '<div class="wrap"><h2>' . __('Error', 'wp-headlineanimator') . '!</h2><h3><font color="red">' . __('FTP connection has failed! Check Hostname and Login!', 'wp-headlineanimator') . '</font></h3></div>';
		exit;
	}
	//        ftp_fput($conn_id, $file                      , $fp         , FTP_ASCII)
	$upload = ftp_put($ftpcon, '/'.get_option('wpc_ftp_target').'.gif' , $src_image, FTP_BINARY);
	if (!$upload) {
		echo '<div class="wrap"><h2>' . __('Error', 'wp-headlineanimator') . '!</h2><h3><font color="red">' . __('FTP upload has failed!', 'wp-headlineanimator') . '</font></h3></div>';
	}
	ftp_close($ftpcon);
}

function gif2string($image) {
	/**
	 * catch the output of imagegif as we need gif images to pass it to the MergerClass
	 **/

	ob_start();
	$contents = ob_get_contents();
	if ($contents !== false) ob_clean(); else ob_start();
	imagegif($image);
	$data = ob_get_contents();
	if ($contents !== false) {
		ob_clean();
		echo $contents;
	}
	else ob_end_clean();
	return $data;
}

function html2rgb($color)
{
	if ($color[0] == '#')
		$color = substr($color, 1);

	if (strlen($color) == 6)
		list($r, $g, $b) = array($color[0].$color[1],
			 $color[2].$color[3],
	$color[4].$color[5]);
	elseif (strlen($color) == 3)
		list($r, $g, $b) = array($color[0], $color[1], $color[2]);
	else
		return false;

	$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

	return array($r, $g, $b);
}

function wpc_add_page() {
        add_submenu_page('options-general.php', 'WP-Headline Animator', 'WP-Headline Animator', 10, __FILE__, 'wpc_options_page');
}


function wpc_install() {
	if ( !get_option('wpc_image') ) {
		update_option('wpc_image', ABSPATH . 'background.png');
		update_option('wpc_target', 'animator');
		update_option('wpc_textcol', '#740204');
		update_option('wpc_textsize', 16);
		update_option('wpc_newssize', 10);
		update_option('wpc_text', 'Now online:');
		update_option('wpc_wantdate', 'off');
		update_option('wpc_mode', 'off');
		update_option('wpc_artnum', 5);
		update_option('wpc_pictime', 300);
		update_option('wpc_nopictime', 50);
		update_option('wpc_remotetarget', 'off');
		update_option('wpc_ftp_server', 'localhost');
		update_option('wpc_ftp_user', 'anonymous');
		update_option('wpc_ftp_pass', 'me@mymail.com');
		update_option('wpc_ftp_path', '/myhome/');
		update_option('wpc_ftp_target', '/animator');
	}
}

function wpc_options_page() {

  if(isset($_POST['submitted'])){
	  if ($_POST['wpc_image']) update_option('wpc_image', $_POST['wpc_image']);
	  if ($_POST['wpc_target']) update_option('wpc_target', $_POST['wpc_target']);
	  if ($_POST['wpc_font']) update_option('wpc_font', $_POST['wpc_font']);
	  if ($_POST['wpc_text']) update_option('wpc_text', $_POST['wpc_text']);
	  if ($_POST['wpc_textcol']) update_option('wpc_textcol', $_POST['wpc_textcol']);
	  if ($_POST['wpc_wantdate']) update_option('wpc_wantdate', $_POST['wpc_wantdate']);
	  if ($_POST['wpc_dateformat']) update_option('wpc_dateformat', $_POST['wpc_dateformat']);
	  if ($_POST['wpc_ftp_user']) update_option('wpc_ftp_user', $_POST['wpc_ftp_user']);
	  if ($_POST['wpc_ftp_pass']) update_option('wpc_ftp_pass', $_POST['wpc_ftp_pass']);
	  if ($_POST['wpc_ftp_path']) update_option('wpc_ftp_path', $_POST['wpc_ftp_path']);
	  if ($_POST['wpc_ftp_target']) update_option('wpc_ftp_target', $_POST['wpc_ftp_target']);

	  if ($_POST['wpc_textsize'] && intval($_POST['wpc_textsize']) > 0 ) {
		  update_option('wpc_textsize', intval($_POST['wpc_textsize']));
	  } else {
		  update_option('wpc_textsize', 16);
	  }
	  if ($_POST['wpc_newssize'] && intval($_POST['wpc_newssize']) > 0 ) {
		  update_option('wpc_newssize', intval($_POST['wpc_newssize']));
	  } else {
		  update_option('wpc_newssize', 10);
	  }
	  if ($_POST['wpc_artnum'] && intval($_POST['wpc_artnum']) > 0 ) {
		  update_option('wpc_artnum', intval($_POST['wpc_artnum']));
	  } else {
		  update_option('wpc_artnum', 5);
	  }
	  if ($_POST['wpc_pictime'] && intval($_POST['wpc_pictime']) >= 0 ) {
		  update_option('wpc_pictime', intval($_POST['wpc_pictime']));
	  } else {
		  update_option('wpc_pictime', 300);
	  }
	  if ($_POST['wpc_nopictime'] && intval($_POST['wpc_nopictime']) >= 0 ) {
		  update_option('wpc_nopictime', intval($_POST['wpc_nopictime']));
	  } else {
		  update_option('wpc_nopictime', 50);
	  }

	  update_option('wpc_ftp_server', $_POST['wpc_ftp_server']);
	  update_option('wpc_remotetarget', $_POST['wpc_remotetarget']);
	  update_option('wpc_mode', $_POST['wpc_mode']);
    wpc_write();
  }

include('adminpanel.php');

}

function wpc_textdomain() {
	if (function_exists('load_plugin_textdomain')) {
		if ( !defined('WP_PLUGIN_DIR') ) {
			load_plugin_textdomain('wp-headlineanimator', str_replace( ABSPATH, '', dirname(__FILE__) ) . '/languages');

		} else {
			load_plugin_textdomain('wp-headlineanimator', false, dirname( plugin_basename(__FILE__) ) . '/languages');
		}

	}
}

  add_action('init', 'wpc_textdomain');
  add_action('admin_menu'  , 'wpc_add_page');
  add_action('activate_wp-headlineanimator/wp-headlineanimator.php', 'wpc_install');
  add_action('publish_post', 'wpc_write');
  add_action('edit_post'   , 'wpc_write');
  add_action('delete_post' , 'wpc_write');
?>
