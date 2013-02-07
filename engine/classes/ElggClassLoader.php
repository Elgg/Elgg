<?php
/**
 * A class/interface/trait autoloader for PHP
 *
 * It is able to load classes that use either:
 *
 *  * The technical interoperability standards for PHP 5.3 namespaces and
 *    class names (https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md);
 *
 *  * The PEAR naming convention for classes (http://pear.php.net/).
 *
 * Classes from a sub-namespace or a sub-hierarchy of PEAR classes can be
 * looked for in a list of locations to ease the vendoring of a sub-set of
 * classes for large projects.
 *
 * All discovered files are stored in the internal class map and the map is
 * queried before attempting to find a file.
 *
 * Contains code from Symfony2's UniversalClassLoader.
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
 * @class      ElggClassLoader
 * @package    Elgg.Core
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ElggClassLoader {

	protected $namespaces = array();
	protected $prefixes = array();
	protected $fallbacks = array();

	/**
	 * @var ElggClassMap
	 */
	protected $map;

	/**
	 * @param ElggClassMap $map
	 */
	public function __construct(ElggClassMap $map) {
		$this->map = $map;
	}

	/**
	 * @return ElggClassMap
	 */
	public function getClassMap() {
		return $this->map;
	}

	/**
	 * Gets the configured namespaces.
	 *
	 * @return array A hash with namespaces as keys and directories as values
	 */
	public function getNamespaces() {
		return $this->namespaces;
	}

	/**
	 * Gets the configured class prefixes.
	 *
	 * @return array A hash with class prefixes as keys and directories as values
	 */
	public function getPrefixes() {
		return $this->prefixes;
	}

	/**
	 * Registers an array of namespaces
	 *
	 * @param array $namespaces An array of namespaces (namespaces as keys and locations as values)
	 */
	public function registerNamespaces(array $namespaces) {
		foreach ($namespaces as $namespace => $locations) {
			$this->namespaces[$namespace] = (array)$locations;
		}
	}

	/**
	 * Registers a namespace.
	 *
	 * @param string	   $namespace The namespace
	 * @param array|string $paths	 The location(s) of the namespace
	 */
	public function registerNamespace($namespace, $paths) {
		$this->namespaces[$namespace] = (array)$paths;
	}

	/**
	 * Registers an array of classes using the PEAR naming convention.
	 *
	 * @param array $classes An array of classes (prefixes as keys and locations as values)
	 */
	public function registerPrefixes(array $classes) {
		foreach ($classes as $prefix => $locations) {
			$this->prefixes[$prefix] = (array)$locations;
		}
	}

	/**
	 * Registers a set of classes using the PEAR naming convention.
	 *
	 * @param string	   $prefix The classes prefix
	 * @param array|string $paths  The location(s) of the classes
	 */
	public function registerPrefix($prefix, $paths) {
		$this->prefixes[$prefix] = (array)$paths;
	}

	/**
	 * Add a directory to search if no registered directory is found.
	 *
	 * @param string $path The directory
	 */
	public function addFallback($path) {
		$this->fallbacks[] = rtrim($path, '/\\');
	}

	/**
	 * Registers this instance as an autoloader.
	 *
	 * @param bool $prepend Whether to prepend the autoloader or not
	 */
	public function register($prepend = false) {
		// must check PHP version http://www.php.net/manual/en/function.spl-autoload-register.php#107362
		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
			spl_autoload_register(array($this, 'loadClass'), true, $prepend);
		} else {
			spl_autoload_register(array($this, 'loadClass'), true);
		}
	}

	/**
	 * Loads the given class or interface, possibly updating the class map.
	 *
	 * @param string $class The name of the class
	 */
	public function loadClass($class) {
		$file = $this->map->getPath($class);
		if ($file) {
			require $file;
		} else {
			$file = $this->findFile($class);
			if ($file) {
				$this->map->setPath($class, $file);
				$this->map->setAltered(true);
				require $file;
			}
		}
	}

	/**
	 * Finds the path to the file where the class is defined.
	 *
	 * @param string $class The name of the class
	 *
	 * @return string|null The path, if found
	 */
	public function findFile($class) {
		if ('\\' == $class[0]) {
			$class = substr($class, 1);
		}

		if (false !== $pos = strrpos($class, '\\')) {
			// namespaced class name
			$namespace = substr($class, 0, $pos);
			$className = substr($class, $pos + 1);
			$normalizedClass = str_replace('\\', DIRECTORY_SEPARATOR, $namespace)
				 . DIRECTORY_SEPARATOR
				 . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
			foreach ($this->namespaces as $ns => $dirs) {
				if (0 !== strpos($namespace, $ns)) {
					continue;
				}

				foreach ($dirs as $dir) {
					$file = $dir . DIRECTORY_SEPARATOR . $normalizedClass;
					if (is_file($file)) {
						return $file;
					}
				}
			}

		} else {
			// PEAR-like class name
			$normalizedClass = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
			foreach ($this->prefixes as $prefix => $dirs) {
				if (0 !== strpos($class, $prefix)) {
					continue;
				}

				foreach ($dirs as $dir) {
					$file = $dir . DIRECTORY_SEPARATOR . $normalizedClass;
					if (is_file($file)) {
						return $file;
					}
				}
			}
		}

		foreach ($this->fallbacks as $dir) {
			$file = $dir . DIRECTORY_SEPARATOR . $normalizedClass;
			if (is_file($file)) {
				return $file;
			}
		}
	}
}
