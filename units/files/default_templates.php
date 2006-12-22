<?php

    global $template;
    global $template_definition;
    
    $template_definition[] = array(
                                    'id' => 'folder',
                                    'name' => __gettext("Folder"),
                                    'description' => __gettext("Each individual folder"),
                                    'glossary' => array(
                                                            '{{username}}' => __gettext("The owner of the folder"),
                                                            '{{name}}' => __gettext("The name of the folder"),
                                                            '{{url}}' => __gettext("The folder's URL"),
                                                            '{{menu}}' => __gettext("Menu items for folder owner (edit, delete, etc)"),
                                                            '{{icon}}' => __gettext("The URL of the file's icon"),
                                                            '{{keywords}}' => __gettext("Keywords associated with the folder")
                                                        )
                                    );
    
    $template['folder'] = <<< END
                    <div class="foldertable">
                    <table>
                        <tr>
                            <td>
                                <a href="{{url}}">
                                    <img src="{{icon}}" width="70" height="59" border="0" alt="" />
                                </a>
                            </td>
                            <td>
                                <p><a href="{{url}}">{{name}}</a> {{menu}}</p>
                                <p>{{keywords}}</p>
                            </td>
                        </tr>
                    </table>
                    </div>
END;

    $template_definition[] = array(
                                    'id' => 'file',
                                    'name' => __gettext("File"),
                                    'description' => __gettext("Each individual file within a folder"),
                                    'glossary' => array(
                                                            '{{url}}' => __gettext("The file's URL"),
                                                            '{{originalname}}' => __gettext("Its filename"),
                                                            '{{description}}' => __gettext("A description of the file"),
                                                            '{{title}}' => __gettext("Its title"),
                                                            '{{menu}}' => __gettext("Menu items for file owner (edit, delete, etc)"),
                                                            '{{icon}}' => __gettext("The URL of its icon"),
                                                            '{{keywords}}' => __gettext("Keywords associated with the folder")
                                                        )
                                    );

    $template['file'] = <<< END
    
                    <div class="filetable">
                    <table>
                        <tr>
                            <td>
                                <a href="{{url}}">
                                    <img src="{{icon}}" width="56" height="70" border="0" alt="" />
                                </a>
                            </td>
                            <td>
                                <a href="{{url}}"><b>{{title}}</b></a>
                                <p>{{menu}}</p>
                                <p>{{description}}</p>
                                <p>{{originalname}}</p>
                                <p>{{keywords}}</p>
                            </td>
                        </tr>
                    </table>
                    </div>
    
END;

?>