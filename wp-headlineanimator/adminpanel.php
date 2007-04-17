<?php
  $wpc_image = get_option('wpc_image');
  $wpc_font = get_option('wpc_font');
  $wpc_target = get_option('wpc_target');
  $wpc_text = get_option('wpc_text');
  $wpc_textcol = get_option('wpc_textcol');
  $wpc_textsize = get_option('wpc_textsize');
  $wpc_newssize = get_option('wpc_newssize');
  $wpc_wantdate = get_option('wpc_wantdate');
  $wpc_dateformat = get_option('wpc_dateformat');
  $wpc_mode = get_option('wpc_mode');

		
if ( !function_exists('imagegif') ) {
		echo '<div class="wrap"><h2>Error!</h2><h3><font color="red">You have no GIF Support in your GDLib. This Plugin will not work</font></h3></div>';
}
?>

<form name="wpc-settings" action="" method="post">

<div class="wrap">
  <h2> WP-Headline Animator</h2>

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
		<th>&nbsp;</th>
			  <td colspan="2">If your TTF file is in the same directory as this plugin, I am expecting "<?= ABSPATH  ?>wp-content/plugins/wp-headlineanimator/myfont.ttf" here.</td>
	</tr>
	<tr valign="top">
		<th>&nbsp;</th>
		<td colspan="2">&nbsp;</td>
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

<?php if ($wpc_mode == 'on') { ?>
    <tr valign="top">
      <th scope="row" width="33%"><label for="wpc_labels">Text size:</label></th>
      <td>
        <input name="wpc_textsize" type="text" size="40" value="<?php echo $wpc_textsize; ?>"/>
      </td>
      <td>&nbsp;</td>
    </tr>

    <tr valign="top">
      <th scope="row" width="33%"><label for="wpc_labels">Newstext size:</label></th>
      <td>
        <input name="wpc_newssize" type="text" size="40" value="<?php echo $wpc_newssize; ?>"/>
      </td>
      <td>&nbsp;</td>
    </tr>

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
      <td><input name="wpc_dateformat" type="text" size="40" value="<?php echo $wpc_dateformat; ?>"/></td>
	  <td><label>see php <a href="http://www.php.net/date">date()</a></label></td>
    </tr>
    <tr valign="top">
      <th>&nbsp;</th>
      <td colspan="2">&nbsp;</td>
    </tr>
<?php } ?>
	</table>
</div>
<? if ( file_exists( ABSPATH.'/'.$wpc_target.'.gif') ) { ?>
<div class="wrap">
<h2> Integration</h2>		
<table width="100%" cellspacing="2" cellpadding="5" class="editform" summary="WP-Headline Animator Integration" border="0">
    <tr valign="top">
      <th>HTML Code for your Animator:</th>
      <td colspan="2"> <label>&lt;a href="<?php echo get_settings('siteurl').'/'; ?>"&gt;&lt;img src="<?php echo get_settings('siteurl').'/'.$wpc_target; ?>.gif"&gt;&lt;/a&gt;</label></td>
    </tr>
	<tr valign="top">
			<th>BBCode for your Animator:</th>
			<td colspan="2"><label>[url=<?php echo get_settings('siteurl').'/'; ?>][img]<?php echo get_settings('siteurl').'/'.$wpc_target; ?>.gif[/img][/url]</label></td>
	</tr>
    <tr valign="top">
      <th>&nbsp;</th>
      <td colspan="2"><a href="<?php echo get_settings('siteurl').'/'; ?>"><img src="<?php echo get_settings('siteurl').'/'.$wpc_target; ?>.gif" alt="Headline Animator" /></a></td>
    </tr>
</table>
<?php } ?>
</div>
		
<table width="100%" cellspacing="2" cellpadding="5" class="editform" summary="WP-Headline Animator Settings 2" border="0">		  
	<tr valign="top">
		<th scope="row" width="33%">&nbsp;</th>
		<td align="right" colspan="2"><label for="wpc_labels"><strong>Advanced Configuration:</strong></label> <input name="wpc_mode" type="checkbox" value="on" <?php if($wpc_mode == 'on') { echo "checked=\"checked\""; } ?> /></td>
	</tr>
	<tr valign="top">
		<th scope="row" width="33%">&nbsp;</th>
		<td align="right" colspan="2"><div class="submit"> <input type="hidden" name="submitted" /><input type="submit" name="Submit" value="<?php _e($rev_action);?> Update Settings &raquo;" /></div></td>
	</tr>
</table>

</form>