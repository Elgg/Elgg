<?php

namespace Elgg;

use Exception;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Imagine\Filter\Basic\Autorotate;
use Elgg\Filesystem\MimeTypeDetector;

/**
 * Image manipulation service
 *
 * @since 2.3
 * @access private
 */
class ImageService {
	use Loggable;

	const JPEG_QUALITY = 75;

	/**
	 * @var ImagineInterface
	 */
	private $imagine;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * Constructor
	 *
	 * @param ImagineInterface $imagine Imagine interface
	 * @param Config           $config  Elgg config
	 */
	public function __construct(ImagineInterface $imagine, Config $config) {
		$this->imagine = $imagine;
		$this->config = $config;
	}

	/**
	 * Crop and resize an image
	 *
	 * @param string $source      Path to source image
	 * @param string $destination Path to destination
	 *                            If not set, will modify the source image
	 * @param array  $params      An array of cropping/resizing parameters
	 *                             - INT 'w' represents the width of the new image
	 *                               With upscaling disabled, this is the maximum width
	 *                               of the new image (in case the source image is
	 *                               smaller than the expected width)
	 *                             - INT 'h' represents the height of the new image
	 *                               With upscaling disabled, this is the maximum height
	 *                             - INT 'x1', 'y1', 'x2', 'y2' represent optional cropping
	 *                               coordinates. The source image will first be cropped
	 *                               to these coordinates, and then resized to match
	 *                               width/height parameters
	 *                             - BOOL 'square' - square images will fill the
	 *                               bounding box (width x height). In Imagine's terms,
	 *                               this equates to OUTBOUND mode
	 *                             - BOOL 'upscale' - if enabled, smaller images
	 *                               will be upscaled to fit the bounding box.
	 * @return bool
	 */
	public function resize($source, $destination = null, array $params = []) {

		if (!isset($destination)) {
			$destination = $source;
		}

		try {
			$image = $this->imagine->open($source);

			$width = $image->getSize()->getWidth();
			$height = $image->getSize()->getHeight();

			$resize_params = $this->normalizeResizeParameters($width, $height, $params);

			$max_width = elgg_extract('w', $resize_params);
			$max_height = elgg_extract('h', $resize_params);

			$x1 = (int) elgg_extract('x1', $resize_params, 0);
			$y1 = (int) elgg_extract('y1', $resize_params, 0);
			$x2 = (int) elgg_extract('x2', $resize_params, 0);
			$y2 = (int) elgg_extract('y2', $resize_params, 0);

			if ($x2 > $x1 && $y2 > $y1) {
				$crop_start = new Point($x1, $y1);
				$crop_size = new Box($x2 - $x1, $y2 - $y1);
				$image->crop($crop_start, $crop_size);
			}

			$target_size = new Box($max_width, $max_height);
			$thumbnail = $image->resize($target_size);

			$thumbnail->save($destination, [
				'jpeg_quality' => elgg_extract('jpeg_quality', $params, self::JPEG_QUALITY),
				'format' => $this->getFileFormat($source, $params),
			]);

			unset($image);
			unset($thumbnail);
		} catch (Exception $ex) {
			$logger = $this->logger ? $this->logger : _elgg_services()->logger;
			$logger->error($ex);
			return false;
		}

		return true;
	}
	
	/**
	 * If needed the image will be rotated based on orientation information
	 *
	 * @param string $filename Path to image
	 *
	 * @return bool
	 */
	function fixOrientation($filename) {
		try {
			$image = $this->imagine->open($filename);
			$metadata = $image->metadata();
			if (!isset($metadata['ifd0.Orientation'])) {
				// no need to perform an orientation fix
				return true;
			}
			
			$autorotate = new Autorotate();
			$autorotate->apply($image)->save($filename);
			
			$image->strip()->save($filename);
			
			return true;
		} catch (Exception $ex) {
			$logger = $this->logger ? $this->logger : _elgg_services()->logger;
			$logger->notice($ex);
		}
		return false;
	}

