<?php
/**
 * CSS Objects: list, module, image_block
 */

$title = 'CSS Objects';

require dirname(__FILE__) . '/head.php';

?>
<body>
	<div class="elgg-page mal">
		<h1 class="mbl"><?php echo $title; ?></h1>
		<div class="mbl"><a href="index.php">return to index</a></div>
		<h2>Modules</h2>
		<div class="mbl clearfix">
			<div class="elgg-col elgg-col-1of2">
				<div class="pam">
				<?php
					echo elgg_view('layout/objects/module', array(
						'title' => 'elgg-aside-module',
						'body' => $ipsum,
						'class' => 'elgg-aside-module',
					));
				?>
				<?php
					echo elgg_view('layout/objects/module', array(
						'title' => 'elgg-popup-module',
						'body' => $ipsum,
						'class' => 'elgg-popup-module',
					));
				?>
				</div>
			</div>
			<div class="elgg-col elgg-col-1of2">
				<div class="pam">
				<?php
					echo elgg_view('layout/objects/module', array(
						'title' => 'elgg-group-module',
						'body' => $ipsum,
						'class' => 'elgg-group-module',
					));
				?>
				</div>
				<div class="pam">
				<?php
					echo elgg_view('layout/objects/module', array(
						'title' => 'elgg-info-module',
						'body' => $ipsum,
						'class' => 'elgg-info-module',
					));
				?>
				</div>
			</div>
		</div>
		<h2>Image Block</h2>
		<div class="mbl clearfix">
		<?php
			$src = elgg_view('icon/user/default/small');
			$image = "<img src=\"$src\" />";
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
	</div>
</body>
</html>