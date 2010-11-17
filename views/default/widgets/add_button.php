<?php

?>
<div class="widget" id="widget_add_button">
<?php
$options = array(
	'href' => '#',
	'text' => elgg_echo('widgets:add'),
	'class' => 'action_button',
);
echo elgg_view('output/url', $options);
?>
</div>
