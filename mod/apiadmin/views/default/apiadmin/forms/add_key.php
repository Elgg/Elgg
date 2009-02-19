<?php
	$ref_label = elgg_echo('apiadmin:yourref');
	$ref_control = elgg_view('input/text', array('internalname' => 'ref'));
	$gen_control = elgg_view('input/submit', array('value' => elgg_echo('apiadmin:generate')));
	
	$form_body = <<< END
	<div class="contentWrapper">
		<p>$ref_label: $ref_control $gen_control</p>
	</div>
END;

	echo elgg_view('input/form', array('action' => "{$vars['url']}action/apiadmin/generate", "body" => $form_body));
?>