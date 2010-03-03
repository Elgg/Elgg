<?php

	/**
	 * Elgg report content plugin form
	 * 
	 * @package ElggReportContent
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */
			
			$guid = 0;
			$title = get_input('title',"");
			$description = "";
			$address = get_input('address',"");
			if ($address == "previous")
				$address = $_SERVER['HTTP_REFERER'];
			$tags = array();
			$access_id = ACCESS_PRIVATE;
			$shares = array();
			$owner = $vars['user'];

?>
<div class="contentWrapper">
	<form action="<?php echo $vars['url']; ?>action/reportedcontent/add" method="post">
	<?php echo elgg_view('input/securitytoken'); ?>
	
		<p>
			<label>
				<?php 	echo elgg_echo('reportedcontent:title'); ?>
				<?php

						echo elgg_view('input/text',array(
								'internalname' => 'title',
								'value' => $title,
						)); 
				
				?>
			</label>
		</p>
		<p>
			<label>
				<?php 	echo elgg_echo('reportedcontent:address'); ?>
				<?php

						echo elgg_view('input/url',array(
								'internalname' => 'address',
								'value' => $address,
						)); 
				
				?>
			</label>
		</p>
		<p class="longtext_editarea">
			<label>
				<?php 	echo elgg_echo('reportedcontent:description'); ?>
				<br />
				<?php

						echo elgg_view('input/longtext',array(
								'internalname' => 'description',
								'value' => $description,
						)); 
				
				?>
			</label>
		</p>
		<p>
			<input type="submit" value="<?php echo elgg_echo('reportedcontent:report'); ?>" />
		</p>
	
	</form>
</div>
