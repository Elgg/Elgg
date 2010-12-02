<?php

?>
<div id="widget_add_button">
<?php
$options = array(
	'href' => '#',
	'text' => elgg_echo('widgets:add'),
	'class' => 'action-button',
);
echo elgg_view('output/url', $options);
?>
</div>
