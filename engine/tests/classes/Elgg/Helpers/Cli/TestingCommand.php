<?php

namespace Elgg\Helpers\Cli;

use Elgg\Cli\Command;

/**
 * @see \Elgg\Cli\CommandTest
 */
class TestingCommand extends Command {
	
	protected $handler;
	
	public function setHandler(\Closure $handler) {
		$this->handler = $handler;
	}
	
	public function configure() {
		$this->setName('testing');
	}
	
	protected function command() {
		return $this->handler;
	}
}
