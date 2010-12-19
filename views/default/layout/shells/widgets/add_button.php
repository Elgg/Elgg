<?php

?>
<div id="widget-add-button">
<?php
$options = array(
	'href' => '#',
	'text' => elgg_echo('widgets:add'),
	'class' => 'elgg-action-button',
);
echo elgg_view('output/url', $options);
?>
</div>
