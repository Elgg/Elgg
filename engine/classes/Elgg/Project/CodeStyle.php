<?php

namespace Elgg\Project;

/**
 * Internal component to detect and fix some whitespace issues
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Project
 */
class CodeStyle {

	const KEY_NEW_CONTENT = 'new_content';
	const KEY_REMAINING = 'remaining';
	const KEY_CORRECTIONS = 'corrections';

	/**
	 * @var string Regex pattern for file extensions to analyze
	 */
	protected $file_pattern = '~\.(?:php|js|css|xml|json|yml|txt|rst|md|gitignore|htaccess|mailmap|sh)$~';

	/**
	 * @var int The start argument such that substr(filepath, start) will return the filepath as a relative
	 *          path from the project root. E.g. if the root is /path/to/Elgg, this property will be set to
	 *          14. That way substr('/path/to/Elgg/foo/bar.php', 14) => foo/bar.php
	 */
	protected $substr_start;

	/**
	 * Fix problems in a directory of files and return a report.
	 *
	 * @param string $root    Root directory
	 * @param bool   $dry_run If set to true, no files will be written
	 * @return array Report of notable files
	 */
	public function fixDirectory($root, $dry_run = false) {
		$return = [];

		$this->substr_start = strlen($this->normalizePath($root)) + 1;

		$files = $this->findFilesToAnalyze($root);

		foreach ($files as $file) {
			$report = $this->analyzeFile($file);
			$key = substr($file, $this->substr_start);

			if ($dry_run) {
				$errors = $report[self::KEY_REMAINING];
				array_splice($errors, count($errors), 0, $report[self::KEY_CORRECTIONS]);
				if ($errors) {
					$return[$key] = $errors;
				}
			} else {
				if ($report[self::KEY_NEW_CONTENT] !== null) {
					file_put_contents($file, $report[self::KEY_NEW_CONTENT]);
				}
				if ($report[self::KEY_REMAINING]) {
					$return[$key][self::KEY_REMAINING] = $report[self::KEY_REMAINING];
				}
				if ($report[self::KEY_CORRECTIONS]) {
					$return[$key][self::KEY_CORRECTIONS] = $report[self::KEY_CORRECTIONS];
				}
			}
		}

		return $return;
	}

	/**
	 * Find files which can be analyzed/fixed by this component
	 *
	 * @param string $root Root directory
	 * @return string[] File paths. All directory separators will be "/"
	 */
	public function findFilesToAnalyze($root) {
		$files = [];
		$this->substr_start = strlen($this->normalizePath($root)) + 1;
		$this->findFiles(rtrim($root, '/\\'), $files);
		return $files;
	}

	/**
	 * Analyze a file for problems and return a report
	 *
	 * @param string $filepath Path of file to analyze
	 * @param string $content  The file's content (optional)
	 *
	 * @return array Report with keys:
	 *
	 *     remaining_problems : string[]    Problems which could not be fixed
	 *     corrections        : string[]    Problems which were fixed
	 *     new_content        : string|null Null if no corrections made, otherwise the corrected content
	 */
	public function analyzeFile($filepath, $content = null) {
		if (!is_string($content)) {
			$content = file_get_contents($filepath);
		}
		$old = $content;
		unset($content);

		$return = [
			self::KEY_REMAINING => [],
			self::KEY_CORRECTIONS => [],
			self::KEY_NEW_CONTENT => null,
		];

		// remove WS after non-WS
		$new = preg_replace('~(\S)[ \t]+(\r?\n)~', '$1$2', $old, -1, $count);
		if ($count) {
			$return[self::KEY_CORRECTIONS][] = "line(s) with trailing whitespace ($count)";
		}

		// don't risk breaking code blocks
		if (!preg_match('~\.(?:rst|md)$~', $filepath)) {
			// remove WS from empty lines
			$new = preg_replace('~^[ \t]+$~m', '', $new, -1, $count);
			if ($count) {
				$return[self::KEY_CORRECTIONS][] = "empty line(s) with whitespace ($count)";
			}
		}

		if (pathinfo($filepath, PATHINFO_EXTENSION) === 'php') {
			// remove close PHP tag at file end
			$new = preg_replace('~\?>\s*$~', '', $new, -1, $count);
			if ($count) {
				$return[self::KEY_CORRECTIONS][] = 'unnecessary close PHP tag';
			}
		}

		if ($new !== $old) {
			$return[self::KEY_NEW_CONTENT] = $new;
		}

		return $return;
	}

	/**
	 * Find files within a directory (recurse for subdirectories)
	 *
	 * @param string $dir   Directory to search
	 * @param array  $files Reference to found files
	 *
	 * @return void
	 */
	protected function findFiles($dir, &$files) {
		$d = dir($dir);

		while (false !== ($entry = $d->read())) {
			if ($entry === '.' || $entry === '..') {
				continue;
			}

			$full = $this->normalizePath("{$d->path}/$entry");
			$relative_path = substr($full, $this->substr_start);

			if (is_dir($full)) {
				if ($entry[0] === '.' || preg_match('~(?:/vendors?)$~', $full)) {
					// special case
					if ($entry !== '.scripts') {
						continue;
					}
				}

				if (in_array($relative_path, ['node_modules', 'docs/_build'])) {
					continue;
				}

				$this->findFiles($full, $files);
			} else {
				// file

				if (basename($dir) === 'languages' && $entry !== 'en.php') {
					continue;
				}

				if ($relative_path === 'install/config/htaccess.dist' || preg_match($this->file_pattern, $entry)) {
					$files[] = $full;
					continue;
				}
			}
		}
		$d->close();
	}

	/**
	 * Normalize a path
	 *
	 * @param string $path A file/dir path
	 *
	 * @return string
	 */
	protected function normalizePath($path) {
		return str_replace('\\', '/', rtrim($path, '/\\'));
	}
}
