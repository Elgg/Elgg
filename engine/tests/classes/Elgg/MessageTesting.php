<?php

namespace Elgg;

trait MessageTesting {

	public function assertSystemMessageEmitted($expected) {
		$registers = _elgg_services()->systemMessages->loadRegisters();

		$this->assertContains($expected, $registers->success,
			"System message '$expected' not emitted: " . print_r($registers, true)
		);
	}

	public function assertErrorMessageEmitted($expected) {
		$registers = _elgg_services()->systemMessages->loadRegisters();

		$this->assertContains($expected, $registers->error,
			"Error message '$expected' not emitted: " . print_r($registers, true)
		);
	}
}