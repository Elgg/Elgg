<?php
namespace Elgg\Plugin;

use Elgg\Exceptions\Plugin\IdMismatchException;
use Elgg\Exceptions\Plugin\ComposerException;
use Elgg\Exceptions\Plugin\ConflictException;
use Composer\Semver\Semver;
use Elgg\Exceptions\Plugin\PhpVersionException;
use Elgg\Exceptions\Plugin\PhpExtensionException;

/**
 * Holds plugin composer.json related functions
 *
 * @internal
 * @since 4.0
 */
class Composer {
	
	/**
	 * @var \ElggPlugin
	 */
	protected $plugin;
	
	/**
	 * @var \Eloquent\Composer\Configuration\Element\Configuration
	 */
	protected $configuration;
	
	/**
	 * Constructor
	 *
	 * @param \ElggPlugin $plugin Plugin
	 *
	 * @throws ComposerException
	 */
	public function __construct(\ElggPlugin $plugin) {
		$this->plugin = $plugin;
		
		try {
			// need to suppress warning because of deprecated notices that get converted to warnings during phpunit
			$reader = @new \Eloquent\Composer\Configuration\ConfigurationReader;
			$this->configuration = $reader->read($this->plugin->getPath() . 'composer.json');
		} catch (\Exception $e) {
			throw new ComposerException(elgg_echo('ElggPlugin:NoPluginComposer', [
				$this->plugin->getID(),
				$this->plugin->guid,
			]));
		}
	}
	
	/**
	 * Returns the composer configuration
	 *
	 * @return \Eloquent\Composer\Configuration\Element\Configuration
	 */
	public function getConfiguration() {
		return $this->configuration;
	}
	
	/**
	 * Asserts if plugin id matches project name
	 *
	 * @return void
	 * @throws IdMismatchException
	 */
	public function assertPluginId() {
		if ($this->configuration->projectName() !== $this->plugin->getID()) {
			throw new IdMismatchException(elgg_echo('ElggPlugin:IdMismatch', [$this->configuration->projectName()]));
		}
	}
	
	/**
	 * Returns the license
	 *
	 * @return string
	 */
	public function getLicense() {
		$license = $this->configuration->license();
		if (!empty($license)) {
			$license = implode(', ', $license);
		}
		
		return (string) $license;
	}
	
	/**
	 * Returns an array with categories
	 *
	 * @return array
	 */
	public function getCategories() {
		$cats = $this->configuration->keywords() ?: [];
		
		$result = [];
		foreach ($cats as $cat) {
			$result[strtolower($cat)] = $this->getFriendlyCategory($cat);
		}
		
		// plugins often set Elgg in their keywords, we do not need that keyword
		unset($result['elgg']);
		unset($result['plugin']);
		
		// add vendor to categories
		$vendor = strtolower((string) $this->configuration->vendorName());
		if (!isset($result[$vendor])) {
			$result[$vendor] = $this->getFriendlyCategory($vendor);
		}
				
		return $result;
	}
	
	/**
	 * Returns an array of projectnames with their conflicting version
	 *
	 * @return array
	 */
	public function getConflicts() {
		$conflicts = $this->configuration->conflict();
		
		$result = [];
		foreach ($conflicts as $name => $version) {
			list(,$projectname) = explode('/', $name);
			if (!empty($projectname)) {
				$result[$projectname] = $version;
			}
		}
		
		return $result;
	}
	
	/**
	 * Asserts if there are conflicts
	 *
	 * @return void
	 * @throws ConflictException
	 */
	public function assertConflicts() {
		$conflicts = $this->getConflicts();
		if (empty($conflicts)) {
			return;
		}
		
		if (isset($conflicts['elgg'])) {
			if ($this->checkConstraints(elgg_get_version(true), $conflicts['elgg'])) {
				throw new ConflictException('Elgg version: ' . elgg_get_version(true) . ' conflicts with constraint '. $conflicts['elgg']);
			}
			unset($conflicts['elgg']);
		}
		
		foreach ($conflicts as $plugin_id => $constraints) {
			if (!elgg_is_active_plugin($plugin_id)) {
				continue;
			}
			
			$plugin = elgg_get_plugin_from_id($plugin_id);
			
			if ($this->checkConstraints($plugin->getVersion(), $constraints)) {
				throw new ConflictException("Plugin [{$plugin->getID()}] with version: {$plugin->getVersion()} conflicts with constraint {$constraints}");
			}
		}
	}
	
	/**
	 * Asserts if there are active plugins that conflict with the current plugin
	 *
	 * @return void
	 * @throws ConflictException
	 */
	public function assertActivePluginConflicts() {
		$active_plugins = elgg_get_plugins('active');
		foreach ($active_plugins as $plugin) {
			$conflicts = $plugin->getConflicts();
			if (!isset($conflicts[$this->plugin->getID()])) {
				continue;
			}
			
			$constraint = $conflicts[$this->plugin->getID()];
			if ($this->checkConstraints($this->plugin->getVersion(), $constraint)) {
				$msg = 'The plugin ' . $this->plugin->getDisplayName() . ' with version ' . $this->plugin->getVersion();
				$msg .= ' conflicts with constraint '. $constraint . ' defined in ' . $plugin->getID();
				throw new ConflictException($msg);
			}
		}
	}
	
	/**
	 * Determine if given version satisfies given constraints
	 *
	 * @param string $version     version to check
	 * @param string $constraints semver notation of version constraint
	 *
	 * @return boolean
	 */
	public function checkConstraints($version, $constraints) {
		return Semver::satisfies($version, $constraints);
	}
	
	/**
	 * Asserts if the required php version matches the actual php version
	 *
	 * @return void
	 * @throws PhpVersionException
	 */
	public function assertRequiredPhpVersion() {
		$requirements = $this->configuration->dependencies();
		if (!isset($requirements['php'])) {
			return;
		}
		
		$php_version = phpversion();
		if (!$this->checkConstraints($php_version, $requirements['php'])) {
			throw new PhpVersionException("The PHP version ({$php_version}) does not meet the plugin [{$this->plugin->getID()}] requirements of {$requirements['php']}");
		}
	}
	
	/**
	 * Asserts if the required php extensions matches the actual installed extensions
	 *
	 * @return void
	 * @throws PhpExtensionException
	 */
	public function assertRequiredPhpExtensions() {
		$requirements = $this->configuration->dependencies();
		foreach ($requirements as $name => $constraint) {
			if (strpos($name, 'ext-') !== 0) {
				continue;
			}
			
			$extension = substr($name, 4);
			if (!extension_loaded($extension)) {
				throw new PhpExtensionException("Plugin [{$this->plugin->getID()}] requires the PHP extensions {$extension}");
			}
			
			$extension_version = phpversion($extension);
			if (!$this->checkConstraints($extension_version, $constraint)) {
				throw new PhpExtensionException("The PHP extension version ({$extension_version}) does not meet the plugin [{$this->plugin->getID()}] requirements of {$constraint}");
			}
		}
	}
	
	/**
	 * Returns a category's friendly name. This can be localized by
	 * defining the string 'admin:plugins:category:<category>'. If no
	 * localization is found, returns the category with _ and - converted to ' '
	 * and then ucwords()'d.
	 *
	 * @param string $category The category
	 *
	 * @return string A human-readable category
	 */
	protected function getFriendlyCategory($category) {
		$cat_raw_string = strtolower("admin:plugins:category:{$category}");
		if (_elgg_services()->translator->languageKeyExists($cat_raw_string)) {
			return elgg_echo($cat_raw_string);
		}
		
		return ucwords(str_replace(['-', '_'], ' ', $category));
	}
}
