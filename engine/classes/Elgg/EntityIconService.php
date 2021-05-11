<?php

namespace Elgg;

use Elgg\Database\EntityTable;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\Filesystem\MimeTypeService;
use Elgg\Traits\Loggable;
use Elgg\Traits\TimeUsing;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @internal
 * @since 2.2
 */
class EntityIconService {

	use Loggable;
	use TimeUsing;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	/**
	 * @var EntityTable
	 */
	private $entities;

	/**
	 * @var UploadService
	 */
	private $uploads;

	/**
	 * @var ImageService
	 */
	private $images;
	
	/**
	 * @var MimeTypeService
	 */
	protected $mimetype;

	/**
	 * Constructor
	 *
	 * @param Config             $config   Config
	 * @param PluginHooksService $hooks    Hook registration service
	 * @param EntityTable        $entities Entity table
	 * @param UploadService      $uploads  Upload service
	 * @param ImageService       $images   Image service
	 * @param MimeTypeService    $mimetype MimeType service
	 */
	public function __construct(
		Config $config,
		PluginHooksService $hooks,
		EntityTable $entities,
		UploadService $uploads,
		ImageService $images,
		MimeTypeService $mimetype
	) {
		$this->config = $config;
		$this->hooks = $hooks;
		$this->entities = $entities;
		$this->uploads = $uploads;
		$this->images = $images;
		$this->mimetype = $mimetype;
	}

	/**
	 * Saves icons using an uploaded file as the source.
	 *
	 * @param \ElggEntity $entity     Entity to own the icons
	 * @param string      $input_name Form input name
	 * @param string      $type       The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array       $coords     An array of cropping coordinates x1, y1, x2, y2
	 *
	 * @return bool
	 */
	public function saveIconFromUploadedFile(\ElggEntity $entity, $input_name, $type = 'icon', array $coords = []) {
		$input = $this->uploads->getFile($input_name);
		if (empty($input)) {
			return false;
		}
				
		// auto detect cropping coordinates
		if (empty($coords)) {
			$auto_coords = $this->detectCroppingCoordinates();
			if (!empty($auto_coords)) {
				$coords = $auto_coords;
			}
		}

		$tmp = new \ElggTempFile();
		$tmp->setFilename(uniqid() . $input->getClientOriginalName());
		$tmp->open('write');
		$tmp->close();
		
		copy($input->getPathname(), $tmp->getFilenameOnFilestore());

		$tmp->mimetype = $this->mimetype->getMimeType($tmp->getFilenameOnFilestore());
		$tmp->simpletype = $this->mimetype->getSimpleType($tmp->mimetype);

		$result = $this->saveIcon($entity, $tmp, $type, $coords);

		$tmp->delete();

		return $result;
	}

	/**
	 * Saves icons using a local file as the source.
	 *
	 * @param \ElggEntity $entity   Entity to own the icons
	 * @param string      $filename The full path to the local file
	 * @param string      $type     The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array       $coords   An array of cropping coordinates x1, y1, x2, y2
	 *
	 * @return bool
	 * @throws InvalidParameterException
	 */
	public function saveIconFromLocalFile(\ElggEntity $entity, $filename, $type = 'icon', array $coords = []) {
		if (!file_exists($filename) || !is_readable($filename)) {
			throw new InvalidParameterException(__METHOD__ . " expects a readable local file. $filename is not readable");
		}
				
		$tmp = new \ElggTempFile();
		$tmp->setFilename(uniqid() . basename($filename));
		$tmp->open('write');
		$tmp->close();
		
		copy($filename, $tmp->getFilenameOnFilestore());

		$tmp->mimetype = $this->mimetype->getMimeType($tmp->getFilenameOnFilestore());
		$tmp->simpletype = $this->mimetype->getSimpleType($tmp->mimetype);

		$result = $this->saveIcon($entity, $tmp, $type, $coords);

		$tmp->delete();

		return $result;
	}

