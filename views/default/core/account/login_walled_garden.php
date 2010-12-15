<?php
/**
* Walled Garden Login Form
*/
	 
global $CONFIG;

/*
$form_body = "<label>" . elgg_echo('username') . "<br />" . elgg_view('input/text', array('internalname' => 'username', 'class' => 'login-textarea username')) . "</label>";
$form_body .= "<br />";
$form_body .= "<label>" . elgg_echo('password') . "<br />" . elgg_view('input/password', array('internalname' => 'password', 'class' => 'login-textarea')) . "</label><br />";

$form_body .= elgg_view('input/submit', array('value' => elgg_echo('login')));
$form_body .= "<div class='remember_me'><label><input type='checkbox' name='persistent' checked value='true' />".elgg_echo('user:persistent')."</label></div>";

$register = elgg_echo('register');
$lost_password = elgg_echo('user:password:lost');
$form_body .= '<p class="lost_password">';
$form_body .= $CONFIG->allow_registration ? "<a class=\"registration_link\" href=\"".elgg_get_site_url()."pg/register/\">$register</a> | " : '';
$form_body .= "<a class='forgotten_password_link' href=\"{$login_url}pages/account/forgotten_password.php\">$lost_password</a>";
$form_body .= '</p>';
*/

$form_body = elgg_view('forms/login');
$form_body .= elgg_view('input/hidden', array('internalname' => 'returntoreferer', 'value' => 'true'));

$login_url = elgg_get_site_url();
if ((isset($CONFIG->https_login)) && ($CONFIG->https_login)) {
	$login_url = str_replace("http", "https", elgg_get_site_url());
}
?>
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
<div id="registration_form" class="hidden clearfix">
<div id="hiddenform_body" class="clearfix">
	<h2>$title</h2>
	$body
</div><div id="hiddenform_bottom"></div></div>
__HTML;
}
?>
	<?php
	$lostpassword_form_body = "<p>" . elgg_echo('user:password:text') . "</p>";
	$lostpassword_form_body .= "<p class='margin-none'><label>". elgg_echo('username') . " "
		. elgg_view('input/text', array('internalname' => 'username', 'class' => 'login-textarea lostusername')) . "</label></p>";
	$lostpassword_form_body .= elgg_view('input/captcha');
	$lostpassword_form_body .= "<p>" . elgg_view('input/submit', array('value' => elgg_echo('request'))) . "<input class='action-button disabled cancel_request' type='reset' value='Cancel'></p>";
	
	?>
<div id="lostpassword_form" class="hidden clearfix">
	<div id="hiddenform_body" class="clearfix">
		<h2><?php echo elgg_echo('user:password:lost'); ?></h2>
		<?php
			echo elgg_view('input/form', array(
				'action' => "action/user/requestnewpassword",
				'body' => $lostpassword_form_body
			));
		?>
</div><div id="hiddenform_bottom"></div></div>

<script type="text/javascript"> 
$(document).ready(function() {
	$('input.username').focus();
	
	// add cancel button to inline register form
	$('#registration_form').find('input.submit-button').after("<input class='action-button disabled cancel_request' type='reset' value='Cancel'>");
	
	function elgg_slide_hiddenform(activateLink, parentElement, toggleElement) {
		$(activateLink).closest(parentElement).find(toggleElement).animate({"width": "563px", duration: 400});
		$(activateLink).closest(parentElement).animate({"height": "256px", duration: 400}, function() {
			// ewwww dirty.  Webkit has problems when showing images that were hidden.
			// forcing a reload of all the images.
			$('.visual_captcha img').each(function() { $(this).attr('src', $(this).attr('src')); });
		});
		return false;
	}

	$('a.registration_link').click(function(e) {
		e.preventDefault();
		elgg_slide_hiddenform(this, '.walledgardenlogin', '#registration_form');
		$('input.name').focus();
	});
	
	$('a.forgotten_password_link').click(function(e) {
		e.preventDefault();
		elgg_slide_hiddenform(this, '.walledgardenlogin', '#lostpassword_form');
		$('input.lostusername').focus();
	});
	
	$('input.cancel_request').click(function() {
		if ($('#lostpassword_form').is(':visible')) {
			$('#lostpassword_form').fadeOut(400);
			location.reload();
		} else if ($('#registration_form').is(':visible')) {
			$('#registration_form').fadeOut(400);
			location.reload();
		}
		return false;
	});
});
</script>
