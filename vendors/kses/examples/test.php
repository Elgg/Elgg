<?php

# test - checks if a kses installation is working
# Copyright (C) 2003, 2005  Ulf Harnhammar
#
# This program is free software and open source software; you can redistribute
# it and/or modify it under the terms of the GNU General Public License as
# published by the Free Software Foundation; either version 2 of the License,
# or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful, but WITHOUT
# ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
# FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
# more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA  or visit
# http://www.gnu.org/licenses/gpl.html
#
# *** CONTACT INFORMATION ***
#
# E-mail:      metaur at users dot sourceforge dot net
# Web page:    http://sourceforge.net/projects/kses
# Paper mail:  Ulf Harnhammar
#              Ymergatan 17 C
#              753 25  Uppsala
#              SWEDEN

include '../kses.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>kses test</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>

<body>
<h1>kses test</h1>
<p>

<?php


# *** FUNCTION DEFINITIONS ***


function onetest($htmlbefore, $htmlafter, &$score, &$max, $allowed)
###############################################################################
# This function performs one kses test.
###############################################################################
{
  $max++;

  $htmlkses = kses($htmlbefore, $allowed);
#  echo "htmlkses --".htmlspecialchars($htmlkses)."--<br>\n";

  if ($htmlkses == $htmlafter)
  {
    echo 'OK';
    $score++;
  }
  else
    echo 'not OK';

  echo "<br>\n";
} # function onetest


# *** MAIN PROGRAM ***


$max = $score = 0;

# Test #1

echo 'Test #1.. ';
$htmlbefore = 'kses \'kses\' kses "kses" kses \\kses\\';
$htmlafter =  $htmlbefore;
onetest($htmlbefore, $htmlafter, $score, $max, array());

# Test #2

echo 'Test #2.. ';
$htmlbefore = 'kses <br>';
$htmlafter =  'kses ';
onetest($htmlbefore, $htmlafter, $score, $max, array());

# Test #3

echo 'Test #3.. ';
$htmlbefore = 'kses <  BR  >';
$htmlafter =  'kses <BR>';
onetest($htmlbefore, $htmlafter, $score, $max, array('br' => array()));

# Test #4

echo 'Test #4.. ';
$htmlbefore = 'kses > 5 <br>';
$htmlafter =  'kses &gt; 5 <br>';
onetest($htmlbefore, $htmlafter, $score, $max, array('br' => array()));

# Test #5

echo 'Test #5.. ';
$htmlbefore = 'kses <  br';
$htmlafter =  'kses <br>';
onetest($htmlbefore, $htmlafter, $score, $max, array('br' => array()));

# Test #6

echo 'Test #6.. ';
$htmlbefore = 'kses <a href=5>';
$htmlafter =  'kses <a>';
onetest($htmlbefore, $htmlafter, $score, $max, array('br' => array(),
        'a' => array()));

# Test #7

echo 'Test #7.. ';
$htmlbefore = 'kses <a href=5>';
$htmlafter =  'kses <a href="5">';
onetest($htmlbefore, $htmlafter, $score, $max,
         array('a' => array('href' => 1)));

# Test #8

echo 'Test #8.. ';
$htmlbefore = 'kses <a href>';
$htmlafter =  $htmlbefore;
onetest($htmlbefore, $htmlafter, $score, $max,
        array('a' => array('href' => 1)));

# Test #9

echo 'Test #9.. ';
$htmlbefore = 'kses <a href href=5 href=\'5\' href="5" dummy>';
$htmlafter =  'kses <a href href="5" href=\'5\' href="5">';
onetest($htmlbefore, $htmlafter, $score, $max,
        array('a' => array('href' => 1)));

# Test #10

echo 'Test #10.. ';
$htmlbefore = 'kses <a href="kses\\\\kses">';
$htmlafter =  $htmlbefore;
onetest($htmlbefore, $htmlafter, $score, $max,
        array('a' => array('href' => 1)));

# Test #11

echo 'Test #11.. ';
$htmlbefore = 'kses <a href="xxxxxx">';
$htmlafter =  $htmlbefore;
onetest($htmlbefore, $htmlafter, $score, $max,
        array('a' => array('href' => array('maxlen' => 6))));

# Test #12

echo 'Test #12.. ';
$htmlbefore = 'kses <a href="xxxxxxx">';
$htmlafter =  'kses <a>';
onetest($htmlbefore, $htmlafter, $score, $max,
        array('a' => array('href' => array('maxlen' => 6))));

# Test #13

echo 'Test #13.. ';
$htmlbefore = 'kses <a href="687">';
$htmlafter =  'kses <a>';
onetest($htmlbefore, $htmlafter, $score, $max,
        array('a' => array('href' => array('maxval' => 686))));

# Test #14

echo 'Test #14.. ';
$htmlbefore = 'kses <a href="xx"   /  >';
$htmlafter =  'kses <a href="xx" />';
onetest($htmlbefore, $htmlafter, $score, $max,
        array('a' => array('href' => array('maxlen' => 6))));

# Test #15

echo 'Test #15.. ';
$htmlbefore = 'kses <a href="JAVA java scrIpt : SCRIPT  :  alert(57)">';
$htmlafter =  'kses <a href="alert(57)">';
onetest($htmlbefore, $htmlafter, $score, $max,
        array('a' => array('href' => 1)));

# Test #16

echo 'Test #16.. ';
$htmlbefore = 'kses <a href="htt&#32; &#173;&#Xad;'.chr(173).'P://ulf">';
$htmlafter =  'kses <a href="http://ulf">';
onetest($htmlbefore, $htmlafter, $score, $max,
        array('a' => array('href' => 1)));

# Test #17

echo 'Test #17.. ';
$htmlbefore = 'kses <a href="/start.php"> kses <a href="start.php">';
$htmlafter =  $htmlbefore;
onetest($htmlbefore, $htmlafter, $score, $max,
        array('a' => array('href' => 1)));


# finished

echo "<br>Score $score out of $max\n";

if ($score != $max)
  echo '<br>Something is wrong! Please contact '.
       '<a href="mailto:kses-general@lists.sourceforge.net">'.
       'the kses-general mailing list</a>, and tell us what '.
       "operating system and PHP version you use.\n";

?>

</p>
</body>
</html>
