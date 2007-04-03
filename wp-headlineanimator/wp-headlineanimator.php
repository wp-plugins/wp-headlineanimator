<?php
/*
Plugin Name: wp-headlineanimator
Plugin URI: http://www.stargazer.at/
Description: Generates a graphic like the FB Headline Animator
Version: 0.5
Author: Christoph Bauer
Author URI: http://my.stargazer.at/

For Changes see README
*/


function wpc_write() {

  require_once('GifMerge.class.php');

  $counter=0;
  
  $myposts = get_posts('numberposts=5&order=DESC&orderby=post_date');
	  
  foreach($myposts as $post) :
  	$text 		= $post->post_title;
  	$textdate	= date('M jS',strtotime($post->post_date));
  	// $textdate 	= date('M jS', $post->post_date);

	if ( function_exists('polyglot_filter') ) {
      		$text = polyglot_filter($text);
    	}

	if ( get_option('wpc_wantdate') == 'on' && $text ) $text = $textdate  . ' - ' . $text;

// shorten text if too long!
	if (strlen($text) > 30) $text = substr($text, 0, 20).'...';

// adjust spaces for some right alignment
	if (strlen($text) < 20) {
		$xmove       = 200 - (strlen($text) * 2);
	} else {
		$xmove       = 200 - (strlen($text) * 3.5 );
	} 
	
	$picture_src =  ABSPATH . '/' . get_option('wpc_image'); 
	$font        = get_option('wpc_font');
	$picture     = imagecreatefrompng( $picture_src );
	$color       = ImageColorAllocate( $picture, 100, 0, 0 );
	
	// we need a tmp path for gif building
	$tmppath	 = ABSPATH . '/wp-content/plugins/wp-headlineanimator/tmp/';
	
	
	
	// imagettftext ( image, size, angle,  x,  y, color , font , text )
	if ($text) imagettftext(  $picture,   10,     0, $xmove, 58, $color, $font, $text);
	imagettftext(  $picture,   14,     0, 100, 30, $color, $font, get_option('wpc_text') );
	
	imagegif ( $picture, $tmppath . 'tmp-' . $counter  . '.gif' );
	imagedestroy( $picture );
	$counter++;
  endforeach;
 
  
    $i = array( $tmppath . 'tmp-0.gif', $tmppath . 'tmp-5.gif', 
		$tmppath . 'tmp-1.gif', $tmppath . 'tmp-5.gif', 
		$tmppath . 'tmp-2.gif', $tmppath . 'tmp-5.gif', 
		$tmppath . 'tmp-3.gif', $tmppath . 'tmp-5.gif', 
		$tmppath . 'tmp-4.gif', $tmppath . 'tmp-5.gif' );

	// Delay Handler
    $d    = array(300, 50, 300, 50, 300, 50, 300, 50, 300, 50);
   


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
				'url'
			);

    $animgif = $anim->getAnimation();

    $f = fopen( ABSPATH . '/' . get_option('wpc_target').'.gif' , "w");
    fwrite($f, $animgif);
    fclose($f);
}

function wpc_add_page() {
        add_submenu_page('options-general.php', 'WP-Headline Animator', 'WP-Headline Animator', 10, __FILE__, 'wpc_options_page');
}



function wpc_options_page() {

  if(isset($_POST['submitted'])){
    update_option('wpc_image', $_POST['wpc_image']);
    update_option('wpc_target', $_POST['wpc_target']);
    update_option('wpc_font', $_POST['wpc_font']);
    update_option('wpc_text', $_POST['wpc_text']);
    update_option('wpc_wantdate', $_POST['wpc_wantdate']);

    wpc_write();
  }

  $wpc_image = get_option('wpc_image');
  $wpc_font = get_option('wpc_font');
  $wpc_target = get_option('wpc_target');
  $wpc_text = get_option('wpc_text');
  $wpc_wantdate = get_option('wpc_wantdate');

?>
<div class="wrap">
  <h2> WP-Headline Animator</h2>

<form name="wpc-settings" action="" method="post">


  <table width="100%" cellspacing="2" cellpadding="5" class="editform" summary="WP-Headline Animator Settings" border="0">
    <tr valign="top">
      <th scope="row" width="33%"><label for="wpc_structure">Background image:</label></th>
      <td width="300px">
        <input name="wpc_image" type="text" size="40" value="<?php echo $wpc_image ?>"/>
      </td>
      <td>(relative to your WP install)</td>
    </tr>

    <tr valign="top">
      <th scope="row" width="33%"><label for="wpc_labels">Font file:</label></th>
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
      <th scope="row" width="33%"><label for="wpc_labels">Show date on animator:</label></th>
      <td colspan="2">
	<input name="wpc_wantdate" type="checkbox" value="on" <?php if($wpc_wantdate == 'on') { echo "checked=\"checked\""; } ?> />
      </td>
    </tr>

    <tr valign="top">
      <th>&nbsp;</th>
      <td colspan="2">&nbsp;</td>
    </tr>

    <tr valign="top">
      <th>HTML Code for your Animator:</th>
      <td colspan="2"> &lt;a href="<?php echo get_settings('siteurl').'/'; ?>"&gt;&lt;img src="<?php echo get_settings('siteurl').'/'.$wpc_target; ?>.gif"&gt;&lt;/a&gt;<td>
    </tr>

    <tr valign="top">
      <th>&nbsp;</th>
      <td colspan="2"><a href="<?php echo get_settings('siteurl').'/'; ?>"><img src="<?php echo get_settings('siteurl').'/'.$wpc_target; ?>.gif"></a></td>
    </tr>

  </table>

  <p class="submit"><input type="hidden" name="submitted" /><input type="submit" name="Submit" value="<?php _e($rev_action);?> Update Settings &raquo;" /></p>
</form>

</div>

<?php
}

  add_action('admin_menu'  , 'wpc_add_page');

  add_action('publish_post', 'wpc_write');
  add_action('edit_post'   , 'wpc_write');
  add_action('delete_post' , 'wpc_write');
?>
