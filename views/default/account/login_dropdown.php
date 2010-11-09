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

<div id="login_dropdown">
	<div id="signin_button" class="signin_button">
		<a href="<?php echo $CONFIG->url; ?>pg/login" class="signin"><span><?php echo elgg_echo('login') ?></span></a>
	</div>
	<fieldset id="signin_menu">
		<?php echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$login_url}action/login")); ?>
	</fieldset>
</div>

<script type="text/javascript">
$(document).ready(function() {

	$(".signin").click(function(e) {
		e.preventDefault();
		$("fieldset#signin_menu").toggle();
		$(".signin").toggleClass("menu_open");
		$('.login_textarea.name').focus();
	});

	$("fieldset#signin_menu").mouseup(function() {
		return false
	});

	$(document).mouseup(function(e) {
		if($(e.target).parent("a.signin").length==0) {
			$(".signin").removeClass("menu_open");
			$("fieldset#signin_menu").hide();
		}
	});

});
</script>