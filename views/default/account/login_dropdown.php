<?php
/**
 * Elgg drop-down login form
 *
 */

if (isloggedin()) {
	return true;
}

$form_body = elgg_view('account/forms/login');
$form_body .= "<input type='hidden' name='returntoreferer' value='true' />";

$login_url = elgg_get_site_url();
if ((isset($CONFIG->https_login)) && ($CONFIG->https_login)) {
	$login_url = str_replace("http", "https", elgg_get_site_url());
}

?>

<div id="login-dropdown">
	<div id="signin-button" class="signin-button">
		<a href="<?php echo $CONFIG->url; ?>pg/login" class="signin"><span><?php echo elgg_echo('login') ?></span></a>
	</div>
	<fieldset id="signin-menu">
		<?php echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$login_url}action/login")); ?>
	</fieldset>
</div>

<script type="text/javascript">
$(document).ready(function() {

	$(".signin").click(function(e) {
		e.preventDefault();
		$("fieldset#signin-menu").toggle();
		$(".signin").toggleClass("menu-open");
		$('.login-textarea.name').focus();
	});

	$("fieldset#signin-menu").mouseup(function() {
		return false
	});

	$(document).mouseup(function(e) {
		if($(e.target).parent("a.signin").length==0) {
			$(".signin").removeClass("menu-open");
			$("fieldset#signin-menu").hide();
		}
	});

});
</script>