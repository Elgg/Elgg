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

if (elgg_is_sticky_form('register')) {
	// An error occurred while submitting the registration form in a lightbox
	forward('register');
}

// render content before head so that JavaScript and CSS can be loaded. See #4032
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$content = $vars["body"];

ob_start(); ?>
<div class="elgg-page elgg-page-walledgarden">
	<div class="elgg-page-messages">
		<?php echo $messages ?>
	</div>
	<div class="elgg-body-walledgarden">
		<?php echo $content ?>
	</div>
</div>
<?php
$body = ob_get_clean();

$body .= elgg_view('page/elements/foot');

$head = elgg_view('page/elements/head', $vars['head']);

echo elgg_view("page/elements/html", array("head" => $head, "body" => $body));
