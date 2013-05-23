<?php
/**
 * CSS grid
 *
 * @package Elgg.Core
 * @subpackage UI
 * 
 * To work around subpixel rounding discrepancies, apply .elgg-col-last to
 * the last column (@todo we need broswer-specific test cases for this).
 */

$gutter_width_percent = elgg_get_config('elgg_grid_gutter_percentage');
if ($gutter_width_percent === null) {
	$gutter_width_percent = 1.6; /* 16px on a 1000px grid */
}

?>

/* ***************************************
	Grid
*************************************** */

/*
Gutters do not work with IE8 and before without manually adding .elgg-col-last 
to the last column in each row.
*/

/*<style>/**/

.elgg-col {
	float: left;
}

.elgg-col-alt {
	float: right;
}

.elgg-grid-gutters > .elgg-col {
	margin-right: <?php echo $gutter_width_percent; ?>%;
}

.elgg-grid-gutters > .elgg-col-alt {
	margin-left: <?php echo $gutter_width_percent; ?>%;
}

.elgg-grid-gutters {
	margin-top: <?php echo $gutter_width_percent; ?>%;
	margin-bottom: <?php echo $gutter_width_percent; ?>%;
}

.elgg-grid-gutters > .elgg-col:last-child,
.elgg-grid-gutters > .elgg-col-alt:last-child,
.elgg-grid-gutters > .elgg-col-last {
	float: none;
	overflow: hidden;
	margin: 0;
	width: auto;
}

.elgg-col-1of1 {
	float: none;
}
.elgg-grid-gutters > .elgg-col-1of1 {
	margin: 0;
}

<?php

// build units

// keep map to eliminate duplicates. keys are rounded to thousands (avoid float issues)
$percentages = array(
	'100' => array(1, 1),
);

for ($den = 2; $den <= 6; $den++) {
	$num_gutters = $den - 1;
	$column_width_percent = 100 / $den;
	$column_width_percent_gutters = (100 - $num_gutters * $gutter_width_percent) / $den;
	for ($num = 1; $num < $den; $num++) {
		// avoid duplicates
		$rounded_percentage = (string) round($num / $den, 3);
		if ($num > 1 && isset($percentages[$rounded_percentage])) {
			continue;
		}
		$percentages[$rounded_percentage] = array($num, $den);

		$width_percent = $num * $column_width_percent;
		$width_percent_gutters = $num * $column_width_percent_gutters + ($num - 1) * $gutter_width_percent;

		// round to 3 digits, but round last digit down (.666 instead of .667)
		$width_percent = floor($width_percent * 10000) / 10000;
		$width_percent_gutters = floor($width_percent_gutters * 10000) / 10000;

		echo <<<CSS
.elgg-col-{$num}of{$den} {
	width: $width_percent%;
}
.elgg-grid-gutters > .elgg-col-{$num}of{$den} {
	width: $width_percent_gutters%;
}

CSS;
	}
}

echo "\n";

