<?php

	global $template;
	global $template_definition;
	
	$template_definition[] = array(
									'id' => 'folder',
									'name' => "Folder",
									'description' => "Each individual folder",
									'glossary' => array(
															'{{username}}' => 'The owner of the folder',
															'{{name}}' => 'The name of the folder',
															'{{url}}' => 'The folder\'s URL',
															'{{menu}}' => 'Menu items for folder owner (edit, delete, etc)',
															'{{icon}}' => 'The URL of the file\'s icon',
															'{{keywords}}' => 'Keywords associated with the folder'
														)
									);
	
	$template['folder'] = <<< END
					<table>
						<tr>
							<td valign="middle">
								<a href="{{url}}">
									<img src="{{icon}}" width="93" height="90" border="0" alt="" />
								</a>
							</td>
							<td valign="middle">
								<a href="{{url}}">{{name}}</a> <small>{{menu}}</small><br />
								<small>{{keywords}}</small
							</td>
						</tr>
					</table>
END;

	$template_definition[] = array(
									'id' => 'file',
									'name' => "File",
									'description' => "Each individual file within a folder",
									'glossary' => array(
															'{{url}}' => 'The file\'s URL',
															'{{originalname}}' => 'Its filename',
															'{{description}}' => 'A description of the file',
															'{{title}}' => 'Its title',
															'{{menu}}' => 'Menu items for file owner (edit, delete, etc)',
															'{{icon}}' => 'The URL of its icon',
															'{{keywords}}' => 'Keywords associated with the folder'
														)
									);

	$template['file'] = <<< END
	
					<table>
						<tr>
							<td>
								<a href="{{url}}">
									<img src="{{icon}}" width="90" height="90" border="0" alt="" />
								</a>
							</td>
							<td valign="middle">
								<a href="{{url}}"><b>{{title}}</b></a>
								<small>{{menu}}</small><br />
									{{description}}<br />
								<small>{{originalname}}</small><br />
								<small>{{keywords}}</small>
							</td>
						</tr>
					</table>
	
END;

?>