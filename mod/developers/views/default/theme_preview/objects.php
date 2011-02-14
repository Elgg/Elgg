<?php
/**
 * CSS Objects: list, module, image_block
 */

$url = current_page_url();

?>

<div class="elgg-page mal">
	<h1 class="mbs">
		<a href="index.php">Index</a> > <a href="<?php echo $url; ?>"><?php echo $title; ?></a>
	</h1>
	<div class="mbl">
		<a href="forms.php">< previous</a>&nbsp;&nbsp;<a href="grid.php">next ></a>
	</div>
	<h2>Modules</h2>
	<div class="mbl clearfix">
		<div class="elgg-col elgg-col-1of2">
			<div class="pam">
			<?php
				echo elgg_view_module('aside', 'elgg-module-aside', $ipsum);
				echo elgg_view_module('popup', 'elgg-module-popup', $ipsum);
			?>
			</div>
		</div>
		<div class="elgg-col elgg-col-1of2">
			<div class="pam">
			<?php
				echo elgg_view_module('info', 'elgg-module-info', $ipsum);
				echo elgg_view_module('featured', 'elgg-module-featured', $ipsum);
			?>
			</div>
		</div>
	</div>
	<h2>Image Block</h2>
	<div class="mbl clearfix">
	<?php
		$user = new ElggUser();
		$image = elgg_view_entity_icon($user, 'small');
		echo elgg_view_image_block($image, $ipsum);
	?>
	</div>
	<h2>List</h2>
	<div class="mbl clearfix">
	<?php
		$obj1 = new ElggObject();
		$obj1->title = "Object 1";
		$obj1->description = $ipsum;
		$obj2 = new ElggObject();
		$obj2->title = "Object 2";
		$obj2->description = $ipsum;
		echo elgg_view('layout/objects/list', array('items' => array($obj1, $obj2)));
	?>
	</div>
	<h2>Table</h2>
	<div class="mbl clearfix">
		<table class="elgg-table">
		<?php
			echo "<thead><tr><th>column 1</th><th>column 2</th></tr></thead>";
			for ($i = 1; $i < 5; $i++) {
				echo '<tr>';
				for ($j = 1; $j < 3; $j++) {
					echo "<td>value $j</td>";
				}
				echo '</tr>';
			}
		?>
		</table>
	</div>
	<h2>Messages</h2>
	<div class="mbl clearfix">
		<ul>
			<li class="elgg-message elgg-state-success mas">Success message</li>
			<li class="elgg-message elgg-state-error mas">Error message</li>
			<li class="elgg-message elgg-state-notice mas">Notice message</li>
		</ul>
	</div>
</div>
