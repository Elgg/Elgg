<?php
/**
 * Generic form template for install forms
 *
 * @uses $vars['variables']
 * @uses $vars['type'] Type of form: admin, database, settings
 */

$variables = $vars['variables'];
$type = $vars['type'];

$form_body = '';
foreach ($variables as $field => $params) {
	$label = elgg_echo("install:$type:label:$field");
	$help = elgg_echo("install:$type:help:$field");
	$params['internalname'] = $field;

	$form_body .= '<p>';
	$form_body .= "<label>$label</label>";
	$form_body .= elgg_view("input/{$params['type']}", $params);
	$form_body .= "<span class=\"install-help\">$help</span>";
	$form_body .= '</p>';
}

$submit_params = array(
	'value' => elgg_echo('next'),
);
$form_body .= elgg_view('input/submit', $submit_params);

echo $form_body;

?>
<script type="text/javascript">
	var was_submitted = false;
	function elggCheckFormSubmission() {
		if (was_submitted == false) {
			was_submitted = true;
			return true;
		}
		return false;
	}
</script>
