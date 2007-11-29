<?php
/*
 * This script defines the templates used by the content toolbar module
 * 
 * Created on Apr 3, 2007
 * 
 * @uses $template
 * @uses $template_definitions
 * 
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Diego Andrés Ramírez Aragón - 2007
*/

global $template;
global $template_definition;

$template_definition[] = array (
  'id' => 'contenttoolbar',
  'name' => __gettext("Content Toolbar"), 
  'description' => __gettext("A placeholder for content related buttons (resources, videos)"), 
  'glossary' => array (
          '{{js_buttons}}' => __gettext("JS buttons definitions"),
          '{{buttons}}' => __gettext("Buttons definitions")
      )
);

$template_definition[] = array(
  'id' => 'video_wizard',
  'name' => __gettext("Video insert wizard page"),
  'description' => __gettext("The wizard page to add a video from an external site"),
  'glossary' => array(
    '{{title}}' => __gettext("Site name"),
    '{{explanation}}' => __gettext("Wizard explanation"),
    '{{video_url_label}}' => __gettext("Video URL label"),
    '{{video_size_label}}' => __gettext("Video size label"),
    '{{video_button_label}}' => __gettext("Video insert button label"),
    '{{input_field}}' => __gettext("Input field for the video tag"),
    '{{error_msg}}' => __gettext("Invalid embed object error message") 
  )
);

$template["contenttoolbar"] = file_get_contents(dirname(__FILE__)."/templates/contenttoolbar.html");
$template["video_wizard"] = file_get_contents(dirname(__FILE__)."/templates/video_wizard.html");
?>
