<p>
<?php 

	$num_items = $vars['entity']->num_items;
	if (!isset($num_items)) $num_items = 30;

  echo elgg_echo('tagcloud:widget:numtags'); 
	
	echo elgg_view('input/dropdown', array(
			'internalname' => 'params[num_items]',
			'options_values' => array( '10' => '10',
                                 '20' => '20',
			                           '30' => '30',
			                           '50' => '50',
			                           '100' => '100',
			                         ),
			'value' => $num_items
		));
?>
</p>
