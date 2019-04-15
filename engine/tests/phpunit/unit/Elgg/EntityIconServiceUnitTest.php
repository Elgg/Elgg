<?php

namespace Elgg;

/**
 * @group EntityIconService
 * @group UnitTests
 */
class EntityIconServiceUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var \Elgg\PluginHooksService
	 */
	private $hooks;

	/**
	 * @var \Elgg\Http\Request
	 */
	private $request;

	/**
	 * @var \Elgg\Logger
	 */
	private $logger;

	/**
	 * @var \Elgg\Database\EntityTable
	 */
	private $entities;

	/**
	 * @var \Elgg\UploadService
	 */
	private $uploads;

	/**
	 * @var \Elgg\ImageService
	 */
	private $images;

	/**
	 * @var \ElggObject
	 */
	private $entity;

	/**
	 * @var \ElggUser
	 */
	private $user;

	/**
	 * @var string
	 */
	private $entity_dir_path;

	/**
	 * @var string
	 */
	private $owner_dir_path;

	public function up() {

		$this->hooks = new PluginHooksService(_elgg_services()->events);
		
		// we will need simpletype hook to work
		_elgg_filestore_init();
		
		$this->request = \Elgg\Http\Request::create("/action/upload");
		$this->logger = _elgg_services()->logger;
		$this->logger->setHooks($this->hooks);

		$this->entities = _elgg_services()->entityTable;
		$this->uploads = new \Elgg\UploadService($this->request);
		$this->images = _elgg_services()->imageService;

		$this->user = $this->createUser();
		$this->entity = $this->createObject([
			'owner_guid' => $this->user->guid,
			'subtype' => 'foo',
		]);

		$dir = (new \Elgg\EntityDirLocator($this->entity->guid))->getPath();
		$this->entity_dir_path = _elgg_config()->dataroot . $dir;
		if (is_dir($this->entity_dir_path)) {
			_elgg_rmdir($this->entity_dir_path);
		}

		$dir = (new \Elgg\EntityDirLocator($this->entity->owner_guid))->getPath();
		$this->owner_dir_path = _elgg_config()->dataroot . $dir;
		if (is_dir($this->owner_dir_path)) {
			_elgg_rmdir($this->owner_dir_path);
		}
		// Needed to test elgg_get_inline_url()
		$session = \ElggSession::getMock();
		_elgg_services()->setValue('session', $session);
		_elgg_services()->session->start();
	}

	public function down() {
		$this->assertTrue(file_exists(_elgg_config()->dataroot . '1/1/75x125.jpg'));
		$this->assertTrue(file_exists(_elgg_config()->dataroot . '1/1/300x300.jpg'));
		$this->assertTrue(file_exists(_elgg_config()->dataroot . '1/1/600x300.jpg'));
		$this->assertTrue(file_exists(_elgg_config()->dataroot . '1/1/300x600.jpg'));
		$this->assertTrue(file_exists(_elgg_config()->dataroot . '1/1/400x300.gif'));
		$this->assertTrue(file_exists(_elgg_config()->dataroot . '1/1/400x300.png'));
		if (is_dir($this->entity_dir_path)) {
			_elgg_rmdir($this->entity_dir_path);
		}

		if (is_dir($this->owner_dir_path)) {
			_elgg_rmdir($this->owner_dir_path);
		}
	}

	protected function createService() {
		return new \Elgg\EntityIconService(_elgg_config(), $this->hooks, $this->request, $this->logger, $this->entities, $this->uploads, $this->images);
	}
	
	public static function getDefaultIconSizes() {
		return [
			'master' => [
				'w' => 10240,
				'h' => 10240,
				'square' => false,
				'upscale' => false,
				'crop' => false,
			],
		];
	}

	public static function getCoverSizes() {
		$cover_sizes = [
			'medium' => [
				'w' => 1280,
				'h' => 720,
				'square' => false,
			],
			// Empty config means that image should not be altered
			'original' => [],
		];
		
		return array_merge($cover_sizes, self::getDefaultIconSizes());
	}

	public static function getIconSizesForSubtype($hook, $type, $sizes, $params) {
		$subtype = elgg_extract('entity_subtype', $params);
		$icon_type = elgg_extract('type', $params);
		if ($type == 'object' && $subtype == 'foo' && $icon_type == 'icon') {
			return self::getTestSizes();
		}
	}

	public static function getTestSizes() {
		$test_sizes = [
			'square' => [
				'w' => 120,
				'h' => 120,
				'square' => true,
			],
			'rectangle' => [
				'w' => 120,
				'h' => 120,
				'square' => false,
			]
		];
		
		return array_merge($test_sizes, self::getDefaultIconSizes());
	}

	/**
	 * @group IconService
	 */
	public function testGetDefaultSizes() {
		$service = $this->createService();

		// Should return config values, as we do not have any registered hook
		$this->assertEquals(_elgg_config()->icon_sizes, $service->getSizes());

		// If type is not 'icon', should return an default array with only 'master' size present
		$this->logger->disable();
		$this->assertEquals(self::getDefaultIconSizes(), $service->getSizes(null, null, 'foo'));
		$this->logger->enable();
	}

	/**
	 * @group IconService
	 */
	public function testCanSetSizesForCustomIconType() {
		$service = $this->createService();
		$this->logger->disable();
		$this->assertEquals(self::getDefaultIconSizes(), $service->getSizes('object', 'foo', 'cover'));
		$this->logger->enable();

		$this->hooks->registerHandler('entity:cover:sizes', 'object', array($this, 'getCoverSizes'));
		$service = $this->createService();
		$this->assertEquals(self::getCoverSizes(), $service->getSizes('object', 'foo', 'cover'));
	}

	/**
	 * @group IconService
	 */
	public function testCanFilterIconSizesForEntityTypeSubtypePair() {
		$this->hooks->registerHandler('entity:icon:sizes', 'object', array($this, 'getIconSizesForSubtype'));
		$service = $this->createService();
		$this->assertEquals(self::getTestSizes(), $service->getSizes('object', 'foo', 'icon'));
	}

	/**
	 * @group IconService
	 */
	public function testHasNoDefaultIcon() {
		$service = $this->createService();
		$this->assertFalse($service->hasIcon($this->entity, 'small'));
	}

	/**
	 * @group IconService
	 */
	public function testGetDefaultIconFile() {
		$service = $this->createService();

		$icon = $service->getIcon($this->entity, 'small');
		$this->assertEquals($this->entity_dir_path . 'icons/icon/small.jpg', $icon->getFilenameOnFilestore());

		$cover = $service->getIcon($this->entity, 'small', 'cover');
		$this->assertEquals($this->entity_dir_path . 'icons/cover/small.jpg', $cover->getFilenameOnFilestore());
	}

	/**
	 * @group IconService
	 */
	public function testCanReplaceIconFile() {
		$callback = function($hook, $type, $icon, $params) {
			$size = elgg_extract('size', $params);
			$type = elgg_extract('type', $params);
			$entity = elgg_extract('entity', $params);
			if ($entity->getSubtype() == 'foo') {
				$icon->owner_guid = $entity->owner_guid;
				$icon->setFilename("foo/bar/$type/$size.jpg");
			}
			return $icon;
		};
		$this->hooks->registerHandler('entity:icon:file', 'object', $callback);
		$this->hooks->registerHandler('entity:cover:file', 'object', $callback);

		$service = $this->createService();

		$icon = $service->getIcon($this->entity, 'small');
		$this->assertEquals($this->owner_dir_path . 'foo/bar/icon/small.jpg', $icon->getFilenameOnFilestore());

		$cover = $service->getIcon($this->entity, 'large', 'cover');
		$this->assertEquals($this->owner_dir_path . 'foo/bar/cover/large.jpg', $cover->getFilenameOnFilestore());
	}

	/**
	 * @group IconService
	 * @expectedException \InvalidParameterException
	 */
	public function testThrowsExceptionOnInvalidHookHandlerReturnForIconFile() {
		$callback = function($hook, $type, $icon, $params) {
			return '/path/to/foo.jpg';
		};

		$this->hooks->registerHandler('entity:icon:file', 'object', $callback);

		$service = $this->createService();
		$service->getIcon($this->entity, 'small');
	}

	/**
	 * @group IconService
	 * @expectedException \InvalidParameterException
	 */
	public function testThrowsExceptionOnSaveIconFromNonExistentElggFile() {
		$service = $this->createService();
		$service->saveIconFromElggFile($this->entity, new \ElggFile());
	}

	/**
	 * @group IconService
	 */
	public function testCanSaveIconFromElggFile() {

		$service = $this->createService();

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('400x300.gif');
		$file->mimetype = 'image/gif';

		$service->saveIconFromElggFile($this->entity, $file);

		$this->assertTrue($service->hasIcon($this->entity, 'master'));
		$this->assertTrue($service->hasIcon($this->entity, 'large'));
		$this->assertTrue($service->hasIcon($this->entity, 'medium'));
		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$this->assertTrue($service->hasIcon($this->entity, 'tiny'));
		$this->assertTrue($service->hasIcon($this->entity, 'topbar'));
	}

	/**
	 * @group IconService
	 */
	public function testCanDeleteIcon() {

		$service = $this->createService();

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('400x300.gif');
		$file->mimetype = 'image/gif';

		$service->saveIconFromElggFile($this->entity, $file);

		$this->assertTrue($service->hasIcon($this->entity, 'master'));
		$this->assertTrue($service->hasIcon($this->entity, 'large'));
		$this->assertTrue($service->hasIcon($this->entity, 'medium'));
		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$this->assertTrue($service->hasIcon($this->entity, 'tiny'));
		$this->assertTrue($service->hasIcon($this->entity, 'topbar'));

		$service->deleteIcon($this->entity);

		$this->assertFalse($service->hasIcon($this->entity, 'master'));
		$this->assertFalse($service->hasIcon($this->entity, 'large'));
		$this->assertFalse($service->hasIcon($this->entity, 'medium'));
		$this->assertFalse($service->hasIcon($this->entity, 'small'));
		$this->assertFalse($service->hasIcon($this->entity, 'tiny'));
		$this->assertFalse($service->hasIcon($this->entity, 'topbar'));
	}

	/**
	 * @group IconService
	 * @expectedException \InvalidParameterException
	 */
	public function testThrowsExceptionIfLocalFileIsNotReadable() {
		$service = $this->createService();
		$local_file = _elgg_config()->dataroot . '_______empty';
		$service->saveIconFromLocalFile($this->entity, $local_file);
	}

	/**
	 * @group IconService
	 */
	public function testCanSaveIconFromLocalFile() {

		$service = $this->createService();

		$local_file = _elgg_config()->dataroot . '1/1/400x300.png';
		$service->saveIconFromLocalFile($this->entity, $local_file);

		$this->assertTrue($service->hasIcon($this->entity, 'master'));
		$this->assertTrue($service->hasIcon($this->entity, 'large'));
		$this->assertTrue($service->hasIcon($this->entity, 'medium'));
		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$this->assertTrue($service->hasIcon($this->entity, 'tiny'));
		$this->assertTrue($service->hasIcon($this->entity, 'topbar'));
	}

	/**
	 * @group IconService
	 */
	public function testCanSaveIconFromUploadedFile() {

		$service = $this->createService();
		
		// Make a copy of the file so we can move it
		$tmp = new \ElggFile();
		$tmp->owner_guid = $this->user->guid;
		$tmp->setFilename('tmp.gif');
		$tmp->open('write');
		$tmp->write(file_get_contents(_elgg_config()->dataroot . '1/1/400x300.gif'));
		$tmp->close();

		$uploaded_file = $tmp->getFilenameOnFilestore();

		$upload = new \Symfony\Component\HttpFoundation\File\UploadedFile($uploaded_file, 'tmp.gif', 'image/gif', filesize($uploaded_file), UPLOAD_ERR_OK, true);
		$this->request->files->set('icon', $upload);

		$service->saveIconFromUploadedFile($this->entity, 'icon');

		$this->assertTrue($service->hasIcon($this->entity, 'master'));
		$this->assertTrue($service->hasIcon($this->entity, 'large'));
		$this->assertTrue($service->hasIcon($this->entity, 'medium'));
		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$this->assertTrue($service->hasIcon($this->entity, 'tiny'));
		$this->assertTrue($service->hasIcon($this->entity, 'topbar'));
	}

	/**
	 * @group IconService
	 */
	public function testIconLastChangedTime() {

		$service = $this->createService();

		$local_file = _elgg_config()->dataroot . '1/1/400x300.png';
		$service->saveIconFromLocalFile($this->entity, $local_file);

		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$icon = $service->getIcon($this->entity, 'small');

		$this->assertEquals(filemtime($icon->getFilenameOnFilestore()), $service->getIconLastChange($this->entity, 'small'));

		sleep(1);
		$icon->setModifiedTime();
		$this->assertEquals(filemtime($icon->getFilenameOnFilestore()), $service->getIconLastChange($this->entity, 'small'));
	}

	/**
	 * @group IconService
	 */
	public function testCanResolveDefaultIconURL() {

		$service = $this->createService();

		$local_file = _elgg_config()->dataroot . '1/1/400x300.png';
		$service->saveIconFromLocalFile($this->entity, $local_file);

		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$icon = $service->getIcon($this->entity, 'small');

		$this->assertEquals(elgg_get_inline_url($icon), $service->getIconURL($this->entity, 'small'));
	}

	/**
	 * @group IconService
	 */
	public function testCanReplaceDefaultIconURL() {

		$this->hooks->registerHandler('entity:icon:url', 'object', function() {
			return '/path/to/icon.png';
		});

		$service = $this->createService();

		$local_file = _elgg_config()->dataroot . '1/1/400x300.png';
		$service->saveIconFromLocalFile($this->entity, $local_file);

		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$icon = $service->getIcon($this->entity, 'small');

		$this->assertEquals(elgg_normalize_url('/path/to/icon.png'), $service->getIconURL($this->entity, 'small'));
	}

	/**
	 * @group IconService
	 */
	public function testCanSaveIconWithCroppingCoordinates() {

		$service = $this->createService();

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('600x300.jpg');
		$file->mimetype = 'image/jpeg';

		$service->saveIconFromElggFile($this->entity, $file);

		// original file should stay where it is
		$this->assertTrue($file->exists());

		$this->assertTrue($service->hasIcon($this->entity, 'master'));
		$this->assertTrue($service->hasIcon($this->entity, 'large'));
		$this->assertTrue($service->hasIcon($this->entity, 'medium'));
		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$this->assertTrue($service->hasIcon($this->entity, 'tiny'));
		$this->assertTrue($service->hasIcon($this->entity, 'topbar'));

		$master = $service->getIcon($this->entity, 'master');
		
		// recrop from master with coordinates
		$service->saveIconFromElggFile($this->entity, $master, 'icon', [
			'x1' => 10,
			'y1' => 10,
			'x2' => 110,
			'y2' => 110,
		]);

		$this->assertTrue($service->hasIcon($this->entity, 'master'));
		$this->assertTrue($service->hasIcon($this->entity, 'large'));
		$this->assertTrue($service->hasIcon($this->entity, 'medium'));
		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$this->assertTrue($service->hasIcon($this->entity, 'tiny'));
		$this->assertTrue($service->hasIcon($this->entity, 'topbar'));
	}

	/**
	 * @group IconService
	 */
	function testEmptySizeConfigSavesUnmodifiedVersion() {
		$this->hooks->registerHandler('entity:cover:sizes', 'object', array($this, 'getCoverSizes'));

		$service = $this->createService();

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('600x300.jpg');
		$file->mimetype = 'image/jpeg';

		$service->saveIconFromElggFile($this->entity, $file, 'cover');

		// original file should stay where it is
		$this->assertTrue($file->exists());

		$this->assertTrue($service->hasIcon($this->entity, 'medium', 'cover'));
		$this->assertTrue($service->hasIcon($this->entity, 'original', 'cover'));
		$this->assertTrue($service->hasIcon($this->entity, 'master', 'cover'));

		$original_bytes = $service->getIcon($this->entity, 'original', 'cover')->grabFile();
		$source_bytes = $service->getIcon($this->entity, 'master', 'cover')->grabFile();
		
		// original should remain the same
		$this->assertEquals($source_bytes, $original_bytes);
		
		// crop with coordinates
		$service->saveIconFromElggFile($this->entity, $file, 'cover', [
			'x1' => 10,
			'y1' => 10,
			'x2' => 110,
			'y2' => 110,
		]);

		// source file should stay where it is
		$this->assertTrue($file->exists());

		$this->assertTrue($service->hasIcon($this->entity, 'medium', 'cover'));
		$this->assertTrue($service->hasIcon($this->entity, 'original', 'cover'));
		$this->assertTrue($service->hasIcon($this->entity, 'master', 'cover'));

		$original_bytes = $service->getIcon($this->entity, 'original', 'cover')->grabFile();
		$source_bytes = $service->getIcon($this->entity, 'master', 'cover')->grabFile();
		
		// original should remain the same
		$this->assertEquals($source_bytes, $original_bytes);
	}

	/**
	 * @group IconService
	 * @expectedException \LogicException
	 */
	public function testThrowsExceptionOnElggIconSave() {
		$service = $this->createService();
		$icon = $service->getIcon($this->entity, 'small');
		$icon->save();
	}

	/**
	 * @group IconService
	 * @todo test _elgg_filestore_touch_icons() does it's job
	 */
	public function testIconURLInvalidatedOnAccessIdChange() {
		$this->markTestIncomplete();
	}

	/**
	 * @group IconService
	 */
	public function testCanPrepareInputFile() {

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('600x300.jpg');

		$service = $this->createService();

		$service->saveIconFromElggFile($this->entity, $file);

		$this->assertTrue($service->hasIcon($this->entity, 'master'));

		$master = $service->getIcon($this->entity, 'master');
		$size = getimagesize($master->getFilenameOnFilestore());

		// original file should stay where it is
		$this->assertTrue($file->exists());

		// make sure we have a wide master
		$this->assertTrue($size[0] > $size[1]);

		$this->hooks->registerHandler('entity:icon:prepare', 'object', function($hook, $type, $file, $params) {
			// make sure we passed in documented params
			if (!$params['entity'] instanceof \ElggEntity || !$params['file'] instanceof \ElggFile || !$file instanceof \ElggFile) {
				return;
			}
			$new_source = new \ElggFile();
			$new_source->owner_guid = 1;
			$new_source->setFilename('300x600.jpg');

			// replace with tall image
			$file->owner_guid = $params['entity']->guid;
			$file->setFilename('tmp/tmp.jpg');
			$file->open('write');
			$file->close();

			copy($new_source->getFilenameOnFilestore(), $file->getFilenameOnFilestore());

			return $file;
		});

		$this->assertTrue($this->hooks->hasHandler('entity:icon:prepare', 'object'));

		$service = $this->createService();

		$service->saveIconFromElggFile($this->entity, $file);

		// original file should stay where it is
		$this->assertTrue($file->exists());

		$this->assertTrue($service->hasIcon($this->entity, 'master'));

		$master = $service->getIcon($this->entity, 'master');
		$size = getimagesize($master->getFilenameOnFilestore());

		// was the file repaced with a tall image?
		$this->assertTrue($size[0] < $size[1]);
	}

	/**
	 * @group IconService
	 */
	public function testCanSaveIconsWithAHookHandler() {

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('600x300.jpg');

		$this->hooks->registerHandler('entity:icon:save', 'object', function($hook, $type, $return, $params) {
			if ($return) {
				return;
			}

			// make sure we passed in documented params
			if (!$params['entity'] instanceof \ElggEntity || !$params['file'] instanceof \ElggFile) {
				return;
			}

			if (!isset($params['x1']) || !isset($params['y1']) || !isset($params['x2']) || !isset($params['y2'])) {
				return;
			}

			return true;
		});

		$this->assertTrue($this->hooks->hasHandler('entity:icon:save', 'object'));

		$service = $this->createService();

		$service->saveIconFromElggFile($this->entity, $file);

		// original file should stay where it is
		$this->assertTrue($file->exists());

		// hook returned true without generating icons
		$this->assertFalse($service->hasIcon($this->entity, 'master'));
		$this->assertFalse($service->hasIcon($this->entity, 'large'));
		$this->assertFalse($service->hasIcon($this->entity, 'medium'));
		$this->assertFalse($service->hasIcon($this->entity, 'small'));
		$this->assertFalse($service->hasIcon($this->entity, 'tiny'));
		$this->assertFalse($service->hasIcon($this->entity, 'topbar'));
	}

	/**
	 * @group IconService
	 */
	public function testCanDeleteIconsWithAHookHandler() {

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('600x300.jpg');

		$this->hooks->registerHandler('entity:icon:delete', 'object', function($hook, $type, $return, $params) {
			if ($return === false) {
				return;
			}

			// make sure we passed in documented params
			if (!$params['entity'] instanceof \ElggEntity) {
				return;
			}

			return false;
		});

		$this->assertTrue($this->hooks->hasHandler('entity:icon:delete', 'object'));

		$service = $this->createService();

		$service->saveIconFromElggFile($this->entity, $file);

		// original file should stay where it is
		$this->assertTrue($file->exists());

		// hook returned false without deleting icons
		$this->assertTrue($service->hasIcon($this->entity, 'master'));
		$this->assertTrue($service->hasIcon($this->entity, 'large'));
		$this->assertTrue($service->hasIcon($this->entity, 'medium'));
		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$this->assertTrue($service->hasIcon($this->entity, 'tiny'));
		$this->assertTrue($service->hasIcon($this->entity, 'topbar'));
	}

	/**
	 * @group IconService
	 */
	public function testCanListenToIconsSavedHook() {

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('600x300.jpg');

		$this->hooks->registerHandler('entity:icon:saved', 'object', function($hook, $type, $return, $params) {

			// make sure we passed in documented params
			if (!$params['entity'] instanceof \ElggEntity) {
				return;
			}

			if (!isset($params['x1']) || !isset($params['y1']) || !isset($params['x2']) || !isset($params['y2'])) {
				return;
			}

			_elgg_services()->iconService->deleteIcon($params['entity']);
		});

		$this->assertTrue($this->hooks->hasHandler('entity:icon:saved', 'object'));

		$service = $this->createService();
		_elgg_services()->setValue('iconService', $service);

		$service->saveIconFromElggFile($this->entity, $file);

		// icons were deleted by the hook
		$this->assertFalse($service->hasIcon($this->entity, 'master'));
		$this->assertFalse($service->hasIcon($this->entity, 'large'));
		$this->assertFalse($service->hasIcon($this->entity, 'medium'));
		$this->assertFalse($service->hasIcon($this->entity, 'small'));
		$this->assertFalse($service->hasIcon($this->entity, 'tiny'));
		$this->assertFalse($service->hasIcon($this->entity, 'topbar'));
	}

	/**
	 * @group IconService
	 * @dataProvider iconDimensionsProvider
	 */
	public function testIconDimensionsAfterResize($sw, $sh, $size, $ew, $eh, $crop, $cw = null, $ch = null) {

		$service = $this->createService();

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename("{$sw}x{$sh}.jpg");
		$file->mimetype = 'image/jpeg';

		if ($crop) {
			$coords = [
				'x1' => 5,
				'y1' => 5,
				'x2' => 25,
				'y2' => 25,
			];
		} else {
			$coords = [
				'x1' => 0,
				'y1' => 0,
				'x2' => 0,
				'y2' => 0,
			];
		}

		if (!$cw) {
			$cw = $ew;
		}
		if (!$ch) {
			$ch = $eh;
		}

		// resizing
		$service->saveIconFromElggFile($this->entity, $file, 'icon');

		$icon = $service->getIcon($this->entity, $size);
		$this->assertTrue($icon->exists());
		$image_size = getimagesize($icon->getFilenameOnFilestore());
		$this->assertEquals($ew, $image_size[0]);
		$this->assertEquals($eh, $image_size[1]);

		// cropping
		$master = $service->getIcon($this->entity, 'master');
		$service->saveIconFromElggFile($this->entity, $master, 'icon', $coords);

		$icon = $service->getIcon($this->entity, $size);
		$this->assertTrue($icon->exists());
		$image_size = getimagesize($icon->getFilenameOnFilestore());
		$this->assertEquals($cw, $image_size[0]);
		$this->assertEquals($ch, $image_size[1]);
	}

	public function iconDimensionsProvider() {
		return [
			// resize 600x300 source image
			[600, 300, 'master', 600, 300, false],
			[600, 300, 'large', 200, 200, false],
			[600, 300, 'medium', 100, 100, false],
			[600, 300, 'small', 40, 40, false],
			[600, 300, 'tiny', 25, 25, false],
			[600, 300, 'topbar', 16, 16, false],
			// resize 300x600 source image
			[300, 600, 'master', 300, 600, false],
			[300, 600, 'large', 200, 200, false],
			[300, 600, 'medium', 100, 100, false],
			[300, 600, 'small', 40, 40, false],
			[300, 600, 'tiny', 25, 25, false],
			[300, 600, 'topbar', 16, 16, false],
			// resize 300x300 source image
			[300, 300, 'master', 300, 300, false],
			[300, 300, 'large', 200, 200, false],
			[300, 300, 'medium', 100, 100, false],
			[300, 300, 'small', 40, 40, false],
			[300, 300, 'tiny', 25, 25, false],
			[300, 300, 'topbar', 16, 16, false],
			// resize 75x125 source image
			[75, 125, 'master', 75, 125, false],
			[75, 125, 'large', 200, 200, false],
			[75, 125, 'medium', 100, 100, false],
			[75, 125, 'small', 40, 40, false],
			[75, 125, 'tiny', 25, 25, false],
			[75, 125, 'topbar', 16, 16, false],
			// resize 75x125 source image with cropping
			[75, 125, 'master', 75, 125, true],
			[75, 125, 'medium', 100, 100, true],
			[75, 125, 'small', 40, 40, true],
			[75, 125, 'tiny', 25, 25, true],
			[75, 125, 'topbar', 16, 16, true],
			[75, 125, 'large', 200, 200, true, 200, 200],
		];
	}

	/**
	 * @group IconService
	 */
	public function testServeIconSends400ForMalformattedRequest() {

		$this->request = \Elgg\Http\Request::create("/serve-icon/x/large");

		$service = $this->createService();
		$service->setCurrentTime();

		$response = $service->handleServeIconRequest(false);

		$this->assertEquals(400, $response->getStatusCode());

		// We can't compare two DateTime objects here, because Symfony parses the date from string, where as our TimeUsing trait
		// uses DateTime constructor, which in 7.1 adds microseconds
		// http://php.net/manual/en/migration71.incompatible.php#migration71.incompatible.datetime-microseconds
		$this->assertEquals($service->getCurrentTime('-1 day')->format('U'), $response->getExpires()->format('U'));
	}

	/**
	 * @group IconService
	 */
	public function testServeIconSends404ForNonExistentGuid() {

		$this->request = \Elgg\Http\Request::create("/serve-icon/55/small");

		$service = $this->createService();
		$service->setCurrentTime();

		$response = $service->handleServeIconRequest(false);

		$this->assertEquals(404, $response->getStatusCode());

		// We can't compare two DateTime objects here, because Symfony parses the date from string, where as our TimeUsing trait
		// uses DateTime constructor, which in 7.1 adds microseconds
		// http://php.net/manual/en/migration71.incompatible.php#migration71.incompatible.datetime-microseconds
		$this->assertEquals($service->getCurrentTime('-1 day')->format('U'), $response->getExpires()->format('U'));
	}

	/**
	 * @group IconService
	 */
	public function testCanHandleServeIconRequest() {

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('600x300.jpg');

		$this->request = \Elgg\Http\Request::create("/serve-icon/{$this->entity->guid}/small");

		$service = $this->createService();
		$service->setCurrentTime();

		$service->saveIconFromElggFile($this->entity, $file);

		$response = $service->handleServeIconRequest(false);

		$icon = $service->getIcon($this->entity, 'small');

		$this->assertInstanceOf(\Symfony\Component\HttpFoundation\BinaryFileResponse::class, $response);
		$this->assertEquals(200, $response->getStatusCode());

		$this->assertEquals('image/jpeg', $response->headers->get('Content-Type'));

		$filesize = filesize($icon->getFilenameOnFilestore());
		$this->assertEquals($filesize, $response->headers->get('Content-Length'));

		$this->assertContains('inline', $response->headers->get('Content-Disposition'));

		$this->assertEquals('"' . $icon->getModifiedTime() . '"', $response->headers->get('Etag'));

		// We can't compare two DateTime objects he	re, because Symfony parses the date from string, where as our TimeUsing trait
		// uses DateTime constructor, which in 7.1 adds microseconds
		// http://php.net/manual/en/migration71.incompatible.php#migration71.incompatible.datetime-microseconds
		$this->assertEquals($service->getCurrentTime('+1 day')->format('U'), $response->getExpires()->format('U'));

		$this->assertEquals('max-age=86400, private', $response->headers->get('cache-control'));

		// now try conditional request

		$this->request->headers->set('if-none-match', '"' . $icon->getModifiedTime() . '"');
		$response = $service->handleServeIconRequest(false);

		$this->assertEquals(304, $response->getStatusCode());

		$this->assertEquals($service->getCurrentTime('+1 day')->format('U'), $response->getExpires()->format('U'));

		$this->assertEquals('max-age=86400, private', $response->headers->get('cache-control'));
	}
	
	/**
	 * @group IconService
	 */
	public function testDelayedCreationOfIcon() {
		
		$service = $this->createService();

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename('400x300.gif');
		$file->mimetype = 'image/gif';

		$service->saveIconFromElggFile($this->entity, $file);
		
		$master = $service->getIcon($this->entity, 'master');
		$this->assertTrue($master->exists());
		
		// fetch 'medium' icon without auto generating it
		$medium = $service->getIcon($this->entity, 'medium', 'icon', false);
		
		$this->assertFalse($medium->exists());
		
		// now generate 'medium' icon
		$medium = $service->getIcon($this->entity, 'medium');
		
		$this->assertTrue($medium->exists());
	}

}
