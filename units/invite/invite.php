<?php

	// Ask for details to invite a friend
	
		$run_result .= <<< END
		
		<form action="" method="post">
		
END;
		$run_result .= run("templates:draw", array(
														'context' => 'databox1',
														'name' => 'Their name',
														'column1' => run("display:input_field",array("invite_name","","text"))
							)
							);
		$run_result .= run("templates:draw", array(
														'context' => 'databox1',
														'name' => 'Their email address',
														'column1' => run("display:input_field",array("invite_email","","text"))
							)
							);
							
		$run_result .= run("templates:draw", array(
														'context' => 'databox1',
														'name' => 'An optional message',
														'column1' => run("display:input_field",array("invite_text","","longtext"))
							)
							);
							
		$run_result .= run("templates:draw", array(
														'context' => 'databox1',
														'name' => '&nbsp;',
														'column1' => '<input type="submit" value="Invite" />'
							)
							);
							
		$run_result .= <<< END
		
			<input type="hidden" name="action" value="invite_invite" />
		</form>
		
END;

?>