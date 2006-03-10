<?php

	// Template preview
	
		$header = gettext("Folder name"); // gettext variable
		$subHeader = gettext("Subfolders"); // gettext variable
		$body = <<< END
		
		<h2>$header</h2>
		<h3>$subHeader</h3>
		
END;
		$body .= run("templates:draw", array(
									'context' => 'folder',
									'username' => gettext("test"),
									'url' => "",
									'ident' => 0,
									'name' => gettext("Subfolder"),
									'icon' => url. "_files/folder.png",
									'menu' => "[<a href=\"#\">" . gettext("Delete") . "</a>]"
								)
								);

		$body .= run("templates:draw", array(
									'context' => 'file',
									'username' => gettext("test"),
									'title' => gettext("A sample file"),
									'ident' => 0,
									'folder' => 0,
									'description' => gettext("This is a file"),
									'originalname' => gettext("filename"),
									'url' => "#",
									'icon' => url . "_files/file.png",
									'menu' => "[<a href=\"#\">" . gettext("Edit") . "</a>] [<a href=\"#\">" . gettext("Delete") . "</a>]"
								)
								);
								
		$run_result .= run("templates:draw", array(
													'context' => 'contentholder',
													'title' => gettext("Files and folders"),
													'body' => $body,
													'submenu' => ''
													)
													);
								
?>