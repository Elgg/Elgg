<?php
/**
 * Content filter for river
 *
 * @uses $vars[]
 */

// create selection array
$options = array();
$options['type=all'] = elgg_echo('river:select', array(elgg_echo('all')));
$registered_entities = elgg_get_config('registered_entities');

if (!empty($registered_entities)) {
	foreach ($registered_entities as $type => $subtypes) {
		// subtype will always be an array.
		if (!count($subtypes)) {
			$label = elgg_echo('river:select', array(elgg_echo("item:$type")));
			$options["type=$type"] = $label;
		} else {
			foreach ($subtypes as $subtype) {
				$label = elgg_echo('river:select', array(elgg_echo("item:$type:$subtype")));
				$options["type=$type&subtype=$subtype"] = $label;
			}
		}
	}
}

$params = array(
	'id' => 'elgg-river-selector',
	'options_values' => $options,
);
$selector = $vars['selector'];
if ($selector) {
	$params['value'] = $selector;
}
echo elgg_view('input/dropdown', $params);
?>
<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
elgg.register_hook_handler('init', 'system', function() {
	$('#elgg-river-selector').change(function() {
		var url = window.location.href;
		if (window.location.search.length) {
			url = url.substring(0, url.indexOf('?'));
		}
		url += '?' + $(this).val();
		elgg.forward(url);
	});
});
</script>
