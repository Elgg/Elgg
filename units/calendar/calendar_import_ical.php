<?php
	$url = url;
	
	$body = <<< END
		<form action="{$url}_calendar/parse_ical_file.php" method="post" enctype="multipart/form-data">
END;

	$title = gettext("Import a calendar");

	$body .= <<< END
	
		<table>
			<tr>
				<td colspan="2"><p>
END;
	
	
	$fileLabel = gettext("File to upload:"); //gettext variable
	$upload = gettext("Import Calendar");

	$body .= <<< END
				</p></td>
			<tr>
				<td width="30%"><p>
					<label for="new_file">
						$fileLabel
					</label>
				</p></td>
				<td><p>
					<input name="new_file" id="new_file" type="file" />
				</p></td>
			</tr>
			
			<tr>
				<td colspan="2" align="left"><br />
					<input type="submit" value="{$upload}" />
				</td>
			</tr>
		</table>
END;

$run_result .= run("templates:draw", array(
							'context' => 'databoxvertical',
							'name' => $title,
							'contents' => $body
						)
						);
	
	$run_result .= <<< END
	</form>
END;


?>