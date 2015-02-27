<?php

namespace Elgg\Debug\Inspector;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package Elgg.Core
 * @since   1.11
 */
class ViewComponent {

	/**
	 * @var string View location. E.g. "/path/to/views/default/"
	 */
	public $location;

	/**
	 * @var string View name. E.g. "css/elgg"
	 */
	public $view;

	/**
	 * @var string View file extension, if known. E.g. "php"
	 */
	public $extension = null;

	/**
	 * @var bool Is this component represent an overridden view?
	 */
	public $overridden = false;

	/**
	 * @return string Return the component as a file path
	 */
	public function getFile() {
		$ext = pathinfo($this->view, PATHINFO_EXTENSION);
		if ($ext) {
			// view is filename
			return "{$this->location}{$this->view}";
		}

		$str = "{$this->location}{$this->view}.{$this->extension}";
		if ($this->extension === null) {
			// try to guess from filesystem
			$files = glob("{$this->location}{$this->view}.*");
			if (count($files) === 1) {
				$str = $files[0];
			} else {
				$str = "{$this->location}{$this->view}.?";
			}
		}

		return $str;
	}

	/**
	 * Get a component from the path and location
	 *
	 * @param string $path     Full file path
	 * @param string $location Base location of view
	 *
	 * @return ViewComponent
	 */
	public static function fromPaths($path, $location) {
		$component = new self();
		$component->location = $location;

		// cut location off
		$file = substr($path, strlen($location));
		$component->file = $file;

		$basename = basename($file);
		$period = strpos($basename, '.');
		if ($period === false) {
			// file with no extension? shouldn't happen
			$component->view = $file;
			$component->extension = '';
		} else {
			$cut_off_end = strlen($basename) - $period;
			$component->view = substr($file, 0, -$cut_off_end);
			$component->extension = substr($basename, $period + 1);
		}

		return $component;
	}
}
