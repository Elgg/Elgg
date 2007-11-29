<?php

global $template;
global $template_definition;

$template_definition[] = array( 'id' => 'folder',
                                'name' => __gettext("Folder"),
                                'description' => __gettext("Each individual folder"),
                                'glossary' => array('{{username}}' => __gettext("The owner of the folder"),
                                                    '{{name}}' => __gettext("The name of the folder"),
                                                    '{{url}}' => __gettext("The folder's URL"),
                                                    '{{menu}}' => __gettext("Menu items for folder owner (edit, delete, etc)"),
                                                    '{{icon}}' => __gettext("The URL of the file's icon"),
                                                    '{{keywords}}' => __gettext("Keywords associated with the folder")
                                                    )
                                );


$template_definition[] = array( 'id' => 'file',
                                'name' => __gettext("File"),
                                'description' => __gettext("Each individual file within a folder"),
                                'glossary' => array('{{url}}' => __gettext("The file's URL"),
                                                    '{{originalname}}' => __gettext("Its filename"),
                                                    '{{description}}' => __gettext("A description of the file"),
                                                    '{{title}}' => __gettext("Its title"),
                                                    '{{menu}}' => __gettext("Menu items for file owner (edit, delete, etc)"),
                                                    '{{icon}}' => __gettext("The URL of its icon"),
                                                    '{{keywords}}' => __gettext("Keywords associated with the folder")
                                                    )
                                );

$template_definition[] = array('id'=> 'file_wizard',
                               'name' => __gettext("File selection wizard"),
                               'description' => __gettext("Wizard page that show the file list"),
                               'glossary' => array('{{user_id}}' => __gettext("File's owner id"),
                                                   '{{folder}}' => __gettext("Initial folder"),
                                                   '{{input_field}}' => __gettext("Input field to be modified by the wizard")
                                             )
                         );
                         
$template['folder'] = file_get_contents(dirname(__FILE__)."/templates/file_folder.html");
$template['file'] = file_get_contents(dirname(__FILE__)."/templates/file_file.html");
$template['file_wizard'] = file_get_contents(dirname(__FILE__)."/templates/file_wizard.html");
?>