	/**
	 * Saves icons using a file located in the data store as the source.
	 *
	 * @param \ElggEntity $entity Entity to own the icons
	 * @param \ElggFile   $file   An ElggFile instance
	 * @param string      $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array       $coords An array of cropping coordinates x1, y1, x2, y2
	 *
	 * @return bool
	 * @throws InvalidParameterException
	 */
	public function saveIconFromElggFile(\ElggEntity $entity, \ElggFile $file, $type = 'icon', array $coords = []) {
		if (!$file->exists()) {
			throw new InvalidParameterException(__METHOD__ . ' expects an instance of ElggFile with an existing file on filestore');
		}
		
		$tmp = new \ElggTempFile();
		$tmp->setFilename(uniqid() . basename($file->getFilenameOnFilestore()));
		$tmp->open('write');
		$tmp->close();
		
		copy($file->getFilenameOnFilestore(), $tmp->getFilenameOnFilestore());

		$tmp->mimetype = $this->mimetype->getMimeType($tmp->getFilenameOnFilestore(), $file->getMimeType() ?: '');
		$tmp->simpletype = $this->mimetype->getSimpleType($tmp->mimetype);

		$result = $this->saveIcon($entity, $tmp, $type, $coords);

		$tmp->delete();

		return $result;
	}

	/**
	 * Saves icons using a created temporary file
	 *
	 * @param \ElggEntity $entity Temporary ElggFile instance
	 * @param \ElggFile   $file   Temporary ElggFile instance
	 * @param string      $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array       $coords An array of cropping coordinates x1, y1, x2, y2
	 *
	 * @return bool
	 */
	public function saveIcon(\ElggEntity $entity, \ElggFile $file, $type = 'icon', array $coords = []) {

		$type = (string) $type;
		if (!strlen($type)) {
			$this->getLogger()->error('Icon type passed to ' . __METHOD__ . ' can not be empty');
			return false;
		}
		
		$entity_type = $entity->getType();
		
		$file = $this->hooks->trigger("entity:$type:prepare", $entity_type, [
			'entity' => $entity,
			'file' => $file,
		], $file);
		
		if (!$file instanceof \ElggFile || !$file->exists() || $file->getSimpleType() !== 'image') {
			$this->getLogger()->error('Source file passed to ' . __METHOD__ . ' can not be resolved to a valid image');
			return false;
		}
		
		$this->prepareIcon($file->getFilenameOnFilestore());
		
		$x1 = (int) elgg_extract('x1', $coords);
		$y1 = (int) elgg_extract('y1', $coords);
		$x2 = (int) elgg_extract('x2', $coords);
		$y2 = (int) elgg_extract('y2', $coords);
		
		$created = $this->hooks->trigger("entity:$type:save", $entity_type, [
			'entity' => $entity,
			'file' => $file,
			'x1' => $x1,
			'y1' => $y1,
			'x2' => $x2,
			'y2' => $y2,
		], false);

		// did someone else handle saving the icon?
		if ($created !== true) {
			// remove existing icons
			$this->deleteIcon($entity, $type, true);
			
			// save master image
			$store = $this->generateIcon($entity, $file, $type, $coords, 'master');
			
			if (!$store) {
				$this->deleteIcon($entity, $type);
				return false;
			}
		}

		if ($type == 'icon') {
			$entity->icontime = time();
			if ($x1 || $y1 || $x2 || $y2) {
				$entity->x1 = $x1;
				$entity->y1 = $y1;
				$entity->x2 = $x2;
				$entity->y2 = $y2;
			}
		} else {
			if ($x1 || $y1 || $x2 || $y2) {
				$entity->{"{$type}_coords"} = serialize([
					'x1' => $x1,
					'y1' => $y1,
					'x2' => $x2,
					'y2' => $y2,
				]);
			}
		}
		
		$this->hooks->trigger("entity:$type:saved", $entity->getType(), [
			'entity' => $entity,
			'x1' => $x1,
			'y1' => $y1,
			'x2' => $x2,
			'y2' => $y2,
		]);
		
		return true;
	}
	
