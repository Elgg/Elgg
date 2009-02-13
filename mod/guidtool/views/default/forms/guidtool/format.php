<?php
	/**
	 * Elgg GUID Tool
	 * 
	 * @package ElggGUIDTool
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$formats = $vars['formats'];
	
	if (!$formats)
		$formats = array('opendd');
		
	$format = get_input('format');
	if ($format)
	{	
		forward(get_input('forward_url') . $format . "/");
		exit;
	}
	
?>

<div class="contentWrapper">
	<form method="get">
		<select name="format">
		<?php
			foreach ($formats as $format)
			{
	?>
				<option value="<?php echo $format; ?>"><?php echo $format; ?></option>
	<?php 
			}
		?>
		</select>
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("go"); ?>" />
	</form>
</div>