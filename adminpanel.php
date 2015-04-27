<?php
/**
 * @package WP-HeadlineAnimator-Admin
 * @author Viktoria Rei Bauer
 * @version 1.7.3
 */
/*
 * Function library used with WP-BlackCheck
 *
 * Copyright 2011 Viktoria Rei Bauer (email : headlineanimator@stargazer.at)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 */


// Securing against direct calls
if (!defined('ABSPATH')) die("Called directly. Taking the emergency exit.");

  $wpc_image 		= get_option('wpc_image');
  $wpc_font 		= get_option('wpc_font');
  $wpc_target 		= get_option('wpc_target');
  $wpc_text 		= get_option('wpc_text');
  $wpc_textcol 		= get_option('wpc_textcol');
  $wpc_textsize 	= get_option('wpc_textsize');
  $wpc_newssize 	= get_option('wpc_newssize');
  $wpc_wantdate 	= get_option('wpc_wantdate');
  $wpc_dateformat	= get_option('wpc_dateformat');
  $wpc_mode 		= get_option('wpc_mode');
  $wpc_artnum 		= get_option('wpc_artnum');
  $wpc_pictime		= get_option('wpc_pictime');
  $wpc_nopictime	= get_option('wpc_nopictime');
  $wpc_remotetarget	= get_option('wpc_remotetarget');
  $wpc_ftp_server	= get_option('wpc_ftp_server');
  $wpc_ftp_user		= get_option('wpc_ftp_user');
  $wpc_ftp_pass		= get_option('wpc_ftp_pass');
  $wpc_ftp_path		= get_option('wpc_ftp_path');
  $wpc_ftp_target	= get_option('wpc_ftp_target');

if ( !function_exists('imagegif') ) {
	echo '<div class="wrap"><h2>' . __('Error', 'wp-headlineanimator') . '!</h2><h3><font color="red">' . __('You have no GIF Support in your GDLib. This Plugin will not work!', 'wp-headlineanimator') . '</font></h3></div>';;
}
?>



<div class="wrap">
<?php
echo '<div id="icon-options-general" class="icon32"><br /></div><h2>' . __('WP-HeadlineAnimator - Settings', 'wp-headlineanimator') . '</h2>';

echo '<p>' . __('Welcome to the settings page for WP-HeadlineAnimator. You are able to configure the plugin to your needs. ', 'wp-headlineanimator') . '<br />';
echo sprintf ( __('For more information visit <a href="%s" target="_blank">this page</a>.', 'wp-headlineanimator'), 'http://my.stargazer.at/tag/wp-headlineanimator/' ) . ' ';
echo sprintf ( __('If you found a bug, please report it at <a href="%s" target="_blank">this page</a>.', 'wp-headlineanimator'), 'http://bugs.stargazer.at/' ) . '</p>';

if(isset($_POST['submitted'])) echo '<div style="border:1px outset gray; margin:.5em; padding:.5em; background-color:#efd;">' . __('Settings updated.', 'wp-headlineanimator') . '</div>';

echo '<h3>' . __('Settings', 'wp-headlineanimator') . '</h3>';
?>

