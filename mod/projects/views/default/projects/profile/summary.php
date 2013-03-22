<?php
/**
 * Project profile summary
 *
 * Icon and profile fields
 * 
 * @package Coopfunding
 * @subpackage Projects
 *
 * @uses $vars['project']
 */

if (!isset($vars['entity']) || !$vars['entity']) {
	echo elgg_echo('projects:notfound');
	return true;
}

$project = $vars['entity'];
$owner = $project->getOwnerEntity();

if (!$owner) {
	// not having an owner is very bad so we throw an exception
	$msg = elgg_echo('InvalidParameterException:IdNotExistForGUID', array('project owner', $project->guid));
	throw new InvalidParameterException($msg);
}

?>
<div class="projects-profile clearfix elgg-image-block">
	<div class="elgg-image">
		<div class="projects-profile-icon">
			<?php
				// we don't force icons to be square so don't set width/height
				echo elgg_view_entity_icon($project, 'large', array(
					'href' => '',
					'width' => '',
					'height' => '',
				)); 
			?>
		</div>
	</div>

	<div class="projects-profile-fields elgg-body">
		<?php
			echo elgg_view('projects/profile/fields', $vars);
		?>
	</div>
</div>
<?php
?>

