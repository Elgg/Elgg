<?php
/*
 * This script init the content toolbar adding the required javascript functions
 * and displaying the toolbar
 * 
 * It is called with run("display:content:toolbar",$parameter)
 * Created on Apr 3, 2007
 * 
 * @uses $metatags
 * @uses $CFG
 * @uses $contenttoolbarbuttons
 * 
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Diego Andrés Ramírez Aragón - 2007
*/

global $metatags,$CFG,$contenttoolbarbuttons;

if(!empty($parameter)){
  $field = $parameter;
}
else {
  $field = 'new_weblog_post'; 
}

$url= substr($CFG->wwwroot, 0, -1);
$metatags .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$url/mod/contenttoolbar/js/helpers.js\"></script>";
$metatags .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$url/mod/contenttoolbar/js/script.js\"></script>";
$metatags .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$url/mod/contenttoolbar/js/edit.js\"></script>";
$metatags .= "<link rel=\"stylesheet\" href=\"" . $CFG->wwwroot . "mod/contenttoolbar/css.css\" type=\"text/css\" media=\"screen\" />";

$buttons = implode("&nbsp;",array_map(get_html_button,$contenttoolbarbuttons));
$js_buttons = implode(",",array_map(get_js_button,$contenttoolbarbuttons));

$js_buttons="var toolbar = [$js_buttons];";

//TODO Add the field to the display params
$run_result .= templates_draw(array('context'=>'contenttoolbar',
                                    'js_buttons'=> $js_buttons,
                                    'buttons'=> $buttons,
                                    'input_field'=>$field
                                    )
                             );

?>
