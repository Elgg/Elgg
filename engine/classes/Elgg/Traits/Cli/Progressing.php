<?php

namespace Elgg\Traits\Cli;

use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Progress reporting
 *
 * @internal
 */
trait Progressing {

	/**
	 * @var ProgressBar
	 */
	protected $progress;

	/**
	 * Set progress bar helper
	 *
	 * @param ProgressBar $progress Progress bar
	 *
	 * @return void
	 */
	public function setProgressBar(ProgressBar $progress) {
		$this->progress = $progress;
	}

	/**
	 * Advance progressbar
	 *
	 * @param int $step Step
	 * @return void
	 */
	public function advance(int $step = 1) {
		if ($this->progress) {
			$this->progress->advance($step);
		}
	}
}