	/**
	 * Calculate the parameters for resizing an image
	 *
	 * @param int   $width  Natural width of the image
	 * @param int   $height Natural height of the image
	 * @param array $params Resize parameters
	 *                      - 'w' maximum width of the resized image
	 *                      - 'h' maximum height of the resized image
	 *                      - 'upscale' allow upscaling
	 *                      - 'square' constrain to a square
	 *                      - 'x1', 'y1', 'x2', 'y2' cropping coordinates
	 *
	 * @return array
	 * @throws \LogicException
	 */
	public function normalizeResizeParameters($width, $height, array $params = []) {

		$max_width = (int) elgg_extract('w', $params, 100, false);
		$max_height = (int) elgg_extract('h', $params, 100, false);
		if (!$max_height || !$max_width) {
			throw new \LogicException("Resize width and height parameters are required");
		}

		$square = elgg_extract('square', $params, false);
		$upscale = elgg_extract('upscale', $params, false);

		$x1 = (int) elgg_extract('x1', $params, 0);
		$y1 = (int) elgg_extract('y1', $params, 0);
		$x2 = (int) elgg_extract('x2', $params, 0);
		$y2 = (int) elgg_extract('y2', $params, 0);

		$cropping_mode = $x1 || $y1 || $x2 || $y2;

		if ($cropping_mode) {
			$crop_width = $x2 - $x1;
			$crop_height = $y2 - $y1;
			if ($crop_width <= 0 || $crop_height <= 0 || $crop_width > $width || $crop_height > $height) {
				throw new \LogicException("Coordinates [$x1, $y1], [$x2, $y2] are invalid for image cropping");
			}
		} else {
			// everything selected if no crop parameters
			$crop_width = $width;
			$crop_height = $height;
		}

		// determine cropping offsets
		if ($square) {
			// asking for a square image back
			
			// size of the new square image
			$max_width = $max_height = min($max_width, $max_height);

			// find largest square that fits within the selected region
			$crop_width = $crop_height = min($crop_width, $crop_height);

			if (!$cropping_mode) {
				// place square region in the center
				$x1 = floor(($width - $crop_width) / 2);
				$y1 = floor(($height - $crop_height) / 2);
			}
		} else {
			// maintain aspect ratio of original image/crop
			if ($crop_height / $max_height > $crop_width / $max_width) {
				$max_width = floor($max_height * $crop_width / $crop_height);
			} else {
				$max_height = floor($max_width * $crop_height / $crop_width);
			}
		}

		if (!$upscale && ($crop_height < $max_height || $crop_width < $max_width)) {
			// we cannot upscale and selected area is too small so we decrease size of returned image
			$max_height = $crop_height;
			$max_width = $crop_width;
		}

		return [
			'w' => $max_width,
			'h' => $max_height,
			'x1' => $x1,
			'y1' => $y1,
			'x2' => $x1 + $crop_width,
			'y2' => $y1 + $crop_height,
			'square' => $square,
			'upscale' => $upscale,
		];
	}

	/**
	 * Determine the image file format, this is needed for correct resizing
	 *
	 * @param string $filename path to the file
	 * @param array  $params   array of resizing params (can contain 'format' to set save format)
	 *
	 * @see https://github.com/Elgg/Elgg/issues/10686
	 * @return void|string
	 */
	protected function getFileFormat($filename, $params) {
		
		$accepted_formats = [
			'image/jpeg' => 'jpeg',
			'image/pjpeg' => 'jpeg',
			'image/png' => 'png',
			'image/x-png' => 'png',
			'image/gif' => 'gif',
			'image/vnd.wap.wbmp' => 'wbmp',
			'image/x‑xbitmap' => 'xbm',
			'image/x‑xbm' => 'xbm',
		];
		
		// was a valid output format supplied
		$format = elgg_extract('format', $params);
		if (in_array($format, $accepted_formats)) {
			return $format;
		}
		
		$mime_detector = new MimeTypeDetector();
		$mime = $mime_detector->getType($filename);
		
		return elgg_extract($mime, $accepted_formats);
	}
}
