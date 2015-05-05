<?php

namespace Elgg;

interface BatchUpgrade {
	public function isRequired();

	public function run();

	public function getNumRemaining();

	public function getErrorMessages();

	public function getNextOffset();
}
