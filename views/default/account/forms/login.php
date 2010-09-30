<?php
/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$form_body = "<label>" . elgg_echo('loginusername') . "<br />" . elgg_view('input/text', array('internalname' => 'username', 'class' => 'login_textarea')) . "</label>";
$form_body .= "<br />";
$form_body .= "<label>" . elgg_echo('password') . "<br />" . elgg_view('input/password', array('internalname' => 'password', 'class' => 'login_textarea')) . "</label><br />";

$form_body .= elgg_view('input/hidden', array('internalname' => 'returntoreferer', 'value' => 'true'));
$form_body .= elgg_view('input/submit', array('value' => elgg_echo('login')));
$form_body .= "<div class='persistent_login'><label><input type='checkbox' name='persistent' value='true' />".elgg_echo('user:persistent')."</label></div>";

$form_body .= elgg_view('login/extend');

$form_body .= "<p class='loginbox'>";
$form_body .= $CONFIG->allow_registration ? "<a href=\"{$vars['url']}pg/register/\">" . elgg_echo('register') . '</a> | ' : '';
$form_body .= "<a href=\"{$vars['url']}pages/account/forgotten_password.php\">" . elgg_echo('user:password:lost') . "</a></p>";

$login_url = $vars['url'];
if ((isset($CONFIG->https_login)) && ($CONFIG->https_login)) {
	$login_url = str_replace("http", "https", $vars['url']);
}
?>

<div id="login">
<h2><?php echo elgg_echo('login'); ?></h2>
	<?php
		echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$login_url}action/login"));
	?>
</div>
<script type="text/javascript">
	$(document).ready(function() { $('input[name=username]').focus(); });
</script>