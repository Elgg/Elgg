<?php
/**
 * Elgg categories plugin settings page
 *
 * @package ElggCategories
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

// Get site and categories
$site = $CONFIG->site;
$categories = $site->categories;

if (empty($categories)) {
	$categories = array();
}

?>
<div class="contentWrapper">
	<p>
		<?php echo elgg_echo('categories:explanation'); ?>
	</p>
	<?php
		echo elgg_view('input/tags', array('value' => $categories, 'internalname' => 'categories'));
	?>
</div>