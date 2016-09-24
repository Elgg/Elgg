<?php

namespace Elgg;

use DateTime;
use Elgg\Database\EntityTable;
use Elgg\Filesystem\MimeTypeDetector;
use Elgg\Http\Request;
use ElggEntity;
use ElggFile;
use ElggIcon;
use InvalidParameterException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 * @since 2.2
 */
class EntityIconService {
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
	 * @var Request
	 */
	private $request;

	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * @var EntityTable
	 */
	private $entities;

	/**
	 * Constructor
	 *
	 * @param Config             $config   Config
	 * @param PluginHooksService $hooks    Hook registration service
	 * @param Request            $request  Http request
	 * @param Logger             $logger   Logger
	 * @param EntityTable        $entities Entity table
	 */
	public function __construct(Config $config, PluginHooksService $hooks, Request $request, Logger $logger, EntityTable $entities) {
		$this->config = $config;
		$this->hooks = $hooks;
		$this->request = $request;
		$this->logger = $logger;
		$this->entities = $entities;
	}

	/**
	 * Saves icons using an uploaded file as the source.
	 *
	 * @param ElggEntity $entity     Entity to own the icons
	 * @param string     $input_name Form input name
	 * @param string     $type       The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array      $coords     An array of cropping coordinates x1, y1, x2, y2
	 * @return bool
	 */
	public function saveIconFromUploadedFile(ElggEntity $entity, $input_name, $type = 'icon', array $coords = array()) {
		$files = $this->request->files;
		if (!$files->has($input_name)) {
			return false;
		}

		$input = $files->get($input_name);
		if (!$input instanceof UploadedFile || !$input->isValid()) {
			return false;
		}

		$tmp_filename = time() . $input->getClientOriginalName();
		$tmp = new ElggFile();
		$tmp->owner_guid = $entity->guid;
		$tmp->setFilename("tmp/$tmp_filename");
		$tmp->open('write');
		$tmp->close();
		// not using move_uploaded_file() for testing purposes
		copy($input->getPathname(), $tmp->getFilenameOnFilestore());

		$tmp->mimetype = (new MimeTypeDetector())->getType($tmp_filename, $input->getClientMimeType());
		$tmp->simpletype = elgg_get_file_simple_type($tmp->mimetype);

		$result = $this->saveIcon($entity, $tmp, $type, $coords);

		unlink($input->getPathname());
		$tmp->delete();

		return $result;
	}

	/**
	 * Saves icons using a local file as the source.
	 *
	 * @param ElggEntity $entity   Entity to own the icons
	 * @param string     $filename The full path to the local file
	 * @param string     $type     The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array      $coords   An array of cropping coordinates x1, y1, x2, y2
	 * @return bool
	 * @throws InvalidParameterException
	 */
	public function saveIconFromLocalFile(ElggEntity $entity, $filename, $type = 'icon', array $coords = array()) {
		if (!file_exists($filename) || !is_readable($filename)) {
			throw new InvalidParameterException(__METHOD__ . " expects a readable local file. $filename is not readable");
		}

		$tmp_filename = time() . pathinfo($filename, PATHINFO_BASENAME);
		$tmp = new ElggFile();
		$tmp->owner_guid = $entity->guid;
		$tmp->setFilename("tmp/$tmp_filename");
		$tmp->open('write');
		$tmp->close();
		copy($filename, $tmp->getFilenameOnFilestore());

		$tmp->mimetype = (new MimeTypeDetector())->getType($tmp->getFilenameOnFilestore());
		$tmp->simpletype = elgg_get_file_simple_type($tmp->mimetype);

		$result = $this->saveIcon($entity, $tmp, $type, $coords);

		$tmp->delete();

		return $result;
	}

	/**
	 * Saves icons using a file located in the data store as the source.
	 *
	 * @param ElggEntity $entity Entity to own the icons
	 * @param ElggFile   $file   An ElggFile instance
	 * @param string     $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array      $coords An array of cropping coordinates x1, y1, x2, y2
	 * @return bool
	 * @throws InvalidParameterException
	 */
	public function saveIconFromElggFile(ElggEntity $entity, ElggFile $file, $type = 'icon', array $coords = array()) {
		if (!$file->exists()) {
			throw new InvalidParameterException(__METHOD__ . ' expects an instance of ElggFile with an existing file on filestore');
		}

		$tmp_filename = time() . pathinfo($file->getFilenameOnFilestore(), PATHINFO_BASENAME);
		$tmp = new ElggFile();
		$tmp->owner_guid = $entity->guid;
		$tmp->setFilename("tmp/$tmp_filename");
		$tmp->open('write');
		$tmp->close();
		copy($file->getFilenameOnFilestore(), $tmp->getFilenameOnFilestore());

		$tmp->mimetype = (new MimeTypeDetector())->getType($tmp->getFilenameOnFilestore(), $file->getMimeType());
		$tmp->simpletype = elgg_get_file_simple_type($tmp->mimetype);

		$result = $this->saveIcon($entity, $tmp, $type, $coords);

		$tmp->delete();

		return $result;
	}

