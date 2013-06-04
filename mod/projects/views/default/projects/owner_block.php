<?php

$project = $vars['entity'];

$title = elgg_view('output/url', array(
	'text' => $project->name,
	'href' => $project->getURL(),
));
$tags = elgg_view('output/tags', array('value' => $project->interests));
$description = $project->briefdescription;
$friendly_time = elgg_view('output/friendlytime', array('time' => $project->time_created));

echo <<<HTML
<div class="projects-owner-block mbl">
	<h3>$title</h3>
	$tags
	<div class="elgg-description">$description</div>
	<div class="elgg-subtext">$friendly_time</div>
</div>
HTML;