	/**
	 * Prepares an icon
	 *
	 * @param string $filename the file to prepare
	 *
	 * @return void
	 */
	protected function prepareIcon($filename) {
		
		// fix orientation
		$temp_file = new \ElggTempFile();
		$temp_file->setFilename(uniqid() . basename($filename));
		
		copy($filename, $temp_file->getFilenameOnFilestore());
		
		$rotated = $this->images->fixOrientation($temp_file->getFilenameOnFilestore());

		if ($rotated) {
			copy($temp_file->getFilenameOnFilestore(), $filename);
		}
		
		$temp_file->delete();
	}
	
	/**
	 * Generate an icon for the given entity
	 *
	 * @param \ElggEntity $entity    Temporary ElggFile instance
	 * @param \ElggFile   $file      Temporary ElggFile instance
	 * @param string      $type      The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array       $coords    An array of cropping coordinates x1, y1, x2, y2
	 * @param string      $icon_size The icon size to generate (leave empty to generate all supported sizes)
	 *
	 * @return bool
	 */
	protected function generateIcon(\ElggEntity $entity, \ElggFile $file, $type = 'icon', $coords = [], $icon_size = '') {
		
		if (!$file->exists()) {
			$this->getLogger()->error('Trying to generate an icon from a non-existing file');
			return false;
		}
		
		$x1 = (int) elgg_extract('x1', $coords);
		$y1 = (int) elgg_extract('y1', $coords);
		$x2 = (int) elgg_extract('x2', $coords);
		$y2 = (int) elgg_extract('y2', $coords);
		
		$sizes = $this->getSizes($entity->getType(), $entity->getSubtype(), $type);
		
		if (!empty($icon_size) && !isset($sizes[$icon_size])) {
			$this->getLogger()->warning("The provided icon size '{$icon_size}' doesn't exist for icon type '{$type}'");
			return false;
		}
		
		foreach ($sizes as $size => $opts) {
			if (!empty($icon_size) && ($icon_size !== $size)) {
				// only generate the given icon size
				continue;
			}
			
			// check if the icon config allows cropping
			if (!(bool) elgg_extract('crop', $opts, true)) {
				$coords = [
					'x1' => 0,
					'y1' => 0,
					'x2' => 0,
					'y2' => 0,
				];
			}

			$icon = $this->getIcon($entity, $size, $type, false);

			// We need to make sure that file path is readable by
			// Imagine\Image\ImagineInterface::save(), as it fails to
			// build the directory structure on owner's filestore otherwise
			$icon->open('write');
			$icon->close();
			
			// Save the image without resizing or cropping if the
			// image size value is an empty array
			if (is_array($opts) && empty($opts)) {
				copy($file->getFilenameOnFilestore(), $icon->getFilenameOnFilestore());
				continue;
			}

			$source = $file->getFilenameOnFilestore();
			$destination = $icon->getFilenameOnFilestore();

			$resize_params = array_merge($opts, $coords);

			$image_service = _elgg_services()->imageService;
			$image_service->setLogger($this->getLogger());

			if (!_elgg_services()->imageService->resize($source, $destination, $resize_params)) {
				$this->getLogger()->error("Failed to create {$size} icon from
					{$file->getFilenameOnFilestore()} with coords [{$x1}, {$y1}],[{$x2}, {$y2}]");
				return false;
			}
		}

		return true;
	}

	/**
	 * Returns entity icon as an ElggIcon object
	 * The icon file may or may not exist on filestore
	 *
	 * @note Returned ElggIcon object may be a placeholder. Use ElggIcon::exists() to validate if file has been written to filestore
	 *
	 * @param \ElggEntity $entity   Entity that owns the icon
	 * @param string      $size     Size of the icon
	 * @param string      $type     The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param bool        $generate Try to generate an icon based on master if size doesn't exists
	 *
	 * @return \ElggIcon
	 *
	 * @throws InvalidParameterException
	 */
	public function getIcon(\ElggEntity $entity, $size, $type = 'icon', $generate = true) {

		$size = elgg_strtolower($size);

		$params = [
			'entity' => $entity,
			'size' => $size,
			'type' => $type,
		];

		$entity_type = $entity->getType();

		$default_icon = new \ElggIcon();
		$default_icon->owner_guid = $entity->guid;
		$default_icon->setFilename("icons/$type/$size.jpg");

		$icon = $this->hooks->trigger("entity:$type:file", $entity_type, $params, $default_icon);
		if (!$icon instanceof \ElggIcon) {
			throw new InvalidParameterException("'entity:$type:file', $entity_type hook must return an instance of ElggIcon");
		}
		
		if ($icon->exists() || !$generate) {
			return $icon;
		}
		
		if ($size === 'master') {
			// don't try to generate for master
			return $icon;
		}
		
		// try to generate icon based on master size
		$master_icon = $this->getIcon($entity, 'master', $type, false);
		if (!$master_icon->exists()) {
			return $icon;
		}
		
		if ($type === 'icon') {
			$coords = [
				'x1' => $entity->x1,
				'y1' => $entity->y1,
				'x2' => $entity->x2,
				'y2' => $entity->y2,
			];
		} else {
			$coords = $entity->{"{$type}_coords"};
			$coords = empty($coords) ? [] : unserialize($coords);
		}
		
		$this->generateIcon($entity, $master_icon, $type, $coords, $size);
		
		return $icon;
	}

	/**
	 * Removes all icon files and metadata for the passed type of icon.
	 *
	 * @param \ElggEntity $entity        Entity that owns icons
	 * @param string      $type          The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param bool        $retain_master Keep the master icon (default: false)
	 *
	 * @return bool
	 */
	public function deleteIcon(\ElggEntity $entity, $type = 'icon', $retain_master = false) {
		$delete = $this->hooks->trigger("entity:$type:delete", $entity->getType(), [
			'entity' => $entity,
		], true);

		if ($delete === false) {
			return false;
		}
		
		$result = true;

		$sizes = array_keys($this->getSizes($entity->getType(), $entity->getSubtype(), $type));
		foreach ($sizes as $size) {
			if ($size === 'master' && $retain_master) {
				continue;
			}
			
			$icon = $this->getIcon($entity, $size, $type, false);
			$result &= $icon->delete();
		}

		if ($type == 'icon') {
			unset($entity->icontime);
			unset($entity->x1);
			unset($entity->y1);
			unset($entity->x2);
			unset($entity->y2);
		} else {
			unset($entity->{"{$type}_coords"});
		}
		
		return $result;
	}

	/**
	 * Get the URL for this entity's icon
	 *
	 * Plugins can register for the 'entity:icon:url', <type> plugin hook to customize the icon for an entity.
	 *
	 * @param \ElggEntity $entity Entity that owns the icon
	 * @param mixed       $params A string defining the size of the icon (e.g. tiny, small, medium, large)
	 *                            or an array of parameters including 'size'
	 * @return string|void
	 */
	public function getIconURL(\ElggEntity $entity, $params = []) {
		if (is_array($params)) {
			$size = elgg_extract('size', $params, 'medium');
		} else {
			$size = is_string($params) ? $params : 'medium';
			$params = [];
		}

		$size = elgg_strtolower($size);

		$params['entity'] = $entity;
		$params['size'] = $size;

		$type = elgg_extract('type', $params) ? : 'icon';
		$entity_type = $entity->getType();

		$url = $this->hooks->trigger("entity:$type:url", $entity_type, $params, null);
		if ($url == null) {
			if ($this->hasIcon($entity, $size, $type)) {
				$icon = $this->getIcon($entity, $size, $type);
				$default_use_cookie = (bool) elgg_get_config('session_bound_entity_icons', false);
				$url = $icon->getInlineURL((bool) elgg_extract('use_cookie', $params, $default_use_cookie));
			} else {
				$url = $this->getFallbackIconUrl($entity, $params);
			}
		}

		if ($url) {
			return elgg_normalize_url($url);
		}
	}

	/**
	 * Returns default/fallback icon
	 *
	 * @param \ElggEntity $entity Entity
	 * @param array       $params Icon params
	 *
	 * @return string
	 */
	public function getFallbackIconUrl(\ElggEntity $entity, array $params = []) {

		$type = elgg_extract('type', $params) ? : 'icon';
		$size = elgg_extract('size', $params) ? : 'medium';
		
		$entity_type = $entity->getType();
		$entity_subtype = $entity->getSubtype();

		$exts = ['svg', 'gif', 'png', 'jpg'];

		foreach ($exts as $ext) {
			foreach ([$entity_subtype, 'default'] as $subtype) {
				if ($ext == 'svg' && elgg_view_exists("$type/$entity_type/$subtype.svg", 'default')) {
					return elgg_get_simplecache_url("$type/$entity_type/$subtype.svg");
				}
				if (elgg_view_exists("$type/$entity_type/$subtype/$size.$ext", 'default')) {
					return elgg_get_simplecache_url("$type/$entity_type/$subtype/$size.$ext");
				}
			}
		}

		if (elgg_view_exists("$type/default/$size.png", 'default')) {
			return elgg_get_simplecache_url("$type/default/$size.png");
		}
	}

	/**
	 * Returns the timestamp of when the icon was changed.
	 *
	 * @param \ElggEntity $entity Entity that owns the icon
	 * @param string      $size   The size of the icon
	 * @param string      $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return int|null A unix timestamp of when the icon was last changed, or null if not set.
	 */
	public function getIconLastChange(\ElggEntity $entity, $size, $type = 'icon') {
		$icon = $this->getIcon($entity, $size, $type);
		if ($icon->exists()) {
			return $icon->getModifiedTime();
		}
	}

	/**
	 * Returns if the entity has an icon of the passed type.
	 *
	 * @param \ElggEntity $entity Entity that owns the icon
	 * @param string      $size   The size of the icon
	 * @param string      $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return bool
	 */
	public function hasIcon(\ElggEntity $entity, $size, $type = 'icon') {
		return $this->getIcon($entity, $size, $type)->exists();
	}

	/**
	 * Returns a configuration array of icon sizes
	 *
	 * @param string $entity_type    Entity type
	 * @param string $entity_subtype Entity subtype
	 * @param string $type           The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return array
	 * @throws InvalidParameterException
	 */
	public function getSizes($entity_type = null, $entity_subtype = null, $type = 'icon') {
		$sizes = [];
		if (!$type) {
			$type = 'icon';
		}
		if ($type == 'icon') {
			$sizes = $this->config->icon_sizes;
		}
		$params = [
			'type' => $type,
			'entity_type' => $entity_type,
			'entity_subtype' => $entity_subtype,
		];
		if ($entity_type) {
			$sizes = $this->hooks->trigger("entity:$type:sizes", $entity_type, $params, $sizes);
		}

		if (!is_array($sizes)) {
			throw new InvalidParameterException("The icon size configuration for image type '$type' " .
				"must be an associative array of image size names and their properties");
		}

		// lazy generation of icons requires a 'master' size
		// this ensures a default config for 'master' size
		$sizes['master'] = elgg_extract('master', $sizes, [
			'w' => 10240,
			'h' => 10240,
			'square' => false,
			'upscale' => false,
			'crop' => false,
		]);
		
		if (!isset($sizes['master']['crop'])) {
			$sizes['master']['crop'] = false;
		}
		
		return $sizes;
	}
	
	/**
	 * Automagicly detect cropping coordinates
	 *
	 * Based in the input names x1, x2, y1 and y2
	 *
	 * @return false|array
	 */
	protected function detectCroppingCoordinates() {
		
		$auto_coords = [
			'x1' => get_input('x1'),
			'x2' => get_input('x2'),
			'y1' => get_input('y1'),
			'y2' => get_input('y2'),
		];
		
		$auto_coords = array_filter($auto_coords, function($value) {
			return !elgg_is_empty($value) && is_numeric($value);
		});
		
		if (count($auto_coords) !== 4) {
			return false;
		}
		
		// make ints
		array_walk($auto_coords, function (&$value) {
			$value = (int) $value;
		});
		
		return $auto_coords;
	}

}
