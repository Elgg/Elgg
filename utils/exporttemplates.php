<?php
/***
 *** exporttemplates.php -- exports the in-db templates to files
 ***
 *** Define $CFG->templatedir and then execute
 ***
 ***   php4 -c /etc/php4/apache/php.ini utils/exporttemplates.php
 ***
 ***/
$starttime = microtime();

require_once(dirname(dirname(__FILE__)). '/includes.php');

// setup destination directory
if (empty($CFG->templatesroot)) {
    cli_die('$CFG->templatesroot is not defined');
}
if (!is_dir($CFG->templatesroot)) {
    if (!mkdir($CFG->templatesroot, 0755)) {
        cli_die("Cannot create $CFG->templatesroot");
    }
}

$templates = get_records('templates');
foreach ($templates as $template) {
    $dirname = strtolower($template->name);
    $dirname = clean_param($dirname, PARAM_ALPHANUM);
    print "$dirname\n";
    if (!is_dir($CFG->templatesroot."/$dirname")) {
        if (!mkdir($CFG->templatesroot."/$dirname", 0755)) {
            cli_die("Cannot create $CFG->templatesroot/$dirname");
        }
    }
    $elements = get_records('template_elements', 'template_id', $template->ident);
    foreach ($elements as $element) {
        $filename = strtolower($element->name);
        $filename = clean_param($filename, PARAM_ALPHANUM);
        if (!($fh = fopen($CFG->templatesroot."/$dirname/$filename", 'a'))) {
            cli_die("Cannot open $CFG->templatesroot/$dirname/$filename");
        }
        if (!fwrite($fh, $element->content)) {
            cli_die("Cannot write to $CFG->templatesroot/$dirname/$filename");
        };
        if (!fclose($fh)) {
            cli_die("Cannot write to $CFG->templatesroot/$dirname/$filename");
        }
    }

}

?>