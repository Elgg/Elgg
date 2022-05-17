<?php

elgg_register_error_message('Error message registered in PHP');
elgg_register_success_message('Success message registered in PHP');
elgg_register_success_message(['message' => 'Success message with a link', 'link' => elgg_view('output/url', ['href' => false, 'text' => 'Call to action'])]);
elgg_register_success_message(['message' => 'Persistent success message', 'ttl' => 0]);
elgg_register_success_message(['message' => 'Success message registered in PHP that autohides in 10 sec', 'ttl' => 10]);
elgg_register_error_message(['message' => 'Error message registered in PHP that autohides in 5 sec', 'ttl' => 5]);

// can't use the ipsum because it includes html when wrapping views.
$message = elgg_view('output/url', [
	'text' => 'Show system message (elgg_register_success_message())',
	'is_trusted' => true,
	'href' => '#',
	'id' => 'developers-system-message',
]);

$error = elgg_view('output/url', [
	'text' => 'Show error message (elgg_register_error_message())',
	'is_trusted' => true,
	'href' => '#',
	'id' => 'developers-error-message',
]);

?>
<ul>
	<li><?php echo $message; ?></li>
	<li><?php echo $error; ?></li>
</ul>
