<?php
/*
 * i18n.php
 *
 *  Scripts that scan an generates the po file for the specified directory
 *
 * Created on May 11, 2007
 *
 * @author Diego Andr�s Ram�rez Arag�n <diego@somosmas.org>
 * @copyright Corporaci�n Somos m�s - 2007
 */

class i18n_Manager {
  var $_directory;
  var $_translations= array ();
  var $_default_locale;
  var $_header;

  function i18n_Manager($directory, $locale= "en_GB",$header="elgg_header.txt") {
    if (is_dir($directory)) {
      $this->_directory= $directory;
    }
    $this->_default_locale= $locale;
    $this->_header = $header;
  }

  function initCurrentTranslations($file) {
    $file= file($file);
    $i= 0;
    foreach ($file as $line) {
      if (strpos($line, 'msgid') === 0) {
        $key = trim(substr($line,strlen('msgid')));
        if (strpos($file[$i -1], "#: ") >= 0) {
          $this->_translations[$key]['file']= trim($file[$i -1]);
        }
        $this->_translations[$key]['msgid']= $key;
        $translation = trim(substr($file[$i +1],strlen('msgstr')));
        $this->_translations[$key]['msgstr']= $translation;
      }
      $i++;
    }
  }

  function isTranslated($string) {
    return (array_key_exists($string, $this->_translations));
  }

  function addTranslation($key, $file= "") {
    if (!array_key_exists($key, $this->_translations)) {
      $this->_translations[$key]['msgid']= $key;
      $this->_translations[$key]['msgstr']= "";
      if ($file != "") {
        $file= (!strpos($file, "#: ")) ? "#: " . $file : $file;
        $this->_translations[$key]['file']= $file;
      }
    }
  }

  function process() {
    $po_dir= $this->_directory . DIRECTORY_SEPARATOR . "languages" . DIRECTORY_SEPARATOR . $this->_default_locale . DIRECTORY_SEPARATOR . "LC_MESSAGES";
    if (is_dir($po_dir)) {
      $po_files= scandir($po_dir);
      foreach ($po_files as $po_file) {
        if (strpos($po_file, ".po") > 0) {
          echo "\tLoading current translations from $po_file\n";
          $this->initCurrentTranslations($po_dir . DIRECTORY_SEPARATOR . $po_file);
        }
      }
    } else {
      echo "\tBuilding language directory layout\n";
      @mkdir($this->_directory . DIRECTORY_SEPARATOR . "languages");
      @mkdir($this->_directory . DIRECTORY_SEPARATOR . "languages" . DIRECTORY_SEPARATOR . $this->_default_locale);
      @mkdir($this->_directory . DIRECTORY_SEPARATOR . "languages" . DIRECTORY_SEPARATOR . $this->_default_locale . DIRECTORY_SEPARATOR . "LC_MESSAGES");
    }
    $current_strings= $this->scan();
    foreach ($current_strings as $string) {
      if (!$this->isTranslated($string['string'])) {
        echo "\tAdding new translation string: " . $string['string'] ."\n";
        $this->addTranslation($string['string'], $string['file']);
      }
    }
    $this->export($this->_header,$po_dir.DIRECTORY_SEPARATOR.$this->_default_locale.".po");
  }

