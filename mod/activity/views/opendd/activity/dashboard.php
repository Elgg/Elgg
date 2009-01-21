<?php
	/// Extract the activity
	$activity = $vars['activity'];

	if (($activity) && (count($activity)))
	{
		foreach ($activity as $a) 
		{
			foreach ($a as $odd)
				echo $odd;
		}
	}
?>