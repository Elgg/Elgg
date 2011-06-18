<?php
/**
 * Walled Garden Login Form
 *
 * @todo still requires clean up
 */

$reg_url = elgg_normalize_url('register');
$forgot_url = elgg_normalize_url('forgotpassword');
$cancel_button = elgg_view('input/button', array(
	'value' => elgg_echo('cancel'),
	'class' => 'elgg-button-cancel mlm',
));

$form_body = elgg_view('forms/login');
$form_body .= elgg_view('input/hidden', array(
	'name' => 'returntoreferer',
	'value' => 'true',
));

$login_url = elgg_get_site_url();
if (elgg_get_config('https_login')) {
	$login_url = str_replace("http:", "https:", elgg_get_site_url());
}

?>
<h2><?php echo elgg_echo('login'); ?></h2>
<?php
//@todo Forms 1.8: Convert to use elgg_view_form()
echo elgg_view('input/form', array(
	'body' => $form_body,
	'action' => "{$login_url}action/login",
));

if (elgg_get_config('allow_registration')) {
	$title = elgg_echo('register');
	$body = elgg_view_form('register', array(), array(
		'friend_guid' => (int) get_input('friend_guid', 0),
		'invitecode' => get_input('invitecode'),
	));

	echo <<<__HTML
<div id="elgg-walledgarden-registration" class="hidden clearfix">
	<div class="elgg-hiddenform-body" class="clearfix">
		<h2>$title</h2>
		$body
	</div>
	<div class="elgg-hiddenform-bottom"></div>
</div>
__HTML;
}

$title = elgg_echo('user:password:lost');
$body = elgg_view_form('user/requestnewpassword');
echo <<<__HTML
<div id="elgg-walledgarden-lostpassword" class="hidden clearfix">
	<div class="elgg-hiddenform-body" class="clearfix">
		<h2>$title</h2>
		$body
	</div>
	<div class="elgg-hiddenform-bottom"></div>
</div>
__HTML;

//@todo JS 1.8: no
?>
<script type="text/javascript"> 
$(document).ready(function() {
	$('input.username').focus();
	
	// add cancel button to inline forms
	$('#elgg-walledgarden-registration').find('input.elgg-button-submit').after('<?php echo $cancel_button; ?>');
	$('#elgg-walledgarden-lostpassword').find('input.elgg-button-submit').after('<?php echo $cancel_button; ?>');
	
	function elgg_slide_hiddenform(activateLink, parentElement, toggleElement) {
		$(activateLink).closest(parentElement).find(toggleElement).fadeToggle('medium');
	}

	$('a[href="<?php echo $reg_url; ?>"]').click(function(e) {
		e.preventDefault();
		elgg_slide_hiddenform(this, '#elgg-walledgarden-login', '#elgg-walledgarden-registration');
		$('input.name').focus();
	});
	
	$('a[href="<?php echo $forgot_url; ?>"]').click(function(e) {
		e.preventDefault();
		elgg_slide_hiddenform(this, '#elgg-walledgarden-login', '#elgg-walledgarden-lostpassword');
		$('input.lostusername').focus();
	});
	
	$('input.elgg-button-cancel').click(function() {
		if ($('#elgg-walledgarden-lostpassword').is(':visible')) {
			$('a[href="<?php echo $forgot_url; ?>"]').click();
		} else if ($('#elgg-walledgarden-registration').is(':visible')) {
			$('a[href="<?php echo $reg_url; ?>"]').click();
		}
		return false;
	});
});
</script>
