<?php
/**
 * Elgg groups plugin full profile view.
 *
 * @package ElggGroups
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */
?>
<div id="content_header" class="clearfloat">
	<div class="content_header_title">
		<h2><?php echo $vars['entity']->name; ?></h2>
	</div>
	<?php
		if ($vars['entity']->canEdit())	{
	?>
		<div class="content_header_options">
			<a class="action_button" href="<?php echo $vars['url']; ?>mod/groups/edit.php?group_guid=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("groups:edit"); ?></a>
		</div>
	<?php
		}
	?>
</div>

<div class="group_profile clearfloat">
	<div class="group_profile_column icon">
		<div class="group_profile_icon">
		<?php
		echo elgg_view(
			"groups/icon", array(
			'entity' => $vars['entity'],
			'size' => 'large',
			));
		?>
		</div>

		<div class="group_stats">
			<?php
				echo "<p><b>" . elgg_echo("groups:owner") . ": </b><a href=\"" . get_user($vars['entity']->owner_guid)->getURL() . "\">" . get_user($vars['entity']->owner_guid)->name . "</a></p>";
			?>
			<p><?php
				$options = array(
					'relationship' => 'member',
					'relationship_guid' => $vars['entity']->guid,
					'inverse_relationship' => TRUE,
					'limit' => 0,
					'count' => TRUE
				);

				$count = elgg_get_entities_from_relationship($options);

				echo elgg_echo('groups:members') . ": " . $count;

			?></p>
		</div>
	</div>

	<div class="group_profile_column info">
		<?php
			if ($vars['full'] == true) {
				if (is_array($vars['config']->group) && sizeof($vars['config']->group) > 0){

					foreach($vars['config']->group as $shortname => $valtype) {
						if ($shortname != "name") {
							$value = $vars['entity']->$shortname;

							if (!empty($value)) {
								//This function controls the alternating class
								$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
							}

							echo "<p class=\"{$even_odd}\">";
							echo "<b>";
							echo elgg_echo("groups:{$shortname}");
							echo ": </b>";

							$options = array(
								'value' => $vars['entity']->$shortname
							);

							if ($valtype == 'tags') {
								$options['tag_names'] = $shortname;
							}

							echo elgg_view("output/{$valtype}", $options);

							echo "</p>";
						}
					}
				}
			}
		?>
	</div>
</div>


