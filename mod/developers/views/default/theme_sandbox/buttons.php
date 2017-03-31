<table class="table table-striped">
	<thead>
		<tr>
			<th>Anchor links</th>
			<th>Default</th>
			<th>Disabled (.elgg-state-disabled)</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Base (.elgg-button)</th>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button',
					'text' => 'Button',
				]);
				?>
			</td>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button elgg-state-disabled',
					'text' => 'Button',
				]);
				?>
			</td>
		</tr>
		<tr>
			<th>Action (.elgg-button-action)</th>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button elgg-button-action',
					'text' => 'Action',
				]);
				?>
			</td>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button elgg-button-action elgg-state-disabled',
					'text' => 'Action',
				]);
				?>
			</td>
		</tr>
		<tr>
			<th>Cancel (.elgg-button-cancel)</th>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button elgg-button-cancel',
					'text' => 'Cancel',
				]);
				?>
			</td>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button elgg-button-cancel elgg-state-disabled',
					'text' => 'Cancel',
				]);
				?>
			</td>
		</tr>
		<tr>
			<th>Submit (.elgg-button-submit)</th>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button elgg-button-submit',
					'text' => 'Submit',
				]);
				?>
			</td>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button elgg-button-submit elgg-state-disabled',
					'text' => 'Submit',
				]);
				?>
			</td>
		</tr>
		<tr>
			<th>Special (.elgg-button-special)</th>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button elgg-button-special',
					'text' => 'Special',
				]);
				?>
			</td>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button elgg-button-special elgg-state-disabled',
					'text' => 'Special',
				]);
				?>
			</td>
		</tr>
		<tr>
			<th>Delete (.elgg-button-delete)</th>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button elgg-button-delete',
					'text' => 'Delete',
				]);
				?>
			</td>
			<td>
				<?=
				elgg_view('output/url', [
					'href' => '#',
					'class' => 'elgg-button elgg-button-delete elgg-state-disabled',
					'text' => 'Delete',
				]);
				?>
			</td>
		</tr>
	</tbody>
</table>

<table class="table table-striped mtl">
	<thead>
		<tr>
			<th>Input type="submit"</th>
			<th>Default</th>
			<th>Disabled (.elgg-state-disabled)</th>
			<th>Disabled [attribute disabled=true]</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Base (.elgg-button)</th>
			<td><input type="submit" class="elgg-button" value="submit" /></td>
			<td><input type="submit" class="elgg-button elgg-state-disabled" value="submit" /></td>
			<td><input type="submit" class="elgg-button" disabled="disabled" value="submit" /></td>
		</tr>
		<tr>
			<th>Action (.elgg-button-action)</th>
			<td><input type="submit" class="elgg-button elgg-button-action" value="submit" /></td>
			<td><input type="submit" class="elgg-button elgg-button-action elgg-state-disabled" value="submit" /></td>
			<td><input type="submit" class="elgg-button elgg-button-action" disabled="disabled" value="submit" /></td>
		</tr>
		<tr>
			<th>Cancel (.elgg-button-cancel)</th>
			<td><input type="submit" class="elgg-button elgg-button-cancel" value="submit" /></td>
			<td><input type="submit" class="elgg-button elgg-button-cancel elgg-state-disabled" value="submit" /></td>
			<td><input type="submit" class="elgg-button elgg-button-cancel" disabled="disabled" value="submit" /></td>
		</tr>
		<tr>
			<th>Submit (.elgg-button-submit)</th>
			<td><input type="submit" class="elgg-button elgg-button-submit" value="submit" /></td>
			<td><input type="submit" class="elgg-button elgg-button-submit elgg-state-disabled" value="submit" /></td>
			<td><input type="submit" class="elgg-button elgg-button-submit" disabled="disabled" value="submit" /></td>
		</tr>
		<tr>
			<th>Special (.elgg-button-special)</th>
			<td><input type="submit" class="elgg-button elgg-button-special" value="submit" /></td>
			<td><input type="submit" class="elgg-button elgg-button-special elgg-state-disabled" value="submit" /></td>
			<td><input type="submit" class="elgg-button elgg-button-special" disabled="disabled" value="submit" /></td>
		</tr>
		<tr>
			<th>Delete (.elgg-button-delete)</th>
			<td><input type="submit" class="elgg-button elgg-button-delete" value="submit" /></td>
			<td><input type="submit" class="elgg-button elgg-button-delete elgg-state-disabled" value="submit" /></td>
			<td><input type="submit" class="elgg-button elgg-button-delete" disabled="disabled" value="submit" /></td>
		</tr>
	</tbody>
</table>