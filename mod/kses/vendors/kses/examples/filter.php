<?php

# filter - simple example script for kses
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

# *** INCLUDE kses, DEFINE ELEMENTS+ATTRIBUTES, STRIP MAGIC QUOTES ***

include '../kses.php';

$allowed = array('b' => array(),
                 'i' => array(),
                 'a' => array('href'  => array('minlen' => 3, 'maxlen' => 50),
                              'title' => array('valueless' => 'n')),
                 'p' => array('align' => 1,
                              'dummy' => array('valueless' => 'y')),
                 'img' => array('src' => 1), # FIXME
                 'font' => array('size' =>
                                         array('minval' => 4, 'maxval' => 20)),
                 'br' => array());

$val = $_POST['val'];
if (get_magic_quotes_gpc())
  $val = stripslashes($val);

# *** PRINT SOME HTML CODE ***

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>kses example: HTML filter</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>

<body>

<?php

# *** SHOW THE USER'S INPUT ***

?>
<h1>Input</h1>

<pre><?= htmlspecialchars($val); ?></pre>

<?php

# *** SHOW IT AFTER FILTERING ***

?>
<h1>Output</h1>

<pre><?php

$val = kses($val, $allowed, array('http', 'https'));
# The filtering takes place on the line above.
echo htmlspecialchars($val);

?></pre>

<?php

# *** DISPLAY A TEXTAREA FOR THE USER TO TYPE IN ***

?>
<h1>Type something</h1>

<form method="POST" action="filter.php">
<textarea name="val" rows=5 cols=50><?= htmlspecialchars($val); ?></textarea>
<br>
<input type="submit" value="Send it!">
</form>

<?php

# *** SHOW ALLOWED ELEMENTS+ATTRIBUTES ***

?>
<p>
Only the following HTML elements and attributes are allowed:
</p>

<p>
<?php
$first = 1;
foreach ($allowed as $htmlkey => $htmlval)
{
  if (!$first)
    echo ' ';
  $first = 0;

  echo "&lt;$htmlkey"; # element

  foreach ($htmlval as $html2key => $html2val)
    echo " <i>$html2key=</i>"; # attribute

  echo "&gt;";
}

?>

</p>

<p>
&lt;a href=&gt; must have a length in the range 3 to 50.<br>
&lt;a title=&gt; must not be valueless.<br>
&lt;p dummy&gt; must be valueless.<br>
&lt;font size=&gt; must have a value in the range 4 to 20.<br>
Only the URL protocols "http" and "https" are allowed.
</p>

</body>
</html>
