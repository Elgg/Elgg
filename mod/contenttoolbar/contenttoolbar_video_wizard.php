<?php
/*
 * This script display the video wizard
 * 
 * Created on Apr 3, 2007
 * 
 * @uses $metatags
 * @uses $CFG
 * 
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Diego Andrés Ramírez Aragón - 2007
*/

require_once (dirname(dirname(__FILE__)) . "/../includes.php");

global $metatags,$CFG;
templates_page_setup();

$field = optional_param('input_field','new_weblog_post');

$url= substr($CFG->wwwroot, 0, -1);
$metatags .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$url/mod/contenttoolbar/js/helpers.js\"></script>";
$metatags .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$url/mod/contenttoolbar/js/script.js\"></script>";
$metatags .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$url/mod/contenttoolbar/js/edit.js\"></script>";
$metatags .= "<link rel=\"stylesheet\" href=\"" . $CFG->wwwroot . "mod/contenttoolbar/wizard.css\" type=\"text/css\" media=\"screen\" />";

$explanation = __gettext("To embed videos from popular sites like Google Video and Youtube, obtain the embed HTML, paste it in the following form and configure your preferred size:");
$video_url_label = __gettext("Video URL");
$video_size_label = __gettext("Video size");
$video_button_label = __gettext("Insert video");
$error_msg = __gettext("We had trouble understanding this code. Are you sure this is the embed HTML for your video?");

$run_result = templates_draw(array('context'=>'video_wizard',
                                    'title'=> $CFG->sitename,
                                    'explanation' => $explanation,
                                    'video_url_label' => $video_url_label,
                                    'video_size_label' => $video_size_label,
                                    'video_button_label' => $video_button_label,
                                    'error_msg' => $error_msg,
                                    'input_field' => $field 
                                    )
                             );

echo $run_result;
?>
