<?php
	/// Extract the activity
	$activity = $vars['activity'];
?>
<div id="activity">
	<?php
	if (($activity) && (count($activity)))
	{
		foreach ($activity as $a) echo $a;
	}
	else
		echo elgg_echo('activity:noactivity');
	?>
</div>