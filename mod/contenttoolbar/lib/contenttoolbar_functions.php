<?php
/*
 * This defines the functions used for add and display the content toolbar buttons
 * 
 * Created on Apr 3, 2007
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Diego Andrés Ramírez Aragón - 2007
*/

/**
 * Adds a new button to the toolbar
 * @param string $type Button's type (picker | mediapopup)
 * @param string $title Button's label
 * @param string $icon Button's icon name
 * @param string $key Button's accesskey accelerator
 * @param array $options Button's extra parameters (open | close | sample | options)
 * @return void
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Copyright &copy; 2007, Diego Andrés Ramírez Aragón
 */
function add_content_tool_button($type,$title, $icon, $key, $options){
  global $contenttoolbarbuttons;
  
  if(empty($type) || empty($title)) return;
  
  $button = array();
  $button['id'] = $type."_".count($contenttoolbarbuttons);
  $button['type'] = $type;
  $button['title'] = $title;
  $button['icon'] = (empty($icon))?$title:$icon;
  if(!empty($key)){
    $button['key'] = $key;
  }
  if(!empty($options)){
    $button['options'] = $options;
  }
  array_push($contenttoolbarbuttons,$button);
}

/**
 * This function get the HTML representation of a toolbar button
 * @param array $button Button specification
 * @uses $CFG
 * @return string The HTML string that represent the button
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Copyright &copy; 2007, Diego Andrés Ramírez Aragón
 */
function get_html_button($button){  
  global $CFG;
  $title = $button['title'];
  $label = $title;
  $id = $button['id'];
  if(array_key_exists('key',$button)){
    $accesskey = "accesskey=\"".$button['key']."\"";
    $title.= " [ALT+ ".strtoupper($button['key'])."]";
  }
  $html_button = "<input type=\"button\" class=\"toolbutton\" id=\"$id\" title=\"$title\" $accesskey value=\"$label\"/>";
  if($button['icon']!=$title && $CFG->contenttoolbar_icons){
    $html_button = "<img class=\"toolbutton\" id=\"$id\" title=\"$title\" src=\"".$CFG->wwwroot."mod/contenttoolbar/img/".$button['icon']."\" $accesskey/>";
  }
  return $html_button;
}

/**
 * Get the JS representation of a toolbar button (used to generate the button events)
 * @param array $button Button specification
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Copyright &copy; 2007, Diego Andrés Ramírez Aragón
 */
function get_js_button($button){
  //{"type":"format","title":"Level 5 Headline","icon":"h5.png","key":"5","open":"== ","close":" ==\\n"}
  $js_button ="{";
  foreach(array_keys($button) as $property){
    if(is_array($button[$property])){
      foreach(array_keys($button[$property]) as $tproperty){
        $js_button .= "\"$tproperty\":\"".$button[$property][$tproperty]."\",";
      }
    }
    else{
      $js_button .= "\"$property\":\"".$button[$property]."\",";
    }
  }
  $js_button = substr($js_button,0,strlen($js_button)-1);
  $js_button.="}";
  return $js_button;  
}

global $contenttoolbarbuttons;
$contenttoolbarbuttons=array();
?>
