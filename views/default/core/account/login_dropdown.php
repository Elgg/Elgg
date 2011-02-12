<?php
/**
 * Elgg drop-down login form
 *
 * @todo Forms 1.8: Convert to use elgg_view_form()
 */

if (elgg_is_logged_in()) {
	return true;
}

/**
 * @todo forms/login should take a "forward_to" argument, or something similar
 * Enter description here ...
 * @var unknown_type
 */
$form_body = elgg_view('forms/login');
$form_body .= "<input type='hidden' name='returntoreferer' value='true' />";

$login_url = elgg_get_site_url();
if (elgg_get_config('https_login')) {
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
<?php //@todo JS 1.8: no ?>
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