<?php

	// Preview template
	
	// Basic page elements
	
		$name = "Basic page elements";
		$body = <<< END
		
	<img src="/_templates/leaves.jpg" width="300" height="225" alt="A test image" align="right" />
	<h1>Heading one</h1>
	<p>
		Paragraph text <b>bold</b> <u>underlined</u> <i>italics</i> <a href="#">link</a>
	</p>
	<h2>Heading two</h2>
	<ul>
		<li>A bullet list</li>
	</ul>
	<h3>Heading three</h3>
	<ol>
		<li>A numbered list</li>
	</ol>
		
END;

		$run_result .= run("templates:draw", array(
													'context' => 'infobox',
													'name' => $name,
													'contents' => $body
												)
												);

	// Form elements
	
		$name = "Data input";

		$body = run("templates:draw", array(
												'context' => 'databox',
												'name' => "Some text input",
												'column1' => run("display:input_field",array("blank","","text")),
												'column2' => run("display:access_level_select",array("blank","PUBLIC"))
											)
											);
		$body .= run("templates:draw", array(
												'context' => 'databox1',
												'name' => "Some longer text input",
												'column1' => run("display:input_field",array("blank","","longtext"))
											)
											);
		$body .= run("templates:draw", array(
												'context' => 'databoxvertical',
												'name' => "Further text input",
												'contents' => run("display:input_field",array("blank","","longtext")) . "<br />" . run("display:input_field",array("blank","","text")) . "<br /><input type='button' value='Button' />"
											)
											);
		
		$run_result .= run("templates:draw", array(
														'context' => 'infobox',
														'name' => $name,
														'contents' => $body
													)
													);
?>