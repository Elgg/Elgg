<?php
/**
 * Walled garden page shell
 *
 * Used for the walled garden index page
 *
 * @uses $vars['head']        Parameters for the <head> element
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

$is_sticky_register = elgg_is_sticky_form('register');
$wg_body_class = 'elgg-body-walledgarden';
$inline_js = '';
if ($is_sticky_register) {
	$wg_body_class .= ' hidden';
	$inline_js = <<<__JS
<script type="text/javascript">
elgg.register_hook_handler('init', 'system', function() {
	$('.registration_link').trigger('click');
});
</script>
__JS;
}

// render content before head so that JavaScript and CSS can be loaded. See #4032
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$content = $vars["body"];

$body = <<<__BODY
<div class="elgg-page elgg-page-walledgarden">
	<div class="elgg-page-messages">
		$messages
	</div>
	<div class="$wg_body_class">
		$content
	</div>
</div>
__BODY;

$body .= elgg_view('page/elements/foot');

$body .= $inline_js;

$head = elgg_view('page/elements/head', $vars['head']);

echo elgg_view("page/elements/html", array("head" => $head, "body" => $body));
