<?php

	// Template preview
	
		$body = <<< END
		
		<h2>Folder name</h2>
		<h3>Subfolders</h3>
		
END;
		$body .= run("templates:draw", array(
									'context' => 'folder',
									'username' => "test",
									'url' => "",
									'ident' => 0,
									'name' => "Subfolder",
									'icon' => "/_files/folder.png"
								)
								);

		$body .= run("templates:draw", array(
									'context' => 'file',
									'username' => "test",
									'title' => "A sample file",
									'ident' => 0,
									'folder' => 0,
									'description' => "This is a file",
									'originalname' => "filename",
									'url' => "#",
									'icon' => "/_files/file.png"
								)
								);
								
		$run_result .= run("templates:draw", array(
													'context' => 'infobox',
													'name' => 'Files and folders',
													'contents' => $body
													)
													);
								
?>