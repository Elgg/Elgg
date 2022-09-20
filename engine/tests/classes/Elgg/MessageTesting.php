<?php

namespace Elgg;

trait MessageTesting {

	public function assertSystemMessageEmitted($expected) {
		$registers = elgg_extract('success', _elgg_services()->system_messages->loadRegisters());

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
		$registers = elgg_extract('error', _elgg_services()->system_messages->loadRegisters());

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
