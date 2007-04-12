<?php
/*
Plugin Name: wp-headlineanimator
Plugin URI: http://www.stargazer.at/projekte
Description: Generates a graphic like the FB Headline Animator. More info in my blog posts on my.stargazer.at...
Version: 1.2
Author: Christoph "Stargazer" Bauer
Author URI: http://my.stargazer.at/

For Info see README
*/

/*  Copyright 2007  Christoph "Stargazer" Bauer  (email via http://my.stargazer.at/impressum-kontakt/ )

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

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
  $myposts 	= get_posts('numberposts=5&order=DESC&orderby=post_date');
  $counter	= 0;
 
  foreach($myposts as $post) :
  	$text 		= $post->post_title;
  	if ( !get_option('wpc_dateformat') ) {
  		$textdate	= date('M jS',strtotime($post->post_date));
	} else {
		$textdate	= date( get_option('wpc_dateformat'),strtotime($post->post_date));
	}
	
	if ( function_exists('polyglot_filter') ) $text = polyglot_filter($text); // just to be sure

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
	if ($text) imagettftext(  $picture,   10,     0, $xmove, 58, $color, $font, $text);
	imagettftext(  $picture,   14,     0, 100, 30, $color, $font, get_option('wpc_text') );
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
	array_push ($d, 300, 50);
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

    $f = fopen( ABSPATH . '/' . get_option('wpc_target').'.gif' , "w");
    fwrite($f, $animgif);
    fclose($f);
}

function readpic($picture_src) {
	/**
	*
	* Picture loader. Life is like a box of chocolate - we don't know what we'll get.
	* So let's pretend the user knows what he's doing, using valid extensions...
	*
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


function gif2string($image) {
	/**
	 * 
	 * catch the output of imagegif as we need gif images to pass it to the MergerClass
	 * 
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
		update_option('wpc_image', 'wp-content/plugins/wp-headlineanimator/background.png');
		update_option('wpc_target', 'animator');
		update_option('wpc_textcol', '#740204');
		update_option('wpc_text', 'Now online:');
		update_option('wpc_wantdate', 'off');
	}
}

function wpc_options_page() {

  if(isset($_POST['submitted'])){
    update_option('wpc_image', $_POST['wpc_image']);
    update_option('wpc_target', $_POST['wpc_target']);
    update_option('wpc_font', $_POST['wpc_font']);
    update_option('wpc_text', $_POST['wpc_text']);
	update_option('wpc_textcol', $_POST['wpc_textcol']);
    update_option('wpc_wantdate', $_POST['wpc_wantdate']);
    update_option('wpc_dateformat', $_POST['wpc_dateformat']);

    wpc_write();
  }

  $wpc_image = get_option('wpc_image');
  $wpc_font = get_option('wpc_font');
  $wpc_target = get_option('wpc_target');
  $wpc_text = get_option('wpc_text');
  $wpc_textcol = get_option('wpc_textcol');
  $wpc_wantdate = get_option('wpc_wantdate');
  $wpc_dateformat = get_option('wpc_dateformat');

?>
<div class="wrap">
  <h2> WP-Headline Animator</h2>

<form name="wpc-settings" action="" method="post">


  <table width="100%" cellspacing="2" cellpadding="5" class="editform" summary="WP-Headline Animator Settings" border="0">
    <tr valign="top">
		<th scope="row" width="33%"><label for="wpc_structure">
		<?php if ( !file_exists( ABSPATH.'/'.get_option('wpc_image')) ) echo '<font color="#ff0000">'; ?>Background image:<?php if ( !file_exists( ABSPATH.'/'.get_option('wpc_image') ) ) echo '</font>'; ?>
		</label></th>
      <td width="300px">
        <input name="wpc_image" type="text" size="40" value="<?php echo $wpc_image ?>"/>
      </td>
      <td>(relative to your WP install)</td>
    </tr>

    <tr valign="top">
	<?php if ( !get_option('wpc_font') ) echo '<font color="#ff0000">'; ?>
		<th scope="row" width="33%"><label for="wpc_labels">
		<?php if ( !get_option('wpc_font') || !file_exists(get_option('wpc_font')) ) echo '<font color="#ff0000">'; ?>Font file:<?php if ( !get_option('wpc_font') || !file_exists(get_option('wpc_font')) ) echo '</font>'; ?>
		</label></th>
      <td>
        <input name="wpc_font" type="text" size="40" value="<?php echo $wpc_font; ?>"/>
      </td>
      <td>(TTF File on the server)</td>
    </tr>

    <tr valign="top">
      <th scope="row" width="33%"><label for="wpc_labels">Target:</label></th>
      <td>
        <input name="wpc_target" type="text" size="35" value="<?php echo $wpc_target; ?>"/>.gif
      </td>
      <td>(relative to your WP install)</td>
    </tr>
 
    <tr valign="top">
      <th>&nbsp;</th>
      <td colspan="2">&nbsp;</td>
    </tr>

    <tr valign="top">
      <th scope="row" width="33%"><label for="wpc_labels">Text on Picture:</label></th>
      <td>
        <input name="wpc_text" type="text" size="40" value="<?php echo $wpc_text; ?>"/>
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr valign="top">
      <th scope="row" width="33%"><label for="wpc_labels">Text color:</label></th>
      <td>
        <input name="wpc_textcol" type="text" size="40" value="<?php echo $wpc_textcol; ?>"/>
      </td>
      <td>(HTML Notation like #740204)</td>
    </tr>
			  
			  <!--
			  <?=get_option('wpc_textcol'); ?> =>
			  <? print_r (html2rgb(get_option('wpc_textcol'))); ?>
			  -->
			  
	<tr valign="top">
		<th>&nbsp;</th>
		<td colspan="2">&nbsp;</td>
	</tr>

    <tr valign="top">
      <th scope="row" width="33%"><label for="wpc_labels">Show date on animator:</label></th>
      <td colspan="2">
	<input name="wpc_wantdate" type="checkbox" value="on" <?php if($wpc_wantdate == 'on') { echo "checked=\"checked\""; } ?> />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row" width="33%"><label for="wpc_labels">Date format:</label></th>
      <td colspan="2">
	<input name="wpc_dateformat" type="text" size="40" value="<?php echo $wpc_dateformat; ?>"/>
      </td>
    </tr>
    <tr valign="top">
      <th>&nbsp;</th>
      <td colspan="2">&nbsp;</td>
    </tr>
		      
<? if ( file_exists( ABSPATH.'/'.$wpc_target.'.gif') ) { ?>
    <tr valign="top">
      <th>HTML Code for your Animator:</th>
      <td colspan="2"> &lt;a href="<?php echo get_settings('siteurl').'/'; ?>"&gt;&lt;img src="<?php echo get_settings('siteurl').'/'.$wpc_target; ?>.gif"&gt;&lt;/a&gt;<td>
    </tr>
	<tr valign="top">
			<th>BBCode for your Animator:</th>
			<td colspan="2"> [url=<?php echo get_settings('siteurl').'/'; ?>][img]<?php echo get_settings('siteurl').'/'.$wpc_target; ?>.gif[/img][/url]<td>
	</tr>
    <tr valign="top">
      <th>&nbsp;</th>
      <td colspan="2"><a href="<?php echo get_settings('siteurl').'/'; ?>"><img src="<?php echo get_settings('siteurl').'/'.$wpc_target; ?>.gif"></a></td>
    </tr>
<?php } ?>
  </table>

  <p class="submit"><input type="hidden" name="submitted" /><input type="submit" name="Submit" value="<?php _e($rev_action);?> Update Settings &raquo;" /></p>
</form>

</div>

<?php
}

  add_action('admin_menu'  , 'wpc_add_page');
  add_action('activate_wp-headlineanimator/wp-headlineanimator.php', 'wpc_install');
  add_action('publish_post', 'wpc_write');
  add_action('edit_post'   , 'wpc_write');
  add_action('delete_post' , 'wpc_write');
?>
