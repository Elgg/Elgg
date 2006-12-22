<?php

require_once(dirname(dirname(__FILE__))."/includes.php");

header("Content-type: text/css; charset=utf-8");

$template_id = optional_param('template',0,PARAM_INT);

echo templates_draw(array(
                          'template' => $template_id,
                          'context' => 'css'
                          )
                    );
                    
?>