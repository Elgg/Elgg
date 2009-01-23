<?php
	/**
	 * Elgg river for dashboard.
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	/// Extract the river
	$river = $vars['river'];
?>
<div id="river">
	<?php
	if (($river) && (count($river)>0))
	{
		foreach ($river as $r) echo $r;
			//echo "<div>$r</div>";
	}
	else
		echo elgg_echo('river:widget:noactivity');
	?>
</div>