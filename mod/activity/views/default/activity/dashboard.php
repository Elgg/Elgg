<?php
	/// Extract the activity
	$activity = $vars['activity'];
	
	//include a view which can then be extended by the wire plugin so you can post to the wire right in this view
	echo elgg_view("activity/thewire");

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