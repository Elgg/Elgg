<?php

namespace Elgg;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Exceptions\LogicException;
use Elgg\Exceptions\RangeException;
use Elgg\Filesystem\MimeTypeService;
use Elgg\Traits\Loggable;
use Imagine\Filter\Basic\Autorotate;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;

/**
 * Image manipulation service
 *
 * @since 2.3
 * @internal
 */
class ImageService {
	
	use Loggable;

	const JPEG_QUALITY = 75;
	const WEBP_QUALITY = 75;

	/**
	 * @var ImagineInterface
	 */
	protected $imagine;

	/**
	 * Constructor
	 *
	 * @param Config          $config   Elgg config
	 * @param MimeTypeService $mimetype MimeType service
	 */
	public function __construct(protected Config $config, protected MimeTypeService $mimetype) {
		
		switch ($config->image_processor) {
			case 'imagick':
				if (extension_loaded('imagick')) {
					$this->imagine = new \Imagine\Imagick\Imagine();
					break;
				}
				
				// fallback to GD if Imagick is not loaded
			default:
				// default use GD
				$this->imagine = new \Imagine\Gd\Imagine();
				break;
		}
	}

	/**
	 * Crop and resize an image
	 *
	 * @param string      $source      Path to source image
	 * @param null|string $destination Path to destination
	 *                                 If not set, will modify the source image
	 * @param array       $params      An array of cropping/resizing parameters
	 *                                 - INT 'w' represents the width of the new image
	 *                                 With upscaling disabled, this is the maximum width
	 *                                 of the new image (in case the source image is
	 *                                 smaller than the expected width)
	 *
	 *                                 - INT 'h' represents the height of the new image
	 *                                 With upscaling disabled, this is the maximum height
	 *
	 *                                 - INT 'x1', 'y1', 'x2', 'y2' represent optional cropping
	 *                                 coordinates. The source image will first be cropped
	 *                                 to these coordinates, and then resized to match
	 *                                 width/height parameters
	 *
	 *                                 - BOOL 'square' - square images will fill the
	 *                                 bounding box (width x height). In Imagine's terms,
	 *                                 this equates to OUTBOUND mode
	 *
	 *                                 - BOOL 'upscale' - if enabled, smaller images
	 *                                 will be upscaled to fit the bounding box.
	 * @return bool
	 */
	public function resize(string $source, ?string $destination = null, array $params = []): bool {

		$destination = $destination ?? $source;

		try {
			$resize_params = $this->normalizeResizeParameters($source, $params);
			
			$image = $this->imagine->open($source);
			
			$max_width = (int) elgg_extract('w', $resize_params);
			$max_height = (int) elgg_extract('h', $resize_params);

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
			$image->resize($target_size);
			
			// create new canvas with a background (default: white)
			$background_color = elgg_extract('background_color', $params, 'ffffff');
			$thumbnail = $this->imagine->create($image->getSize(), $image->palette()->color($background_color));
			$thumbnail->paste($image, new Point(0, 0));

			if (pathinfo($destination, PATHINFO_EXTENSION) === 'webp') {
				$options = [
					'webp_quality' => elgg_extract('webp_quality', $params, self::WEBP_QUALITY),
				];
			} else {
				$options = [
					'format' => $this->getFileFormat($source, $params),
					'jpeg_quality' => elgg_extract('jpeg_quality', $params, self::JPEG_QUALITY),
				];
			}
			
			$thumbnail->save($destination, $options);

			unset($image);
			unset($thumbnail);
		} catch (\Exception $ex) {
			$this->getLogger()->error($ex);

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
	public function fixOrientation($filename) {
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
		} catch (\Exception $ex) {
			$this->getLogger()->notice($ex);
		}
		
		return false;
	}

	/**
	 * Calculate the parameters for resizing an image
	 *
	 * @param string $source The source location of the image to validate the parameters for
	 * @param array  $params Resize parameters
	 *                       - 'w' maximum width of the resized image
	 *                       - 'h' maximum height of the resized image
	 *                       - 'upscale' allow upscaling
	 *                       - 'square' constrain to a square
	 *                       - 'x1', 'y1', 'x2', 'y2' cropping coordinates
	 *
	 * @return array
	 * @throws InvalidArgumentException
	 * @throws RangeException
	 */
	public function normalizeResizeParameters(string $source, array $params = []): array {

		$image = $this->imagine->open($source);

		$width = $image->getSize()->getWidth();
		$height = $image->getSize()->getHeight();

		$max_width = (int) elgg_extract('w', $params, 100, false);
		$max_height = (int) elgg_extract('h', $params, 100, false);
		if (!$max_height || !$max_width) {
			throw new InvalidArgumentException('Resize width and height parameters are required');
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
				throw new RangeException("Coordinates [$x1, $y1], [$x2, $y2] are invalid for image cropping");
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
			$max_width = min($max_width, $max_height);
			$max_height = $max_width;
			
			// find the largest square that fits within the selected region
			$crop_width = min($crop_width, $crop_height);
			$crop_height = $crop_width;
			
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
		
		try {
			return elgg_extract($this->mimetype->getMimeType($filename), $accepted_formats);
		} catch (InvalidArgumentException $e) {
			$this->getLogger()->warning($e);
		}
	}
	
	/**
	 * Checks if imagine has WebP support
	 *
	 * @return bool
	 */
	public function hasWebPSupport(): bool {
		if ($this->config->webp_enabled === false) {
			return false;
		}
		
		if ($this->imagine instanceof \Imagine\Imagick\Imagine) {
			return !empty(\Imagick::queryformats('WEBP*'));
		} elseif ($this->imagine instanceof \Imagine\Gd\Imagine) {
			return (bool) elgg_extract('WebP Support', gd_info(), false);
		}
		
		return false;
	}
}
