<?php
/**
 * Elgg river item wrapper.
 * Wraps all river items.
 *
 * @package Elgg
 * @author Curverider
 * @link http://elgg.com/
 */

//get the site admins choice avatars or action icons
$avatar_icon = get_plugin_setting("avatar_icon","riverdashboard");
if(!$avatar_icon) {
	$avatar_icon = "icon";
}

if($avatar_icon == "icon"){

	?>
	<div class="river_item">
		<div class="river_<?php echo $vars['item']->type; ?>">
			<div class="river_<?php echo $vars['item']->subtype; ?>">
				<div class="river_<?php echo $vars['item']->action_type; ?>">
					<div class="river_<?php echo $vars['item']->type; ?>_<?php if($vars['item']->subtype) echo $vars['item']->subtype . "_"; ?><?php echo $vars['item']->action_type; ?>">
					<p>
						<?php
								echo $vars['body'];
						?>
						<span class="river_item_time">
							(<?php
								echo friendly_time($vars['item']->posted);
							?>)
						</span>
					</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
} else {
	?>
	<div class="river_item">
		<span class="river_item_useravatar">
			<?php
				echo elgg_view("profile/icon",array('entity' => get_entity($vars['item']->subject_guid), 'size' => 'tiny'));
			?>
		</span>
		<p class="river_item_body">
			<?php
				echo $vars['body'];
			?>
			<span class="river_item_time">
				(<?php
					echo friendly_time($vars['item']->posted);
				?>)
			</span>
		</p>
		<div class="clearfloat"></div>
	</div>
	<?php
}
?>