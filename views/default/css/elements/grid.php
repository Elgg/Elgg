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

$gutterWidthPercent = 1.6; /* 16px on a 1000px grid */
?>

.elgg-grid + .elgg-grid { margin-top: <?php echo $gutterWidthPercent; ?>%; }
.elgg-col { float: left; margin-right: <?php echo $gutterWidthPercent; ?>%; }
.elgg-col-alt { float: right; margin-left: <?php echo $gutterWidthPercent; ?>%; margin-right: 0; }
.elgg-col-1of1 { float: none; margin: 0; }
.elgg-col:last-child, .elgg-col-last { float: none; overflow: hidden; margin: 0; width: auto; }

<?php

for ($i = 2; $i <= 6; $i++) {
	$gutters = $i - 1;
	$columnWidthPercent = (100 - $gutters * $gutterWidthPercent)/$i;
	for ($j = 1; $j < $i; $j++) {
		if ($j > 1 && ($i/$j) === (int)($i/$j)) {
			continue;
		}

		$widthPercent = $j * $columnWidthPercent + ($j - 1) * $gutterWidthPercent;
		echo ".elgg-col-{$j}of{$i} { width: $widthPercent%; }\n";
	}
}