<?php
/**
 * Elgg login box
 *
 * @package Elgg
 * @subpackage Core
 */

$login_url = elgg_get_site_url();
if (elgg_get_config('https_login')) {
	$login_url = str_replace("http:", "https:", $login_url);
}
?>

<div id="login">
<h2><?php echo elgg_echo('login'); ?></h2>
	<?php
		echo elgg_view_form('login', array('action' => "{$login_url}action/login"));
	?>
</div>
<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
	$(document).ready(function() { $('input[name=username]').focus(); });
</script>
