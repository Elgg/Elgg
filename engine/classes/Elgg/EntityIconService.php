<?php

namespace Elgg;

use Elgg\Database\EntityTable;
use Elgg\Exceptions\ExceptionInterface;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Exceptions\UnexpectedValueException;
use Elgg\Filesystem\MimeTypeService;
use Elgg\Http\Request as HttpRequest;
use Elgg\Traits\Loggable;
use Elgg\Traits\TimeUsing;

/**
 * Entity icon service
 *
 * @internal
 * @since 2.2
 */
class EntityIconService {

	use Loggable;
	use TimeUsing;

	/**
	 * Constructor
	 *
	 * @param Config          $config   Config
	 * @param EventsService   $events   Events service
	 * @param EntityTable     $entities Entity table
	 * @param UploadService   $uploads  Upload service
	 * @param ImageService    $images   Image service
	 * @param MimeTypeService $mimetype MimeType service
	 * @param Request         $request  Http Request service
	 */
	public function __construct(
		protected Config $config,
		protected EventsService $events,
		protected EntityTable $entities,
		protected UploadService $uploads,
		protected ImageService $images,
		protected MimeTypeService $mimetype,
		protected HttpRequest $request
	) {
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
	public function saveIconFromUploadedFile(\ElggEntity $entity, string $input_name, string $type = 'icon', array $coords = []): bool {
		$input = $this->uploads->getFile($input_name);
		if (empty($input)) {
			return false;
		}
				
		// auto detect cropping coordinates
		if (empty($coords)) {
			$auto_coords = $this->detectCroppingCoordinates($input_name);
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
	 * @throws InvalidArgumentException
	 */
	public function saveIconFromLocalFile(\ElggEntity $entity, string $filename, string $type = 'icon', array $coords = []): bool {
		if (!file_exists($filename) || !is_readable($filename)) {
			throw new InvalidArgumentException(__METHOD__ . " expects a readable local file. {$filename} is not readable");
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
	 * @throws InvalidArgumentException
	 */
	public function saveIconFromElggFile(\ElggEntity $entity, \ElggFile $file, string $type = 'icon', array $coords = []): bool {
		if (!$file->exists()) {
			throw new InvalidArgumentException(__METHOD__ . ' expects an instance of ElggFile with an existing file on filestore');
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
	public function saveIcon(\ElggEntity $entity, \ElggFile $file, string $type = 'icon', array $coords = []): bool {
		if (!strlen($type)) {
			$this->getLogger()->error('Icon type passed to ' . __METHOD__ . ' can not be empty');
			return false;
		}
		
		$entity_type = $entity->getType();
		
		$file = $this->events->triggerResults("entity:{$type}:prepare", $entity_type, [
			'entity' => $entity,
			'file' => $file,
		], $file);
		
		if (!$file instanceof \ElggFile || !$file->exists() || $file->getSimpleType() !== 'image') {
			$this->getLogger()->error('Source file passed to ' . __METHOD__ . ' can not be resolved to a valid image');
			return false;
		}
		
		$entity->lockIconThumbnailGeneration($type);
		
		$this->prepareIcon($file->getFilenameOnFilestore());
		
		$x1 = (int) elgg_extract('x1', $coords);
		$y1 = (int) elgg_extract('y1', $coords);
		$x2 = (int) elgg_extract('x2', $coords);
		$y2 = (int) elgg_extract('y2', $coords);
		
		$created = $this->events->triggerResults("entity:{$type}:save", $entity_type, [
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
			
			// validate cropping coords to prevent out-of-bounds issues
			$sizes = $this->getSizes($entity->getType(), $entity->getSubtype(), $type);
			$coords = array_merge($sizes['master'], $coords);
			
			$icon = $this->getIcon($entity, 'master', $type, false);
			
			try {
				$this->images->normalizeResizeParameters($icon->getFilenameOnFilestore(), $coords);
			} catch (ExceptionInterface $e) {
				// cropping coords are wrong, reset to 0
				$x1 = 0;
				$x2 = 0;
				$y1 = 0;
				$y2 = 0;
			}
		}

		// first invalidate entity metadata cache, because of a high risk of racing condition to save the coordinates
		// the racing condition occurs with 2 (or more) icon save calls and the time between clearing
		// the coordinates in deleteIcon() and the new save here
		$entity->invalidateCache();
		
		if ($x1 || $y1 || $x2 || $y2) {
			$entity->saveIconCoordinates($coords, $type);
		}
		
		$this->events->triggerResults("entity:{$type}:saved", $entity->getType(), [
			'entity' => $entity,
			'x1' => $x1,
			'y1' => $y1,
			'x2' => $x2,
			'y2' => $y2,
		]);
		
		$entity->unlockIconThumbnailGeneration($type);
		
		return true;
	}
	
	/**
	 * Prepares an icon
	 *
	 * @param string $filename the file to prepare
	 *
	 * @return void
	 */
	protected function prepareIcon(string $filename): void {
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
	protected function generateIcon(\ElggEntity $entity, \ElggFile $file, string $type = 'icon', array $coords = [], string $icon_size = ''): bool {
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
				
				if ($size !== 'master') {
					// remove 0 byte icon in order to retry the resize on the next request
					$icon->delete();
				}
				
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
	 * @throws UnexpectedValueException
	 */
	public function getIcon(\ElggEntity $entity, string $size, string $type = 'icon', bool $generate = true): \ElggIcon {
		$size = elgg_strtolower($size);

		$params = [
			'entity' => $entity,
			'size' => $size,
			'type' => $type,
		];

		$entity_type = $entity->getType();

		$default_icon = new \ElggIcon();
		$default_icon->owner_guid = $entity->guid;
		$default_icon->setFilename("icons/{$type}/{$size}.jpg");

		$icon = $this->events->triggerResults("entity:{$type}:file", $entity_type, $params, $default_icon);
		if (!$icon instanceof \ElggIcon) {
			throw new UnexpectedValueException("'entity:{$type}:file', {$entity_type} event must return an instance of \ElggIcon");
		}
		
		if ($size !== 'master' && $this->hasWebPSupport()) {
			if (pathinfo($icon->getFilename(), PATHINFO_EXTENSION) === 'jpg') {
				$icon->setFilename(substr($icon->getFilename(), 0, -3) . 'webp');
			}
		}
		
		if ($icon->exists() || !$generate) {
			return $icon;
		}
		
		if ($size === 'master') {
			// don't try to generate for master
			return $icon;
		}
		
		if ($entity->isIconThumbnailGenerationLocked($type)) {
			return $icon;
		}
		
		// try to generate icon based on master size
		$master_icon = $this->getIcon($entity, 'master', $type, false);
		if (!$master_icon->exists()) {
			return $icon;
		}
		
		$coords = $entity->getIconCoordinates($type);
		
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
	public function deleteIcon(\ElggEntity $entity, string $type = 'icon', bool $retain_master = false): bool {
		$delete = $this->events->triggerResults("entity:{$type}:delete", $entity->getType(), [
			'entity' => $entity,
			'retain_master' => $retain_master, // just removing thumbs or everything?
		], true);

		if ($delete === false) {
			return false;
		}
		
		$result = true;
		$supported_extensions = [
			'jpg',
		];
		if ($this->images->hasWebPSupport()) {
			$supported_extensions[] = 'webp';
		}

		$sizes = array_keys($this->getSizes($entity->getType(), $entity->getSubtype(), $type));
		foreach ($sizes as $size) {
			if ($size === 'master' && $retain_master) {
				continue;
			}
			
			$icon = $this->getIcon($entity, $size, $type, false);
			$result &= $icon->delete();
			
			// make sure we remove all supported images (jpg and webp)
			$current_extension = pathinfo($icon->getFilename(), PATHINFO_EXTENSION);
			$extensions = $supported_extensions;
			foreach ($extensions as $extension) {
				if ($current_extension === $extension) {
					// already removed
					continue;
				}
				
				// replace the extension
				$parts = explode('.', $icon->getFilename());
				array_pop($parts);
				$parts[] = $extension;
				
				// set new filename and remove the file
				$icon->setFilename(implode('.', $parts));
				$result &= $icon->delete();
			}
		}

		$entity->removeIconCoordinates($type);
		
		return $result;
	}

	/**
	 * Get the URL for this entity's icon
	 *
	 * Plugins can register for the 'entity:icon:url', <type> event to customize the icon for an entity.
	 *
	 * @param \ElggEntity $entity Entity that owns the icon
	 * @param mixed       $params A string defining the size of the icon (e.g. tiny, small, medium, large)
	 *                            or an array of parameters including 'size'
	 *
	 * @return string
	 */
	public function getIconURL(\ElggEntity $entity, string|array $params = []): string {
		if (is_array($params)) {
			$size = elgg_extract('size', $params, 'medium');
		} else {
			$size = is_string($params) ? $params : 'medium';
			$params = [];
		}

		$size = elgg_strtolower($size);

		$params['entity'] = $entity;
		$params['size'] = $size;

		$type = elgg_extract('type', $params, 'icon', false);
		$entity_type = $entity->getType();

		$url = $this->events->triggerResults("entity:{$type}:url", $entity_type, $params, null);
		if (!isset($url)) {
			if ($this->hasIcon($entity, $size, $type)) {
				$icon = $this->getIcon($entity, $size, $type);
				$default_use_cookie = (bool) elgg_get_config('session_bound_entity_icons');
				$url = $icon->getInlineURL((bool) elgg_extract('use_cookie', $params, $default_use_cookie));
			} else {
				$url = $this->getFallbackIconUrl($entity, $params);
			}
		}

		if (!empty($url)) {
			return elgg_normalize_url($url);
		}
		
		return '';
	}

	/**
	 * Returns default/fallback icon
	 *
	 * @param \ElggEntity $entity Entity
	 * @param array       $params Icon params
	 *
	 * @return string
	 */
	public function getFallbackIconUrl(\ElggEntity $entity, array $params = []): string {
		$type = elgg_extract('type', $params, 'icon', false);
		$size = elgg_extract('size', $params, 'medium', false);
		
		$entity_type = $entity->getType();
		$entity_subtype = $entity->getSubtype();

		$exts = ['svg', 'gif', 'png', 'jpg'];

		foreach ($exts as $ext) {
			foreach ([$entity_subtype, 'default'] as $subtype) {
				if ($ext == 'svg' && elgg_view_exists("{$type}/{$entity_type}/{$subtype}.svg", 'default')) {
					return elgg_get_simplecache_url("{$type}/{$entity_type}/{$subtype}.svg");
				}
				
				if (elgg_view_exists("{$type}/{$entity_type}/{$subtype}/{$size}.{$ext}", 'default')) {
					return elgg_get_simplecache_url("{$type}/{$entity_type}/{$subtype}/{$size}.{$ext}");
				}
			}
		}

		if (elgg_view_exists("{$type}/default/{$size}.png", 'default')) {
			return elgg_get_simplecache_url("{$type}/default/{$size}.png");
		}
		
		return '';
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
	public function getIconLastChange(\ElggEntity $entity, string $size, string $type = 'icon'): ?int {
		$icon = $this->getIcon($entity, $size, $type);
		if ($icon->exists()) {
			return $icon->getModifiedTime();
		}
		
		return null;
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
	public function hasIcon(\ElggEntity $entity, string $size, string $type = 'icon'): bool {
		$icon = $this->getIcon($entity, $size, $type);
		return $icon->exists() && $icon->getSize() > 0;
	}

	/**
	 * Returns a configuration array of icon sizes
	 *
	 * @param string|null $entity_type    Entity type
	 * @param string|null $entity_subtype Entity subtype
	 * @param string      $type           The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return array
	 * @throws InvalidArgumentException
	 */
	public function getSizes(?string $entity_type = null, ?string $entity_subtype = null, string $type = 'icon'): array {
		$sizes = [];
		$type = $type ?: 'icon';
		if ($type === 'icon') {
			$sizes = $this->config->icon_sizes;
		}
		
		$params = [
			'type' => $type,
			'entity_type' => $entity_type,
			'entity_subtype' => $entity_subtype,
		];
		if ($entity_type) {
			$sizes = $this->events->triggerResults("entity:{$type}:sizes", $entity_type, $params, $sizes);
		}

		if (!is_array($sizes)) {
			$msg = "The icon size configuration for image type '{$type}'";
			$msg .= ' must be an associative array of image size names and their properties';
			throw new InvalidArgumentException($msg);
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
	 * @param string $input_name the file input name which is the prefix for the cropping coordinates
	 *
	 * @return null|array
	 */
	protected function detectCroppingCoordinates(string $input_name): ?array {
		$auto_coords = [
			'x1' => get_input("{$input_name}_x1", get_input('x1')), // x1 is BC fallback
			'x2' => get_input("{$input_name}_x2", get_input('x2')), // x2 is BC fallback
			'y1' => get_input("{$input_name}_y1", get_input('y1')), // y1 is BC fallback
			'y2' => get_input("{$input_name}_y2", get_input('y2')), // y2 is BC fallback
		];
		
		$auto_coords = array_filter($auto_coords, function($value) {
			return !elgg_is_empty($value) && is_numeric($value) && (int) $value >= 0;
		});
		
		if (count($auto_coords) !== 4) {
			return null;
		}
		
		// make ints
		array_walk($auto_coords, function (&$value) {
			$value = (int) $value;
		});
		
		// make sure coords make sense x2 > x1 && y2 > y1
		if ($auto_coords['x2'] <= $auto_coords['x1'] || $auto_coords['y2'] <= $auto_coords['y1']) {
			return null;
		}
		
		return $auto_coords;
	}

	/**
	 * Checks if browser has WebP support and if the webserver is able to generate
	 *
	 * @return bool
	 */
	protected function hasWebPSupport(): bool {
		return in_array('image/webp', $this->request->getAcceptableContentTypes()) && $this->images->hasWebPSupport();
	}
}
