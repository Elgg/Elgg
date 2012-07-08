<?php
/**
 * Create a class map by scanning a directory
 *
 * Contains code from Symfony2's ClassMapGenerator.
 *
 * Copyright (c) 2004-2012 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @class      ElggClassScanner
 * @package    Elgg.Core
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ElggClassScanner {

	/**
	 * Iterate over all files in the given directory searching for classes
	 *
	 * @param Iterator|string $dir The directory to search in or an iterator
	 *
	 * @return array A class map array
	 */
	static public function createMap($dir) {
		if (is_string($dir)) {
			$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
		}

		$map = array();

		foreach ($dir as $file) {
			/* @var SplFileInfo $file */
			if (!$file->isFile() || !$file->isReadable()) {
				continue;
			}

			$path = $file->getRealPath();

			if (pathinfo($path, PATHINFO_EXTENSION) !== 'php') {
				continue;
			}

			foreach (self::findClasses(file_get_contents($path)) as $class) {
				$map[$class] = $path;
			}
		}
		return $map;
	}

	/**
	 * Extract the classes in the given file
	 *
	 * @param string $contents file contents
	 *
	 * @return array The found classes
	 */
	static private function findClasses($contents) {
		$tokens = token_get_all($contents);
		// support PHP before 5.3
		$T_NAMESPACE = version_compare(PHP_VERSION, '5.3', '<') ? -1 : T_NAMESPACE;
		$T_TRAIT = version_compare(PHP_VERSION, '5.4', '<') ? -1 : T_TRAIT;

		$classes = array();

		$namespace = '';
		for ($i = 0, $max = count($tokens); $i < $max; $i++) {
			$token = $tokens[$i];

			if (is_string($token)) {
				continue;
			}

			$class = '';

			switch ($token[0]) {
				case $T_NAMESPACE:
					$namespace = '';
					// If there is a namespace, extract it
					while (($t = $tokens[++$i]) && is_array($t)) {
						if (in_array($t[0], array(T_STRING, T_NS_SEPARATOR))) {
							$namespace .= $t[1];
						}
					}
					$namespace .= '\\';
					break;
				case T_CLASS:
				case T_INTERFACE:
				case $T_TRAIT:
					// Find the classname
					while (($t = $tokens[++$i]) && is_array($t)) {
						if (T_STRING === $t[0]) {
							$class .= $t[1];
						} elseif ($class !== '' && T_WHITESPACE == $t[0]) {
							break;
						}
					}

					$classes[] = ltrim($namespace . $class, '\\');
					break;
				default:
					break;
			}
		}

		return $classes;
	}
}
