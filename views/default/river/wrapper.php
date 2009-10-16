<?php
/**
 * Elgg river item wrapper.
 * Wraps all river items.
 *
 * @package Elgg
 * @author Curverider
 * @link http://elgg.com/
 */

$statement = $vars['statement'];
$time = $vars['time'];
$event = $vars['event'];
$entry = $vars['entry'];

if ($statement->getObject() instanceof ElggEntity) {

	$obj = $statement->getObject();
	$subtype = $obj->getSubtype();
	if (empty($subtype)) $subtype = $obj->type;
	if (empty($subtype)) $subtype = "general";
} else if (is_array($statement->getObject())) {
	$obj = $statement->getObject();
	$subtype = "relationship_" . $obj['relationship'];
}
?>
<div class="river_item">

	<div class="river_<?php echo $subtype; ?>">
		<div class="river_<?php echo $event; ?>">
			<p class="river_<?php echo $subtype; ?>_<?php echo $event; ?>">
				<?php

					echo $vars['entry'];

				?>
				<span class="river_item_time">
					(<?php

						echo friendly_time($time);

					?>)
				</span>
			</p>
		</div>
	</div>

</div>
