<?php
	/**
	 * Elgg tags
	 * Functions for managing tags and tag clouds.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	
	/** 
	 * The algorithm working out the size of font based on the number of tags.
	 * This is quick and dirty.
	 */
	function calculate_tag_size($min, $max, $number_of_tags)
	{
		$buckets = 6;

		$delta =  (($max - $min) / $buckets);
		$thresholds = array();

		for ($n=1; $n <= $buckets; $n++) {
			$thresholds[$n-1] = ($min + $n) * $delta;
		}
	
		// Correction
		if ($thresholds[$buckets-1]>$max) $thresholds[$buckets-1] = $max;

		$size = 0;
		for ($n = 0; $n < count($thresholds); $n++) {
			if ($number_of_tags >= $thresholds[$n]) 
				$size = $n;
		}

		return $size;
	}
	
	/**
	 * This function generates an array of tags with a weighting.
	 *
	 * @param array $tags The array of tags.
	 * @return An associated array of tags with a weighting, this can then be mapped to a display class. 
	 */
	function generate_tag_cloud(array $tags)
	{
		$cloud = array();
		
		$min = 65535;
		$max = 0;
		
		foreach ($tags as $tag)
		{
			$cloud[$tag]++;
			
			if ($cloud[$tag]>$max) $max = $cloud[$tag];
			if ($cloud[$tag]<$min) $min = $cloud[$tag];
		}
		
		foreach ($cloud as $k => $v)
			$cloud[$k] = calculate_tag_size($min, $max, $v);
		
		return $cloud;
	}
?>