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