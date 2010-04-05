<?php
/**
 * Elgg settings not found message
 * Is saved to the errors register when settings.php cannot be found
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$dbuser = '';
$dbpassword = '';
$dbname = '';
$dbhost = 'localhost';
$dbprefix = 'elgg_';
if (isset($vars['sticky'])) {
	$dbuser = $vars['sticky']['CONFIG_DBUSER'];
	$dbname = $vars['sticky']['CONFIG_DBNAME'];
	$dbhost = $vars['sticky']['CONFIG_DBHOST'];
	$dbprefix = $vars['sticky']['CONFIG_DBPREFIX'];
}


if ($vars['settings.php']) {
	echo elgg_echo('installation:settings:dbwizard:savefail');
?>
<div>
	<textarea><?php echo $vars['settings.php']; ?></textarea>
</div>
<?php
} else {
	echo autop(elgg_echo('installation:error:settings'));
?>
<div>
	<h2><?php echo elgg_echo('installation:settings:dbwizard:prompt'); ?></h2>
	<form method="post">
		<table cellpadding="0" cellspacing="10" style="background:#f1f1f1;">
			<tr><td valign="top"><?php echo elgg_echo('installation:settings:dbwizard:label:user'); ?></td><td valign="top"> <input type="text" name="db_install_vars[CONFIG_DBUSER]" value="<?php echo $dbuser; ?>" /></td></tr>
			<tr><td valign="top"><?php echo elgg_echo('installation:settings:dbwizard:label:pass'); ?></td><td valign="top"> <input type="password" name="db_install_vars[CONFIG_DBPASS]" value="<?php echo $dbpassword; ?>" /></td></tr>
			<tr><td valign="top"><?php echo elgg_echo('installation:settings:dbwizard:label:dbname'); ?></td><td valign="top"> <input type="text" name="db_install_vars[CONFIG_DBNAME]" value="<?php echo $dbname; ?>" /></td></tr>
			<tr><td valign="top"><?php echo elgg_echo('installation:settings:dbwizard:label:host'); ?></td><td valign="top"> <input type="text" name="db_install_vars[CONFIG_DBHOST]" value="<?php echo $dbhost; ?>" /></td></tr>
			<tr><td valign="top"><?php echo elgg_echo('installation:settings:dbwizard:label:prefix'); ?></td><td valign="top"> <input type="text" name="db_install_vars[CONFIG_DBPREFIX]" value="<?php echo $dbprefix; ?>" /></td></tr>
		</table>

		<input type="submit" name="<?php echo elgg_echo('save'); ?>" value="<?php echo elgg_echo('save'); ?>" />
	</form>
</div>
<?php
}
