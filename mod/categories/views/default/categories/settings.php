<?php

echo elgg_view_title(elgg_echo('categories:settings'));

?>

<div class="contentWrapper">
	<p>
		<?php echo elgg_echo('categories:explanation'); ?>
	</p>


	<?php

	echo elgg_view('input/form', array(
							'action' => $vars['url'] . 'action/categories/save',
							'method' => 'post',
							'body' => elgg_view('categories/settingsform',$vars)
							)
					);

	?>

</div>