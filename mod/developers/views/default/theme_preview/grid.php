<?php
/**
 * Grid CSS
 */

$filler = "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>\n";

// build list of units (denominators are keys, values are arrays of nominators)
$units = array(1 => array(1),);

// keep map to avoid duplicates. keys are rounded to thousands (avoid float issues)
$percentages = array(
	'100' => array(1, 1),
);

for ($den = 2; $den <= 6; $den++) {
	for ($num = 1; $num < $den; $num++) {
		// avoid duplicates
		$rounded_percentage = (string)round($num / $den, 3);
		if ($num > 1 && isset($percentages[$rounded_percentage])) {
			continue;
		}
		$percentages[$rounded_percentage] = array($num, $den);
		$units[$den][] = $num;
	}
}

// build rows
$rows = array();
$total = 0;
for ($den = 1; $den <= count($units); $den++) {
	// may take multiple rows to use up available units
	while ($units[$den]) {
		$row = array();
		$nom = array_shift($units[$den]);
		$row[] = "$nom/$den";
		$total += $nom;
		if ($total < $den) {
			$nom = $den - $total;
			$row[] = "$nom/$den";
			$total += $nom;
			$index = array_search($nom, $units[$den]);
			if ($index !== false) {
				unset($units[$den][$index]);
			}
		}
		$rows[] = $row;
		$total = 0;
	}
}

?>
<style>
h3 { text-align: center; font-weight: normal; }
.elgg-griddemo { margin: 1em 0 0; font-size: 115%; }
.elgg-col > .elgg-inner,
.elgg-col-alt > .elgg-inner {
	border: 1px solid #cccccc;
	border-radius: 5px;
	padding: 5px;
	text-align: center;
	font-size: .65rem;
}
.elgg-col p {
	text-align: left;
	font-size: .80rem;
}
</style>

<h2 class="elgg-griddemo">Without Gutters</h2>
<p>Each row is wrapped by <code>div.elgg-grid</code></p>

<?php foreach ($rows as $row): ?>
<div class="elgg-grid">
	<?php foreach ($row as $col):
		$class = "elgg-col elgg-col-" . str_replace('/', 'of', $col);
		$text = str_replace(' ', '<br/>', $class);
	?>
	<div class="<?php echo $class ?>"><div class="elgg-inner"><?php echo $text ?></div></div>
	<?php endforeach; ?>
</div>
<?php endforeach; ?>

<!--
<h2 class="elgg-griddemo">With Gutters</h2>
<p>Each row is wrapped by <code>div.elgg-grid-gutters</code>
This does not work with IE8 and before without manually adding .elgg-col-last to the last column in a row.</p>

<?php foreach ($rows as $row): ?>
<div class="elgg-grid-gutters">
	<?php foreach ($row as $col):
		$class = "elgg-col elgg-col-" . str_replace('/', 'of', $col);
		$text = str_replace(' ', '<br/>', $class);
	?>
	<div class="<?php echo $class ?>"><div class="elgg-inner"><?php echo $text ?></div></div>
	<?php endforeach; ?>
</div>
<?php endforeach; ?>
-->

<h2 class="elgg-griddemo">Without Gutters and With Content</h2>
<p>Each row is wrapped by <code>div.elgg-grid</code></p>

<div class="elgg-grid">
	<div class="elgg-col elgg-col-1of5">
		<div class="elgg-inner">
			<h3>1/5</h3>
			<?php echo str_repeat($filler, 3) ?>
		</div>
	</div>
	<div class="elgg-col elgg-col-3of5">
		<div class="elgg-inner clearfix">
			<h3>3/5</h3>
			<div class="elgg-grid">
				<div class="elgg-col elgg-col-1of2">
					<div class="elgg-inner">
						<h3>1/2</h3>
						<?php echo $filler ?>
					</div>
				</div>
				<div class="elgg-col elgg-col-1of2 elgg-col-last">
					<div class="elgg-inner">
						<h3>1/2</h3>
						<?php echo $filler ?>
					</div>
				</div>
			</div>
			<div class="elgg-grid">
				<div class="elgg-col elgg-col-1of3">
					<div class="elgg-inner">
						<h3>1/3</h3>
						<?php echo str_repeat($filler, 2) ?>
					</div>
				</div>
				<div class="elgg-col elgg-col-2of3 elgg-col-last">
					<div class="elgg-inner">
						<h3>2/3</h3>
						<div class="elgg-grid">
							<div class="elgg-col elgg-col-1of2">
								<div class="elgg-inner">
									<h3>1/2</h3>
									<?php echo $filler ?>
								</div>
							</div>
							<div class="elgg-col elgg-col-1of2 elgg-col-last">
								<div class="elgg-inner">
									<h3>1/2</h3>
									<?php echo $filler ?>
								</div>
							</div>
						</div>
						<div class="elgg-grid">
							<div class="elgg-col elgg-col-1of1">
								<div class="elgg-inner">
									<h3>1</h3>
									<?php echo $filler ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="elgg-col elgg-col-1of5 elgg-col-last">
		<div class="elgg-inner">
			<h3>1/5</h3>
			<?php echo str_repeat($filler, 3) ?>
		</div>
	</div>
</div>
