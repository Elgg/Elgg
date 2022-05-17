<?php

namespace Elgg;

trait MessageTesting {

	public function assertSystemMessageEmitted($expected) {
		$registers = _elgg_services()->system_messages->loadRegisters()->success;

		$found = false;
		foreach ($registers as $msg) {
			if ($msg->getMessage() === $expected) {
				$found = true;
				break;
			}
		}

		$this->assertTrue($found, "System message '$expected' not emitted: " . print_r($registers, true));
	}

	public function assertErrorMessageEmitted($expected) {
		$registers = _elgg_services()->system_messages->loadRegisters()->error;

		$found = false;
		foreach ($registers as $msg) {
			if ($msg->getMessage() === $expected) {
				$found = true;
				break;
			}
		}

		$this->assertTrue($found, "Error message '$expected' not emitted: " . print_r($registers, true));
	}
}
