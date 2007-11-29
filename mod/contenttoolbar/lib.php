<?php

/**
 * contenttoolbar_pagesetup
 */
function contenttoolbar_pagesetup(){
  require_once (dirname(__FILE__)."/default_template.php");
}

/**
 * contenttoolbar_init
 * 
 * It adds to $CFG the contenttoolbar_icons property 
 * 
 * @uses $CFG 
 * @uses $function
 */
function contenttoolbar_init(){
  global $CFG,$function;
  
  $CFG->contenttoolbar_icons = false;
  
  $CFG->allowobjectembed=true;
  

  require_once (dirname(__FILE__)."/lib/contenttoolbar_functions.php");

  // Content toolbar
  $function["display:content:toolbar"][] = $CFG->dirroot ."mod/contenttoolbar/lib/contenttoolbar_init.php";
  
  // Weblog text processing
  $function["video:text:process"][] = $CFG->dirroot."mod/contenttoolbar/lib/contenttoolbar_text_processing.php";
  
  if($CFG->allowobjectembed){
    adjust_allowed_tags();
    // Adding widgets
    $CFG->widgets->list[] = array(
                                        'name' => __gettext("Video widget"),
                                        'description' => __gettext("Displays a video of your choice."),
                                        'type' => "contenttoolbar::video"
                                );
  }
  // Addding the external video button
  $options = array('options'=> 'width=400,height=300,left=20,top=20,scrollbars=yes,resizable=yes',
                   'name'=> 'mediapopup',
                   'url' => $CFG->wwwroot."mod/contenttoolbar/contenttoolbar_video_wizard.php");
  add_content_tool_button("mediapopup",__gettext("Add External video"), "image.png", "v", $options);
}

function contenttoolbar_widget_edit($widget) {
  global $CFG, $page_owner;

  $video_url = widget_get_data("video_url",$widget->ident);
  $video_width = widget_get_data("video_width",$widget->ident);
  $video_height = widget_get_data("video_height",$widget->ident);

  $video_width = ($video_width==null || $video_width==0 || $video_width=="")?"200":$video_width;
  $video_height = ($video_height==null || $video_height==0 || $video_height=="")?"240":$video_height;

  $body = "<h2>" . __gettext("Video widget") . "</h2>";
  $explanation = __gettext("To embed videos from popular sites like Google Video and Youtube, obtain the embed HTML, paste it in the following form and configure your preferred size:");
  $video_url_label = __gettext("Video URL");
  $video_size_label = __gettext("Video size");
  $video_button_label = __gettext("Insert Video");
  
  $body.=file_get_contents(dirname(__FILE__)."/templates/video_widget.html");
  $body =str_replace("{{explanation}}",$explanation,$body);
  $body =str_replace("{{video_url_label}}",$video_url_label,$body);
  $body =str_replace("{{video_size_label}}",$video_size_label,$body);
  $body =str_replace("{{video_width}}",$video_width,$body);
  $body =str_replace("{{video_height}}",$video_height,$body);
  $body =str_replace("{{video_url}}",$video_url,$body);

  return $body;
}

function contenttoolbar_widget_display($widget){
  global $CFG;
  
  $video_url = widget_get_data("video_url",$widget->ident);
  $video_width = widget_get_data("video_width",$widget->ident);
  $video_height = widget_get_data("video_height",$widget->ident);
  
  $embedpattern = "/<embed[\w\s\"=;:.&\?\/-]*>\s*<\/embed>/";
  $urlpattern = "/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?\"/";
  preg_match($embedpattern,$video_url,$embed);
  if(empty($embed)){
    $body = __gettext("Invalid video param edit an check that if is a valid &lt;embed> object.");
  }
  else{
    preg_match($urlpattern,$video_url,$url);
    $video_url=substr($url[0],0,-1);
    $body = run("video:text:process","{{video:$video_url}}");
  }
  return array('title'=>"",'content'=>$body);
}
?>