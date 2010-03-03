<?php
	/**
	 * Elgg Pages
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Output body
	$entity = $vars['entity'];
	
	$rev = (int)get_input('rev');
	
	if ($rev)
	{	
		$latest = get_annotation($rev);	
	}
	else
	{
		$latest = $entity->getAnnotations('page', 1, 0, 'desc');
		if ($latest) $latest = $latest[0];
	}
	
?>	
	<div class="contentWrapper">	
	<div id="pages_page">
	
<?php	
	if ($entity)
	{
		echo elgg_view('output/longtext', array('value' => /*$entity->description*/ $latest->value));
		
		$tags = $vars['entity']->tags;
		if (!empty($tags)) {
		
?>
		<!-- display tags -->
		<p class="tags">
			<?php

				echo elgg_view('output/tags', array('tags' => $tags));
			
			?>
		</p>
		
<?php

		}
		$cats = elgg_view('categories/view',$vars);
		if (!empty($cats)) {
			
?>
		<p class="categories">
			<?php echo $cats; ?>
		</p>
<?php
			
		}
	}

	// last edit & by whome
?>

	<p class="strapline">
		<?php
                
			$time_updated = $latest->time_created;
			$owner_guid = $latest->owner_guid;
			$owner = get_entity($owner_guid);
		
			echo sprintf(elgg_echo("pages:strapline"),
							friendly_time($time_updated),
							"<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>"
			);
		
		?>
	</p>
</div>

</div>