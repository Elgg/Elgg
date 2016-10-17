<?php

// can't use the ipsum because it includes html when wrapping views.
$message = elgg_view('output/url', array(
	'text' => 'Show system message (system_message())',
	'is_trusted' => true,
	'href' => '#',
	'id' => 'theme-sandbox-system-message',
//	'onclick' => "elgg.system_message('Elgg System Message');"
));

$error = elgg_view('output/url', array(
	'text' => 'Show error message (register_error())',
	'is_trusted' => true,
	'href' => '#',
	'id' => 'theme-sandbox-error-message',
));

?>
<ul>
	<li><?php echo $message; ?></li>
	<li><?php echo $error; ?></li>
</ul>