<?php

namespace Elgg\Cli;

use ElggBatch;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * entities:get CLI command
 */
class EntitiesGetCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('entities:get')
				->setDescription('Returns a list of entities that match the criteria')
				->addOption('guid', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Entity GUID(s)')
				->addOption('type', null, InputOption::VALUE_OPTIONAL, 'Entity type')
				->addOption('subtype', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Entity subtype(s)')
				->addOption('offset', null, InputOption::VALUE_OPTIONAL, 'Offset', 0)
				->addOption('keyword', null, InputOption::VALUE_OPTIONAL, 'Search keyword')
				->addOption('full-view', null, InputOption::VALUE_NONE, 'Display all metadata')
				->addOption('as', null, InputOption::VALUE_OPTIONAL, 'Username of the user to login');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function handle() {
		$this->renderTable(25, $this->option('offset') ? : 0);
	}

	protected function renderTable($limit, $offset = 0) {
		static $count;
		static $iterator;

		$options = [
			'query' => sanitize_string($this->option('keyword')),
			'guids' => $this->option('guid') ? : ELGG_ENTITIES_ANY_VALUE,
			'types' => $this->option('type') ? : 'object',
			'subtypes' => $this->option('subtype') ? : ELGG_ENTITIES_ANY_VALUE,
			'limit' => $limit,
			'offset' => (int) $offset,
			'order_by' => 'e.guid ASC',
		];

		if ($this->option('keyword')) {
			$results = elgg_trigger_plugin_hook('search', $this->option('type') ? : 'object', $options, []);
			$count = $results['count'];
			$batch = $results['entities'];
		} else {
			$options['count'] = true;
			if (!$count) {
				$count = elgg_get_entities($options);
			}
			unset($options['count']);
			$batch = new ElggBatch('elgg_get_entities', $options);
		}

		if (!$count) {
			$this->write('<comment>No entities to display</comment>');
			return;
		}

		$headers = [
			'#',
			'GUID',
			'Type',
			'Title/name',
			'Description',
			'Owner',
			'Container',
			'Access',
		];

		if ($this->option('full-view')) {
			$headers[] = 'Metadata';
		}

		$table = new Table($this->output);
		$table->setHeaders($headers);

		foreach ($batch as $entity) {
			/* @var $entity \ElggEntity */
			$row = [
				$iterator,
				$entity->guid,
				($subtype = $entity->getSubtype()) ? elgg_echo("item:{$entity->type}:{$subtype}") : elgg_echo("item:{$entity->type}"),
				elgg_get_excerpt($entity->getDisplayName(), 25),
				elgg_get_excerpt($entity->description, 25),
				($owner = $entity->getOwnerEntity()) ? '[' . $owner->guid . '] ' . elgg_get_excerpt($owner->getDisplayName(), 25) : '',
				($container = $entity->getContainerEntity()) ? '[' . $container->guid . '] ' . elgg_get_excerpt($container->getDisplayName(), 25) : '',
				'[' . $entity->access_id . '] ' . elgg_get_excerpt(get_readable_access_level($entity->access_id), 25),
			];

			if ($this->option('full-view')) {
				$metadata = new \ElggBatch('elgg_get_metadata', [
					'guids' => $entity->guid,
					'limit' => 0,
				]);

				$metatable = [];
				foreach ($metadata as $md) {
					$name = $md->name;
					$values = (array) $md->value;
					foreach ($values as $value) {
						$metatable[] = "$name: $value";
					}
				}

				$row[] = implode("\n", $metatable);
			}

			$table->addRow($row);
			$table->addRow(new TableSeparator());
			$iterator++;
		}

		$table->render();

		if ($count > $limit + $offset) {
			$helper = $this->getHelper('question');
			$question = new ConfirmationQuestion('Load next batch [y,n]?', true);

			if (!$helper->ask($this->input, $this->output, $question)) {
				return;
			}

			$this->renderTable($limit, $limit + $offset);
		}
	}

}
