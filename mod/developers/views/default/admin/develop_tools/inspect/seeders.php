<?php
/**
 * List the available CLI seeders and the current amount of seeded entities
 *
 * @uses $vars['data'] the registered database CLI seeders
 */

use Elgg\Database\Seeds\Seed;

$seeders = elgg_extract('data', $vars);
if (empty($seeders)) {
	echo elgg_view('page/components/no_results', [
		'no_results' => true,
	]);
}

$header = elgg_format_element('tr', [], implode(PHP_EOL, [
	elgg_format_element('th', [], elgg_echo('cli:database:seeders:handler')),
	elgg_format_element('th', [], elgg_echo('cli:database:seeders:type')),
	elgg_format_element('th', [], elgg_echo('cli:database:seeders:count')),
]));
$header = elgg_format_element('thead', [], $header);

$rows = [];

elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() use ($seeders, &$rows) {
	foreach ($seeders as $seeder) {
		$row = [];
		/* @var $seed Seed */
		$seed = new $seeder();
		
		$row[] = elgg_format_element('td', [], $seeder);
		$row[] = elgg_format_element('td', [], $seed::getType());
		$row[] = elgg_format_element('td', [], $seed->getCount());
		
		$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $row));
	}
});

$body = elgg_format_element('tbody', [], implode(PHP_EOL, $rows));

echo elgg_format_element('table', ['class' => 'elgg-table'], $header . $body);