<form name="wpc-settings" action="" method="post">
<table cellspacing="2" cellpadding="5" class="editform" summary="WP-HeadlineAnimator Settings" border="0">
	<tr height="30px">
		<td colspan="3"><strong><?php _e('Image Settings:', 'wp-headlineanimator'); ?></strong></td>
	</tr>
	<tr>
		<td><?php if ( !file_exists( ABSPATH.'/'.get_option('wpc_image')) ) echo '<font color="#ff0000">'; ?><?php  _e('Background image:', 'wp-headlineanimator'); ?><?php if ( !file_exists( ABSPATH.'/'.get_option('wpc_image') ) ) echo '</font>'; ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_image" type="text" size="100" value="<?php echo $wpc_image ?>" /></td>
	</tr>
	<tr>
		<td colspan="3"><small><?php  _e('(relative to your WP install)', 'wp-headlineanimator'); ?><small></td>
	</tr>
	<tr>
		<td><?php if ( !get_option('wpc_font') || !file_exists(get_option('wpc_font')) ) echo '<font color="#ff0000">'; ?><?php  _e('Font file:', 'wp-headlineanimator'); ?><?php if ( !get_option('wpc_font') || !file_exists(get_option('wpc_font')) ) echo '</font>'; ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_font" type="text" size="100" value="<?php echo $wpc_font; ?>" /></td>
	</tr>
	<tr>
		<td colspan="3"><small><?php echo sprintf ( __('If your TTF file is in the same directory as this plugin, I am expecting it here: %s', 'wp-headlineanimator'), ABSPATH .str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . 'myfont.ttf' ) ?><small></td>
	</tr>
	<tr height="30px">
		<td colspan="3"><strong><?php _e('Storage Settings:', 'wp-headlineanimator'); ?></strong></td>
	</tr>
	<tr>
		<td><?php  _e('Filename of the HeadlineAnimator:', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_target" type="text" size="35" value="<?php echo $wpc_target; ?>" />.gif</td>
	</tr>
	<tr>
		<td colspan="3"><small><?php  _e('(relative to your WP install)', 'wp-headlineanimator'); ?><small></td>
	</tr>
	<tr>
		<td colspan="3"><input name="wpc_remotetarget" type="checkbox" value="on" <?php if($wpc_remotetarget == 'on') { echo "checked=\"checked\""; } ?> /> <?php  _e('Store animator on remote FTP Server', 'wp-headlineanimator'); ?></td>
	</tr>
<?php if ( $wpc_remotetarget=='on' ) { ?>
	<tr>
		<td><?php  _e('Remote FTP Host:', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_ftp_server" type="text" size="35" value="<?php echo $wpc_ftp_server; ?>" /></td>
	</tr>
	<tr>
		<td><?php  _e('FTP User:', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_ftp_user" type="text" size="35" value="<?php echo $wpc_ftp_user; ?>" /></td>
	</tr>
	<tr>
		<td><?php  _e('FTP Password:', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_ftp_pass" type="text" size="35" value="<?php echo $wpc_ftp_pass; ?>" /></td>
	</tr>
	<tr>
		<td><?php  _e('FTP path and target:', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_ftp_target" type="text" size="35" value="<?php echo $wpc_ftp_target; ?>" />.gif</td>
	</tr>
	<tr>
		<td colspan="3"><small><?php  _e('(remote path starting with "/")', 'wp-headlineanimator'); ?><small></td>
	</tr>
<?php } ?>
	<tr height="30px">
		<td colspan="3"><strong><?php _e('Design Settings:', 'wp-headlineanimator'); ?></strong></td>
	</tr>
	<tr>
		<td><?php  _e('Text on Picture:', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_text" type="text" size="40" value="<?php echo $wpc_text; ?>" /></td>
	</tr>
		<tr>
		<td><?php  _e('Text color:', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_textcol" type="text" size="10" value="<?php echo $wpc_textcol; ?>" /></td>
	</tr>
	<tr height="30px">
		<td colspan="3"><small><?php  _e('(HTML Notation like #740204)', 'wp-headlineanimator'); ?></small></td>
	</tr>
	<tr>
		<td><?php  _e('Text size:', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_textsize" type="text" size="4" value="<?php echo $wpc_textsize; ?>" /></td>
	</tr>
	<tr>
		<td><?php  _e('Newstext size:', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_newssize" type="text" size="4" value="<?php echo $wpc_newssize; ?>" /></td>
	</tr>

	<tr>
		<td colspan="3"> <input name="wpc_mode" type="checkbox" value="on" <?php if($wpc_mode == 'on') { echo "checked=\"checked\""; } ?> /> <?php  _e('Advanced Configuration', 'wp-headlineanimator'); ?></td>
	</tr>


	<tr>
		<td colspan="3"><input name="wpc_wantdate" type="checkbox" value="on" <?php if($wpc_wantdate == 'on') { echo "checked=\"checked\""; } ?> /> <?php  _e('Show date on animator', 'wp-headlineanimator'); ?></td>
	</tr>
<?php if ($wpc_mode == 'on') { ?>
<?php if($wpc_wantdate == 'on') { ?>
	<tr>
		<td><?php  _e('Date format:', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_dateformat" type="text" size="40" value="<?php echo $wpc_dateformat; ?>" /></td>
	</tr>
	<tr height="30px">
		<td colspan="3"><small><?php  echo sprintf ( __('see php manual for <a href="%s" target="_blank">date()</a>.', 'wp-headlineanimator'), 'http://www.php.net/date' ); ?></small></td>
	</tr>
<?php } ?>
	<tr>
		<td><?php  _e('Headlines to display:', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_artnum" type="text" size="4" value="<?php echo $wpc_artnum; ?>" /></td>
	</tr>
	<tr>
		<td><?php  _e('Headline display time (milliseconds):', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_pictime" type="text" size="4" value="<?php echo $wpc_pictime; ?>" /></td>
	</tr>
	<tr>
		<td><?php  _e('Delay between headlines (milliseconds):', 'wp-headlineanimator'); ?></td>
		<td>&nbsp;</td>
		<td><input name="wpc_nopictime" type="text" size="4" value="<?php echo $wpc_nopictime; ?>" /></td>
	</tr>
<?php } ?>

	<tr>
		<td align="right" colspan="3">
		<div class="submit"><input type="hidden" name="submitted" /><input type="submit" name="Submit" value="<?php _e($rev_action, 'wp-headlineanimator');?> <?php _e('Update Settings', 'wp-headlineanimator'); ?> &raquo;" /></div>
		</td>
	</tr>
</table>

<?php if ( file_exists( ABSPATH.'/'.$wpc_target.'.gif') ) { ?>
<?php echo '<h2>' . __('Integration', 'wp-headlineanimator') . '</h2>'; ?>

<table cellspacing="2" cellpadding="5" class="editform" summary="<?php  _e('Integration', 'wp-headlineanimator'); ?>" border="0">
	<tr height="30px">
		<td colspan="3"><?php  _e('HTML Code for your Animator:', 'wp-headlineanimator'); ?></td>
	</tr>
	<tr height="30px">
		<td colspan="3"><input name="wpc_html_code" type="text" size="150" value='<a href="<?php echo get_settings('siteurl').'/'; ?>"><img src="<?php echo get_settings('siteurl').'/'.$wpc_target; ?>.gif"></a>' /></td>
	</tr>
	<tr height="30px">
		<td colspan="3"><?php  _e('bbCode for your Animator:', 'wp-headlineanimator'); ?></td>
	</tr>
	<tr height="30px">
		<td colspan="3"><input name="wpc_bb_code" type="text" size="150" value='[url=<?php echo get_settings('siteurl').'/'; ?>][img]<?php echo get_settings('siteurl').'/'.$wpc_target; ?>.gif[/img][/url]' /></td>
	</tr>
</table>

<?php echo '<h3>' . __('Preview', 'wp-headlineanimator') . '</h3>'; ?>
<p><a href="<?php echo get_settings('siteurl').'/'; ?>"><img src="<?php echo get_settings('siteurl').'/'.$wpc_target; ?>.gif" alt="Headline Animator" /></a></p>

<?php } ?>
</form>

</div>