  function scan() {
    global $grep_path;
    $result= array ();

    $strings= shell_exec($grep_path.'grep -nrPo \'__gettext\("(.*?[^\\\])"\)\' ' . $this->_directory . DIRECTORY_SEPARATOR);
    $strings.= shell_exec($grep_path.'grep -nrEo "__gettext\(\'(.*?[^\\\])\'\)" ' . $this->_directory . DIRECTORY_SEPARATOR);
    $strings= explode("\n", $strings);
    foreach ($strings as $string) {
      if (!strpos($string, "svn")) {
        $string= str_replace($this->_directory . DIRECTORY_SEPARATOR, "", $string);
        // echo $string . "\n\n";
        preg_match("/([\w\.\/]+:[\d]+:)(.+)/", $string, $matches);
        // print_r($matches);
        if (count($matches) == 3) {
          // echo "line: ".$matches[1]."\n";
          $_file = $matches[1];
          //echo "gettext ".$matches[2]."\n";
          // preg_match("/__gettext\(([\w\s\"\'\d\D]+)\)((.+))/i", $matches[2], $matches);
          preg_match("/__gettext\((.+)\)/i", $matches[2], $matches);
          //print_r($matches);
          // Drop locale domain
          $_matches = preg_split('/,\s*"|,\s*\'/',$matches[1]);
          //print_r($_matches);
          $_line = $_matches[0];
          $result[]= array('file'=>$_file,'string'=>$_line);
          //echo "------------------\n";
        }
      }
    }
    return $result;
  }

  function export($header, $outputfile, $overwrite= false) {
    if (file_exists($outputfile) && !$overwrite) {
      $outputfile= $outputfile . ".new";
    }
    echo "Creating language file $outputfile\n";
    $mr= fopen($outputfile, "w");
    $header = file_get_contents($header);

    if(function_exists('mb_convert_encoding')){
      echo "Setting UTF-8\n";
      mb_internal_encoding('UTF-8');
      $header = mb_convert_encoding($header,'UTF-8');
    }

    fwrite($mr, $header);
    fwrite($mr, "\n");
    foreach ($this->_translations as $translation) {
      if($translation['msgid']=="\"\"") continue;
      if (array_key_exists('file', $translation)) {
        fwrite($mr, $translation['file'] . "\n");
      }
      $msgid = $translation['msgid'];
      $msgstr = $translation['msgstr'];

      if(function_exists('mb_convert_encoding')){
        $msgid = mb_convert_encoding($msgid,"UTF-8");
        $msgstr = mb_convert_encoding($msgstr,"UTF-8");
      }

      fwrite($mr,"msgid ". $msgid . "\n");
      $msg = (empty($msgstr))?"\"\"":$msgstr;
      fwrite($mr,"msgstr ". $msg . "\n\n");
    }
    fclose($mr);
  }
}

function print_use(){
  echo "Use i18n.php <directory> <locale> <header_file>\n\n";
  echo "  If you use this comments without parameteres it assumes:\n";
  echo "  \t <directory> = . \n";
  echo "  \t <locale> en_GB \n";
  echo "  \t <header_file> elgg_header.txt (taked from the utils directory)\n\n";
  exit;
}

/*
 * Command excution
 */
require_once dirname(__FILE__) . "/../config.php";

$grep_path = "/bin/";
// For ubuntu systems your need to install a perl-regexp capable grep version
//   sudo apt-get install libpcre3-dev
//   wget ftp://mirrors.kernel.org/gnu/grep/grep-2.5.1a.tar.gz
//   tar xvzf  grep-2.5.1a.tar.gz
//   cd grep-2.5.1a
//   ./configure --prefix=/
//   make
//   sudo make install

if($argc > 4 ){
  print_use();
}

$directory = ($argc>1)?$argv[1]:".";
$locale = ($argc>2)?$argv[2]:"en_GB";
$header = ($argc>3)?$argv[3]:"elgg_header.txt";

if(!is_dir($directory)){
  echo "\nERROR: $directory is not a directory!\n\n";
  print_use();
}

if(!file_exists($header)){
  echo "\nERROR: $header its not a file. falling back to the default header\n\n";
  $header = "elgg_header.txt";
}

echo "Running with the following parameters:\n";
echo "  Directory:\t$directory\n";
echo "  Locale:\t$locale\n";
echo "  Header:\t$header\n\n";

$header = ("elgg_header.txt"==$header)?dirname(__FILE__)."/$header":$header;

$i18n= new i18n_Manager($directory, $locale,$header);
$strings = $i18n->scan();
$i18n->process();
echo "\n";
?>
