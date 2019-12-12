<?php
/**
 * Grid CSS
 */

$filler = "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>\n";

// build list of units (denominators are keys, values are arrays of nominators)
$units = [1 => [1],];

// keep map to avoid duplicates. keys are rounded to thousands (avoid float issues)
$percentages = [
	'100' => [1, 1],
];

for ($den = 2; $den <= 6; $den++) {
	for ($num = 1; $num < $den; $num++) {
		// avoid duplicates
		$rounded_percentage = (string) round($num / $den, 3);
		if ($num > 1 && isset($percentages[$rounded_percentage])) {
			continue;
		}
		$percentages[$rounded_percentage] = [$num, $den];
		$units[$den][] = $num;
	}
}

// build rows
$rows = [];
$total = 0;
for ($den = 1; $den <= count($units); $den++) {
	// may take multiple rows to use up available units
	while ($units[$den]) {
		$row = [];
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

<div class="elgg-module elgg-module-info theme-sandbox-grid-demo">
	<div class="elgg-head">
		<h3 class="theme-sandbox-section-heading">6 Column Grid</h3>
	</div>
	<?php foreach ($rows as $row) : ?>
		<div class="elgg-grid theme-sandbox-grid-demo-solid">
			<?php foreach ($row as $col) :
				$class = "elgg-col elgg-col-" . str_replace('/', 'of', $col);
				$text = str_replace(' ', '<br/>', $class);
				?>
				<div class="<?php echo $class ?>">
					<div class="elgg-inner elgg-border-plain elgg-justify-center pam mtl"><?php echo $text ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>

<div class="elgg-module elgg-module-info theme-sandbox-grid-demo">
	<div class="elgg-head">
		<h3 class="theme-sandbox-section-heading">Nested Grids</h3>
	</div>
	<div class="elgg-grid theme-sandbox-grid-demo-outline">
		<div class="elgg-col elgg-col-1of5">
			<div class="elgg-inner elgg-border-plain elgg-justify-center pam mtl">
				<h3>1/5</h3>
				<?php echo str_repeat($filler, 3) ?>
			</div>
		</div>
		<div class="elgg-col elgg-col-3of5">
			<div class="elgg-inner clearfix elgg-border-plain elgg-justify-center pam mtl">
				<h3>3/5</h3>
				<div class="elgg-grid theme-sandbox-grid-demo-outline">
					<div class="elgg-col elgg-col-1of2">
						<div class="elgg-inner  elgg-border-plain elgg-justify-center pam mtl">
							<h3>1/2</h3>
							<?php echo $filler ?>
						</div>
					</div>
					<div class="elgg-col elgg-col-1of2 elgg-col-last">
						<div class="elgg-inner elgg-border-plain elgg-justify-center pam mtl">
							<h3>1/2</h3>
							<?php echo $filler ?>
						</div>
					</div>
				</div>
				<div class="elgg-grid theme-sandbox-grid-demo-outline">
					<div class="elgg-col elgg-col-1of3">
						<div class="elgg-inner elgg-border-plain elgg-justify-center pam mtl">
							<h3>1/3</h3>
							<?php echo str_repeat($filler, 2) ?>
						</div>
					</div>
					<div class="elgg-col elgg-col-2of3 elgg-col-last">
						<div class="elgg-inner elgg-border-plain elgg-justify-center pam mtl">
							<h3>2/3</h3>
							<div class="elgg-grid theme-sandbox-grid-demo-outline">
								<div class="elgg-col elgg-col-1of2">
									<div class="elgg-inner elgg-border-plain elgg-justify-center pam mtl">
										<h3>1/2</h3>
										<?php echo $filler ?>
									</div>
								</div>
								<div class="elgg-col elgg-col-1of2 elgg-col-last">
									<div class="elgg-inner elgg-border-plain elgg-justify-center pam mtl">
										<h3>1/2</h3>
										<?php echo $filler ?>
									</div>
								</div>
							</div>
							<div class="elgg-grid theme-sandbox-grid-demo-outline">
								<div class="elgg-col elgg-col-1of1">
									<div class="elgg-inner elgg-border-plain elgg-justify-center pam mtl">
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
			<div class="elgg-inner elgg-border-plain elgg-justify-center pam mtl">
				<h3>1/5</h3>
				<?php echo str_repeat($filler, 3) ?>
			</div>
		</div>
	</div>
</div>
