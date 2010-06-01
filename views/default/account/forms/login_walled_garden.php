<?php
/**
* Walled Garden Login Form
*/
	 
global $CONFIG;

$form_body = "<label>" . elgg_echo('username') . "<br />" . elgg_view('input/text', array('internalname' => 'username', 'class' => 'login_textarea username')) . "</label>";
$form_body .= "<br />";
$form_body .= "<label>" . elgg_echo('password') . "<br />" . elgg_view('input/password', array('internalname' => 'password', 'class' => 'login_textarea')) . "</label><br />";
$form_body .= elgg_view('input/hidden', array('internalname' => 'returntoreferer', 'value' => 'true'));
$form_body .= elgg_view('input/submit', array('value' => elgg_echo('login')));
$form_body .= "<div class='remember_me'><label><input type='checkbox' name='persistent' checked value='true' />".elgg_echo('user:persistent')."</label></div>";

$register = elgg_echo('register');
$lost_password = elgg_echo('user:password:lost');
$form_body .= '<p class="lost_password">';
$form_body .= $CONFIG->allow_registration ? "<a class=\"registration_link\" href=\"{$vars['url']}pg/register/\">$register</a> | " : '';
$form_body .= "<a class='forgotten_password_link' href=\"{$login_url}account/forgotten_password.php\">$lost_password</a>";
$form_body .= '</p>';

$login_url = $vars['url'];
if ((isset($CONFIG->https_login)) && ($CONFIG->https_login)) {
	$login_url = str_replace("http", "https", $vars['url']);
}
?>
<style type="text/css">
	body {background:white !important; text-align: center !important;}
</style>
<h2><?php echo elgg_echo('login'); ?></h2>
<?php
	echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$login_url}action/login"));
	echo elgg_view('login/extend'); // view for plugins to extend
?>

<?php
if ($CONFIG->allow_registration) {
	$title = elgg_echo('register');
	$body = elgg_view("account/forms/register", array(
		'friend_guid' => (int) get_input('friend_guid', 0),
		'invitecode' => get_input('invitecode'),
	));

	echo <<<__HTML
<div id="registration_form" class="registration_form margin_top hidden">
	<h2>$title</h2>
	$body
</div>
__HTML;
}
?>

<div id="lostpassword_form" class="lostpassword_form margin_top hidden">
	<?php
	$lostpassword_form_body = "<p>" . elgg_echo('user:password:text') . "</p>";
	$lostpassword_form_body .= "<p class='margin_none'><label>". elgg_echo('username') . " "
		. elgg_view('input/text', array('internalname' => 'username', 'class' => 'login_textarea lostusername')) . "</label></p>";
	$lostpassword_form_body .= elgg_view('input/captcha');
	$lostpassword_form_body .= "<p>" . elgg_view('input/submit', array('value' => elgg_echo('request'))) . "</p>";
	
	?>
	<h2><?php echo elgg_echo('user:password:lost'); ?></h2>
	<?php
		echo elgg_view('input/form', array(
			'action' => "{$vars['url']}action/user/requestnewpassword",
			'body' => $lostpassword_form_body,
			'class' => "margin_top"
		));
	?>
</div>

<script type="text/javascript"> 
$(document).ready(function() { 	
	$('input.username').focus();
	
	$('a.registration_link').click(function(e) {
		e.preventDefault();
		if ($('#lostpassword_form').is(':visible')) {
			elgg_slide_toggle($('a.forgotten_password_link'), '.walledgardenlogin', '.lostpassword_form');
		}
		
		elgg_slide_toggle(this, '.walledgardenlogin', '.registration_form');
	});
	
	$('a.forgotten_password_link').click(function(e) {
		e.preventDefault();
		if ($('#registration_form').is(':visible')) {
			elgg_slide_toggle($('a.registration_link'), '.walledgardenlogin', '.registration_form');
		}
		
		elgg_slide_toggle(this, '.walledgardenlogin', '.lostpassword_form');
		$('input.lostusername').focus();
	});
});
</script>
