<?php
	/**
	 * Elgg GUID browser
	 * 
	 * @package ElggDevTools
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$navbar = $vars['prevnext'];
	$entities = $vars['entities'];
?>
<script type="text/javascript" language="javascript">
<!--
function showhide(oid)
{
	var e = document.getElementById(oid);
	if(e.style.display == 'none') {
		e.style.display = 'block';
	} else {
		e.style.display = 'none';
	}
}
// -->
</script>

<div id="browser">
	<div id="entities">
		<?php echo $entities; ?>
	</div>

	<?php echo $navbar; ?>
</div>