<?php

	if (sizeof($data['files:details']) > 0) {
		
		foreach($data['files:details'] as $details) {

			$run_result .= <<< END
			
			<tr>
				<td>
					<label for="metadata[{$details[1]}]">
							{$details[0]}
					</label>
				</td>
				<td>
					<input id="metadata[{$details[1]}]" name="metadata[{$details[1]}]" value="" />
				</td>
			</tr>
END;
		}
		
	}

?>