	/**
	 * Saves icons using a created temporary file
	 *
	 * @param ElggEntity $entity Temporary ElggFile instance
	 * @param ElggFile   $file   Temporary ElggFile instance
	 * @param string     $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array      $coords An array of cropping coordinates x1, y1, x2, y2
	 * @return bool
	 */
	public function saveIcon(ElggEntity $entity, ElggFile $file, $type = 'icon', array $coords = array()) {

		$entity_type = $entity->getType();
		$entity_subtype = $entity->getSubtype();

		$x1 = (int) elgg_extract('x1', $coords);
		$y1 = (int) elgg_extract('y1', $coords);
		$x2 = (int) elgg_extract('x2', $coords);
		$y2 = (int) elgg_extract('y2', $coords);

		$file = $this->hooks->trigger("entity:$type:prepare", $entity_type, [
			'entity' => $entity,
			'file' => $file,
				], $file);

		if (!$file instanceof ElggFile || !$file->exists() || $file->getSimpleType() !== 'image') {
			$this->logger->error('Source file passed to ' . __METHOD__ . ' can not be resolved to a valid image');
			return false;
		}

		$cropping_mode = ($x2 > $x1) && ($y2 > $y1);
		if (!$cropping_mode) {
			$this->deleteIcon($entity, $type);
		}

		$success = function() use ($entity, $type, $x1, $y1, $x2, $y2) {
			if ($type == 'icon') {
				$entity->icontime = time();
				if ($x1 || $y1 || $x2 || $y2) {
					$entity->x1 = $x1;
					$entity->y1 = $y1;
					$entity->x2 = $x2;
					$entity->y2 = $y2;
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
		};

		$fail = function() use ($entity, $type) {
			$this->deleteIcon($entity, $type);
			return false;
		};

		$created = $this->hooks->trigger("entity:$type:save", $entity_type, [
			'entity' => $entity,
			'file' => $file,
			'x1' => $x1,
			'y1' => $y1,
			'x2' => $x2,
			'y2' => $y2,
				], false);

		if ($created === true) {
			return $success();
		}

		$sizes = $this->getSizes($entity_type, $entity_subtype, $type);

		foreach ($sizes as $size => $opts) {

			$square = (bool) elgg_extract('square', $opts);

			if ($type === 'icon' && $cropping_mode) {
				// Do not crop out non-square icons if cropping coordinates are a square
				$cropping_ratio = ($x2 - $x1) / ($y2 - $y1);
				if ($cropping_ratio == 1 && $square === false) {
					continue;
				}
			}

			$icon = $this->getIcon($entity, $size, $type);

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
			
			if (!_elgg_services()->imageService->resize($source, $destination, $resize_params)) {
				$this->logger->error("Failed to create {$size} icon from
					{$file->getFilenameOnFilestore()} with coords [{$x1}, {$y1}],[{$x2}, {$y2}]");
				return $fail();
			}
		}

		return $success();
	}

	/**
	 * Returns entity icon as an ElggIcon object
	 * The icon file may or may not exist on filestore
	 *
	 * @note Returned ElggIcon object may be a placeholder. Use ElggIcon::exists() to validate if file has been written to filestore
	 *
	 * @param ElggEntity $entity Entity that owns the icon
	 * @param string     $size   Size of the icon
	 * @param string     $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 * @return ElggIcon
	 * @throws InvalidParameterException
	 */
	public function getIcon(ElggEntity $entity, $size, $type = 'icon') {

		$size = elgg_strtolower($size);

		$params = [
			'entity' => $entity,
			'size' => $size,
			'type' => $type,
		];

		$entity_type = $entity->getType();

		$default_icon = new ElggIcon();
		$default_icon->owner_guid = $entity->guid;
		$default_icon->setFilename("icons/$type/$size.jpg");

		$icon = $this->hooks->trigger("entity:$type:file", $entity_type, $params, $default_icon);
		if (!$icon instanceof ElggIcon) {
			throw new InvalidParameterException("'entity:$type:file', $entity_type hook must return an instance of ElggIcon");
		}

		return $icon;
	}

	/**
	 * Removes all icon files and metadata for the passed type of icon.
	 *
	 * @param ElggEntity $entity Entity that owns icons
	 * @param string     $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 * @return bool
	 */
	public function deleteIcon(ElggEntity $entity, $type = 'icon') {
		$delete = $this->hooks->trigger("entity:$type:delete", $entity->getType(), [
			'entity' => $entity,
				], true);

		if ($delete === false) {
			return;
		}

		$sizes = array_keys($this->getSizes($entity->getType(), $entity->getSubtype(), $type));
		foreach ($sizes as $size) {
			$icon = $this->getIcon($entity, $size, $type);
			$icon->delete();
		}

		if ($type == 'icon') {
			unset($entity->icontime);
			unset($entity->x1);
			unset($entity->y1);
			unset($entity->x2);
			unset($entity->y2);
		}
	}

	/**
	 * Get the URL for this entity's icon
	 *
	 * Plugins can register for the 'entity:icon:url', <type> plugin hook to customize the icon for an entity.
	 *
	 * @param ElggEntity $entity Entity that owns the icon
	 * @param mixed      $params A string defining the size of the icon (e.g. tiny, small, medium, large)
	 *                           or an array of parameters including 'size'
	 * @return string
	 */
	public function getIconURL(ElggEntity $entity, $params = array()) {
		if (is_array($params)) {
			$size = elgg_extract('size', $params, 'medium');
		} else {
			$size = is_string($params) ? $params : 'medium';
			$params = array();
		}

		$size = elgg_strtolower($size);

		$params['entity'] = $entity;
		$params['size'] = $size;

		$type = elgg_extract('type', $params) ? : 'icon';
		$entity_type = $entity->getType();

		$url = $this->hooks->trigger("entity:$type:url", $entity_type, $params, null);
		if ($url == null) {
			$icon = $this->getIcon($entity, $size, $type);
			$url = elgg_get_inline_url($icon, true);
			if (!$url && $type == 'icon') {
				$url = elgg_get_simplecache_url("icons/default/$size.png");
			}
		}

		return elgg_normalize_url($url);
	}

	/**
	 * Returns the timestamp of when the icon was changed.
	 *
	 * @param ElggEntity $entity Entity that owns the icon
	 * @param string     $size   The size of the icon
	 * @param string     $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return int|null A unix timestamp of when the icon was last changed, or null if not set.
	 */
	public function getIconLastChange(ElggEntity $entity, $size, $type = 'icon') {
		$icon = $this->getIcon($entity, $size, $type);
		if ($icon->exists()) {
			return $icon->getModifiedTime();
		}
	}

	/**
	 * Returns if the entity has an icon of the passed type.
	 *
	 * @param ElggEntity $entity Entity that owns the icon
	 * @param string     $size   The size of the icon
	 * @param string     $type   The name of the icon. e.g., 'icon', 'cover_photo'
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
	 * @return array
	 * @throws InvalidParameterException
	 */
	public function getSizes($entity_type = null, $entity_subtype = null, $type = 'icon') {
		$sizes = [];
		if (!$type) {
			$type = 'icon';
		}
		if ($type == 'icon') {
			$sizes = $this->config->get('icon_sizes');
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

		if (empty($sizes)) {
			$this->logger->error("Failed to find size configuration for image of type '$type' for entity type " .
				"'$entity_type'. Use the 'entity:$type:sizes, $entity_type' hook to define the icon sizes");

		}

		return $sizes;
	}

	/**
	 * Handle request to /serve-icon handler
	 *
	 * @param bool $allow_removing_headers Alter PHP's global headers to allow caching
	 * @return BinaryFileResponse
	 */
	public function handleServeIconRequest($allow_removing_headers = true) {

		$response = new Response();
		$response->setExpires($this->getCurrentTime('-1 day'));
		$response->prepare($this->request);

		if ($allow_removing_headers) {
			// clear cache-boosting headers set by PHP session
			header_remove('Cache-Control');
			header_remove('Pragma');
			header_remove('Expires');
		}

		$path = implode('/', $this->request->getUrlSegments());
		if (!preg_match('~serve-icon/(\d+)/(.*+)$~', $path, $m)) {
			return $response->setStatusCode(400)->setContent('Malformatted request URL');
		}

		list(, $guid, $size) = $m;

		$entity = $this->entities->get($guid);
		if (!$entity instanceof \ElggEntity) {
			return $response->setStatusCode(404)->setContent('Item does not exist');
		}

		$thumbnail = $entity->getIcon($size);
		if (!$thumbnail->exists()) {
			return $response->setStatusCode(404)->setContent('Icon does not exist');
		}

		$if_none_match = $this->request->headers->get('if_none_match');
		if (!empty($if_none_match)) {
			// strip mod_deflate suffixes
			$this->request->headers->set('if_none_match', str_replace('-gzip', '', $if_none_match));
		}

		$filenameonfilestore = $thumbnail->getFilenameOnFilestore();
		$last_updated = filemtime($filenameonfilestore);
		$etag = '"' . $last_updated . '"';

		$response->setPrivate()
			->setEtag($etag)
			->setExpires($this->getCurrentTime('+1 day'))
			->setMaxAge(86400);

		if ($response->isNotModified($this->request)) {
			return $response;
		}

		$headers = [
			'Content-Type' => (new MimeTypeDetector())->getType($filenameonfilestore),
		];
		$response = new BinaryFileResponse($filenameonfilestore, 200, $headers, false, 'inline');
		$response->prepare($this->request);

		$response->setPrivate()
			->setEtag($etag)
			->setExpires($this->getCurrentTime('+1 day'))
			->setMaxAge(86400);

		return $response;
	}

}
