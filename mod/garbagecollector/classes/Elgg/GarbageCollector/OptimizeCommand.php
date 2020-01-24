<?php

namespace Elgg\GarbageCollector;

use Elgg\Cli\Command;
use Symfony\Component\Console\Helper\Table;

/**
 * elgg-cli database:optimize
 */
class OptimizeCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('database:optimize')
			->setDescription(elgg_echo('garbagecollector:cli:database:optimize:description'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		$ops = GarbageCollector::instance()->optimize();

		$ops = array_map(function($e) {
			$e->result = $e->result ? 'ok' : 'err';
			$e->completed = $e->completed->format(DATE_ATOM);

			return (array) $e;
		}, $ops);

		$table = new Table($this->output);

		$table->addRows($ops);

		$table->render();

		return 0;
	}
}
