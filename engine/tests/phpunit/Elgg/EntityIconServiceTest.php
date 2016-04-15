<?php

namespace Elgg;

class EntityIconServiceTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \Elgg\Config
	 */
	private $config;

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
	 * @var \ElggEntity
	 */
	private $entity;

	/**
	 * @var string
	 */
	private $entity_dir_path;

	/**
	 * @var string
	 */
	private $owner_dir_path;

	public function setUp() {

		_elgg_filestore_init(); // we will need simpletype hook to work

		$this->config = _elgg_testing_config();
		$this->hooks = new \Elgg\PluginHooksService();
		$path_key = \Elgg\Application::GET_PATH_KEY;
		$this->request = \Elgg\Http\Request::create("?$path_key=action/upload");
		$this->logger = new \Elgg\Logger($this->hooks, $this->config, new \Elgg\Context());

		$dbMock = $this->getMockBuilder('\Elgg\Database')
				->disableOriginalConstructor()
				->getMock();

		$this->entity = $this->getMockBuilder('\ElggEntity')
				->disableOriginalConstructor()
				->getMock();
		$this->entity->expects($this->any())
				->method('getType')
				->will($this->returnValue('object'));
		$this->entity->expects($this->any())
				->method('getSubtype')
				->will($this->returnValue('foo'));
		$this->entity->expects($this->any())
				->method('__get')
				->will($this->returnValueMap([
							['guid', 123],
							['owner_guid', 2],
		]));

		$dir = (new \Elgg\EntityDirLocator($this->entity->guid))->getPath();
		$this->entity_dir_path = $this->config->get('dataroot') . $dir;
		if (is_dir($this->entity_dir_path)) {
			_elgg_rmdir($this->entity_dir_path);
		}

		$dir = (new \Elgg\EntityDirLocator($this->entity->owner_guid))->getPath();
		$this->owner_dir_path = $this->config->get('dataroot') . $dir;
		if (is_dir($this->owner_dir_path)) {
			_elgg_rmdir($this->owner_dir_path);
		}
		// Needed to test elgg_get_inline_url()
		$session = \ElggSession::getMock();
		_elgg_services()->setValue('session', $session);
		_elgg_services()->session->start();
	}

	public function tearDown() {
		$this->assertTrue(file_exists($this->config->get('dataroot') . '1/1/75x125.jpg'));
		$this->assertTrue(file_exists($this->config->get('dataroot') . '1/1/300x300.jpg'));
		$this->assertTrue(file_exists($this->config->get('dataroot') . '1/1/600x300.jpg'));
		$this->assertTrue(file_exists($this->config->get('dataroot') . '1/1/300x600.jpg'));
		$this->assertTrue(file_exists($this->config->get('dataroot') . '1/1/400x300.gif'));
		$this->assertTrue(file_exists($this->config->get('dataroot') . '1/1/400x300.png'));
		if (is_dir($this->entity_dir_path)) {
			_elgg_rmdir($this->entity_dir_path);
		}

		if (is_dir($this->owner_dir_path)) {
			_elgg_rmdir($this->owner_dir_path);
		}
	}

	protected function createService() {
		return new \Elgg\EntityIconService($this->config, $this->hooks, $this->request, $this->logger);
	}

	public static function getCoverSizes() {
		return [
			'medium' => [
				'w' => 1280,
				'h' => 720,
				'square' => false,
			]
		];
	}

	public static function getIconSizesForSubtype($hook, $type, $sizes, $params) {
		$subtype = elgg_extract('entity_subtype', $params);
		$icon_type = elgg_extract('type', $params);
		if ($type == 'object' && $subtype == 'foo' && $icon_type == 'icon') {
			return self::getTestSizes();
		}
	}

	public static function getTestSizes() {
		return [
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
	}

	/**
	 * @group IconService
	 */
	public function testGetDefaultSizes() {
		$service = $this->createService();

		// Should return config values, as we do not have any registered hook
		$this->assertEquals($this->config->get('icon_sizes'), $service->getSizes());

		// If type is not 'icon', should return an empty array
		$this->assertEmpty($service->getSizes(null, null, 'foo'));
	}

	/**
	 * @group IconService
	 */
	public function testCanSetSizesForCustomIconType() {
		$service = $this->createService();
		$this->assertEmpty($service->getSizes('object', 'foo', 'cover'));

		$this->hooks->registerHandler('entity:cover:sizes', 'object', array($this, 'getCoverSizes'));
		$service = $this->createService();
		$this->assertNotEmpty($service->getSizes('object', 'foo', 'cover'));
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

		// make sure we removed temporary files
		$dir_items = scandir($this->entity_dir_path . 'tmp');
		$this->assertTrue(count($dir_items) <= 2);
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
	 */
	public function testCanCleanUpOnFailure() {

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

		// This will fail for square icons because cropping coordinates are not square
		$service->saveIconFromElggFile($this->entity, $file, 'icon', [
			'x1' => 0,
			'y1' => 0,
			'x2' => 10,
			'y2' => 20,
		]);

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
		$local_file = $this->config->get('dataroot') . '_______empty';
		$service->saveIconFromLocalFile($this->entity, $local_file);
	}

	/**
	 * @group IconService
	 */
	public function testCanSaveIconFromLocalFile() {

		$service = $this->createService();

		$local_file = $this->config->get('dataroot') . '1/1/400x300.png';
		$service->saveIconFromLocalFile($this->entity, $local_file);

		$this->assertTrue($service->hasIcon($this->entity, 'master'));
		$this->assertTrue($service->hasIcon($this->entity, 'large'));
		$this->assertTrue($service->hasIcon($this->entity, 'medium'));
		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$this->assertTrue($service->hasIcon($this->entity, 'tiny'));
		$this->assertTrue($service->hasIcon($this->entity, 'topbar'));

		// make sure we removed temporary files
		$dir_items = scandir($this->entity_dir_path . 'tmp');
		$this->assertTrue(count($dir_items) <= 2);
	}

	/**
	 * @group IconService
	 */
	public function testCanSaveIconFromUploadedFile() {

		// Make a copy of the file so we can move it
		$tmp = new \ElggFile();
		$tmp->owner_guid = 2;
		$tmp->setFilename('tmp.gif');
		$tmp->open('write');
		$tmp->write(file_get_contents($this->config->get('dataroot') . '1/1/400x300.gif'));
		$tmp->close();

		$uploaded_file = $tmp->getFilenameOnFilestore();

		$upload = new \Symfony\Component\HttpFoundation\File\UploadedFile($uploaded_file, 'tmp.gif', 'image/gif', filesize($uploaded_file), UPLOAD_ERR_OK, true);
		$this->request->files->set('icon', $upload);

		$service = $this->createService();

		$service->saveIconFromUploadedFile($this->entity, 'icon');

		$this->assertTrue($service->hasIcon($this->entity, 'master'));
		$this->assertTrue($service->hasIcon($this->entity, 'large'));
		$this->assertTrue($service->hasIcon($this->entity, 'medium'));
		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$this->assertTrue($service->hasIcon($this->entity, 'tiny'));
		$this->assertTrue($service->hasIcon($this->entity, 'topbar'));

		// make sure uploaded file is deleted
		$this->assertFalse(file_exists($upload->getPathname()));

		// make sure we removed temporary files
		$dir_items = scandir($this->entity_dir_path . 'tmp');
		$this->assertTrue(count($dir_items) <= 2);
	}

	/**
	 * @group IconService
	 */
	public function testIconLastChangedTime() {

		$service = $this->createService();

		$local_file = $this->config->get('dataroot') . '1/1/400x300.png';
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

		$local_file = $this->config->get('dataroot') . '1/1/400x300.png';
		$service->saveIconFromLocalFile($this->entity, $local_file);

		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$icon = $service->getIcon($this->entity, 'small');

		$this->assertEquals(elgg_get_inline_url($icon, true), $service->getIconURL($this->entity, 'small'));
	}

	/**
	 * @group IconService
	 */
	public function testCanReplaceDefaultIconURL() {

		$this->hooks->registerHandler('entity:icon:url', 'object', function() {
			return '/path/to/icon.png';
		});

		$service = $this->createService();

		$local_file = $this->config->get('dataroot') . '1/1/400x300.png';
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
		$master_bytes = file_get_contents($master->getFilenameOnFilestore());

		$medium = $service->getIcon($this->entity, 'medium');
		$medium_bytes = file_get_contents($medium->getFilenameOnFilestore());

		// recrop from master with coordinates
		$service->saveIconFromElggFile($this->entity, $master, 'icon', [
			'x1' => 10,
			'y1' => 10,
			'x2' => 110,
			'y2' => 110,
		]);

		// original file should stay where it is
		$this->assertTrue($file->exists());

		$this->assertTrue($service->hasIcon($this->entity, 'master'));
		$this->assertTrue($service->hasIcon($this->entity, 'large'));
		$this->assertTrue($service->hasIcon($this->entity, 'medium'));
		$this->assertTrue($service->hasIcon($this->entity, 'small'));
		$this->assertTrue($service->hasIcon($this->entity, 'tiny'));
		$this->assertTrue($service->hasIcon($this->entity, 'topbar'));

		// master should remain the same
		$this->assertEquals($master_bytes, file_get_contents($service->getIcon($this->entity, 'master')->getFilenameOnFilestore()));

		// medium should have been cropped
		$this->assertNotEquals($medium_bytes, file_get_contents($service->getIcon($this->entity, 'medium')->getFilenameOnFilestore()));
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
			[600, 300, 'master', 550, 275, false],
			[600, 300, 'large', 200, 100, false],
			[600, 300, 'medium', 100, 100, false],
			[600, 300, 'small', 40, 40, false],
			[600, 300, 'tiny', 25, 25, false],
			[600, 300, 'topbar', 16, 16, false],
			// resize 300x600 source image
			[300, 600, 'master', 275, 550, false],
			[300, 600, 'large', 100, 200, false],
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
			[75, 125, 'large', 75, 125, false],
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

			// there is a problem in get_resized_image_from_existing_file()
			// we expect the large icon to fill the container when in cropping mode
			// however since the icon is set to not upscale, we end up with a 20x20 image
			// See #9663
			//[75, 125, 'large', 75, 125, true, 200, 200],
		];
	}

}
