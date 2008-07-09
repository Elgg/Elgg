<?php

	//	This is a q&d program that shows some of the results of
	//	running KSES.  If you have further questions, check the
	//	current valid email address at http://chaos.org/contact/

	//	Make sure we're in a usable PHP environment
	if(substr(phpversion(), 0, 1) < 4)
	{
		define('KSESTEST_VER', 0);
	}
	elseif(substr(phpversion(), 0, 1) >= 5)
	{
		define('KSESTEST_VER', 5);
	}
	else
	{
		define('KSESTEST_VER', 4);
	}

	//	See if we're in command line or web
	if($_SERVER["DOCUMENT_ROOT"] == "")
	{
		define('KSESTEST_ENV', 'CLI');
	}
	else
	{
		define('KSESTEST_ENV', 'WEB');
	}

	if(KSESTEST_VER == 0)
	{
		$message = array(
			"Error: Not using a current version of PHP!",
			"You are using PHP version " . phpversion() . ".",
			"KSES Class version requires PHP4 or better.",
			"KSES test program ending."
			);

		displayPage(
			array("title" => "Error running KSES test", "message" => $message)
		);

		exit();
	}

	$include_file = "php" . KSESTEST_VER . ".class.kses.php";
	if(file_exists($include_file) && is_readable($include_file))
	{
		include_once($include_file);
	}
	else
	{
		$message = array(
			"Error:  Unable to find '" . $include_file . "'.",
			"Please check your include path and make sure the file is available.",
			"Path: " . ini_get('include_path')
		);

		displayPage(
			array('title' => 'Unable to include ' . $include_file, 'message' => $message)
		);

		exit();
	}

	$kses_type = "kses" . KSESTEST_VER;
	$myKses = new $kses_type;

	$test_text = array();
	$test_text = test1_protocols($myKses);
	$test_text = array_merge($test_text, test1_html($myKses));
	$test_text = array_merge($test_text, test1_kses($myKses));

	displayPage(
		array('title' => 'New Test', 'message' => $test_text)
	);

	function test1_kses(&$myKses)
	{
		$out = array(output_hr(), "Testing current configuration");

		$test_tags = array(
			'<a href="http://www.chaos.org/">www.chaos.org</a>',
			'<a name="X">Short \'a name\' tag</a>',
			'<td colspan="3" rowspan="5">Foo</td>',
			'<td rowspan="2" class="mugwump" style="background-color: rgb(255, 204 204);">Bar</td>',
			'<td nowrap>Very Long String running to 1000 characters...</td>',
			'<td bgcolor="#00ff00" nowrap>Very Long String with a blue background</td>',
			'<a href="proto1://www.foo.com">New protocol test</a>',
			'<img src="proto2://www.foo.com" />',
			'<a href="javascript:javascript:javascript:javascript:javascript:alert(\'Boo!\');">bleep</a>',
			'<a href="proto4://abc.xyz.foo.com">Another new protocol</a>',
			'<a href="proto9://foo.foo.foo.foo.foo.org/">Test of "proto9"</a>',
			'<td width="75">Bar!</td>',
			'<td width="200">Long Cell</td>'
		);

		$out_li = array();
		// Keep only allowed HTML from the presumed 'form'.
		foreach($test_tags as $tag)
		{
			$temp  = $myKses->Parse($tag);
			$check = ($temp == $tag) ? true : false;
			$text  = ($temp == $tag) ? 'pass' : 'fail';

			$li_text  = output_testresult($check, $text) . output_newline();
			$li_text .= "Input: &nbsp;" . output_translate($tag) . output_newline();
			$li_text .= "Output: " . output_translate($temp);
			if(KSESTEST_ENV == 'CLI')
			{
				$li_text .= output_newline();
			}

			array_push($out_li, output_code_wrap($li_text));
		}

		$out = array_merge($out, array(output_ul($out_li)));
		array_push($out, output_hr());
		array_push($out, "Testing is now finished.");
		return $out;
	}

	function output_code_wrap($text)
	{
		if(KSESTEST_ENV == 'CLI')
		{
			return $text;
		}
		else
		{
			return "<code>\n$text<code>\n";
		}
	}

	function output_translate($text)
	{
		if(KSESTEST_ENV == 'CLI')
		{
			return $text;
		}
		else
		{
			return htmlentities($text);
		}
	}

	function output_testresult($pass = false, $text = "")
	{
		if(KSESTEST_ENV == 'CLI')
		{
			return '[' . $text . ']';
		}
		else
		{
			if($pass == true)
			{
				return '<span style="color: green;">[' . $text . ']</span>';
			}
			else
			{
				return '<span style="color: red;">[' . $text . ']</span>';
			}
		}
	}

	function output_spaces()
	{
		if(KSESTEST_ENV == 'WEB')
		{
			$out = "&nbsp;&nbsp;&nbsp;";
		}
		else
		{
			$out = "   ";
		}

		return $out;
	}

	function output_newline()
	{
		if(KSESTEST_ENV == 'WEB')
		{
			$out = "<br />\n";
		}
		else
		{
			$out = "\n";
		}

		return $out;
	}

	function displayPage($data = array())
	{
		$title   = ($data['title'] == '') ? 'No title' : $data['title'];
		$message = ($data['message'] == '') ? array('No message') : $data['message'];

		$out = "";

		foreach($message as $text)
		{
			if(KSESTEST_ENV == 'WEB')
			{
				$header = "\t\t<h1>$title</h1>\n\t\t<hr />\n";
				$out .= "\t\t<p>\n";
				$out .= "\t\t\t$text\n";
				$out .= "\t\t</p>\n";
			}
			else
			{
				$header = "$title\n" . str_repeat('-', 60) . "\n\n";
				$out .= "\t$text\n\n";
			}
		}

		if(KSESTEST_ENV == 'WEB')
		{
			echo "<html>\n";
			echo "\t<head>\n";
			echo "\t\t<title>$title</title>\n";
			echo "\t</head>\n";
			echo "\t<body>\n";
			echo $header;
			echo $out;
			echo "\t</body>\n";
			echo "</html>\n";
		}
		else
		{
			echo $header;
			echo $out;
		}
	}

	function output_hr()
	{
		if(KSESTEST_ENV == 'WEB')
		{
			return "\t\t\t<hr />\n";
		}
		else
		{
			return str_repeat(60, '-') . "\n";
		}
	}

	function output_ul($data = array(), $padding = "")
	{
		if(!is_array($data) || count($data) < 1)
		{
			return "";
		}

		$text = "";
		if(KSESTEST_ENV == 'WEB')
		{
			$text = "\t\t\t<ul>\n";
			foreach($data as $li)
			{
				$text .= "\t\t\t\t<li>$li</li>\n";
			}
			$text .= "\t\t\t</ul>\n";
		}
		else
		{
			foreach($data as $li)
			{
				$text .= $padding . "   * $li\n";
			}
		}

		return $text;
	}

	function test1_protocols(&$myKses)
	{
		$default_prots = $myKses->dumpProtocols();
		$out_text = array();
		if(count($default_prots) > 0)
		{
			array_push($out_text, "Initial protocols from KSES" . KSESTEST_VER . ":");
			array_push($out_text, output_ul($default_prots));
			array_push($out_text, output_hr());
		}

		$myKses->AddProtocols(array("proto1", "proto2:", "proto3"));   // Add a list of protocols
		$myKses->AddProtocols("proto4:");  // Add a single protocol (Note ':' is optional at end)
		$myKses->AddProtocol("proto9", "mystery:", "anarchy");
		$myKses->AddProtocol("alpha", "beta", "gamma:");

		$add_protocol  = "\t\t\t<ol>\n";
		$add_protocol .= "\t\t\t\t" . '<li>$myKses->AddProtocols(array("proto1", "proto2:", "proto3"));</li>' . "\n";
		$add_protocol .= "\t\t\t\t" . '<li>$myKses->AddProtocols("proto4:");</li>' . "\n";
		$add_protocol .= "\t\t\t\t" . '<li>$myKses->AddProtocols("proto4:");</li>' . "\n";
		$add_protocol .= "\t\t\t\t" . '<li>$myKses->AddProtocol("proto9", "mystery:", "anarchy");</li>' . "\n";
		$add_protocol .= "\t\t\t\t" . '<li>$myKses->AddProtocol("alpha", "beta", "gamma:");</li>' . "\n";
		$add_protocol .= "\t\t\t</ol>\n";

		array_push($out_text, $add_protocol);

		$new_prots = $myKses->dumpProtocols();
		if(count($new_prots) > 0)
		{
			array_push($out_text, "New protocols from KSES" . KSESTEST_VER . " after using AddProtocol(s):");
			array_push($out_text, output_ul($new_prots));
			array_push($out_text, output_hr());
		}

		$myKses->RemoveProtocols(array("mystery", "anarchy:"));
		$myKses->RemoveProtocols("alpha:");
		$myKses->RemoveProtocol("beta:");
		$myKses->RemoveProtocol("gamma");

		$remove_protocol  = "\t\t\t<ol>\n";
		$remove_protocol .= "\t\t\t\t" . '<li>$myKses->RemoveProtocols(array("mystery", "anarchy:"));</li>' . "\n";
		$remove_protocol .= "\t\t\t\t" . '<li>$myKses->RemoveProtocols("alpha:");</li>' . "\n";
		$remove_protocol .= "\t\t\t\t" . '<li>$myKses->RemoveProtocol("beta:");</li>' . "\n";
		$remove_protocol .= "\t\t\t\t" . '<li>$myKses->RemoveProtocol("gamma");</li>' . "\n";
		$remove_protocol .= "\t\t\t</ol>\n";
		array_push($out_text, $remove_protocol);

		$new_prots = $myKses->dumpProtocols();
		if(count($new_prots) > 0)
		{
			array_push($out_text, "Resulting protocols from KSES" . KSESTEST_VER . " after using RemoveProtocol(s):");
			array_push($out_text, output_ul($new_prots));
			array_push($out_text, output_hr());
		}

		$myKses->SetProtocols(array("https", "gopher", "news"));
		$set_protocol  = "\t\t\t<ol>\n";
		$set_protocol .= "\t\t\t\t" . '<li>$myKses->SetProtocols(array("https", "gopher", "news"));</li>' . "\n";
		$set_protocol .= "\t\t\t</ol>\n";
		array_push($out_text, $set_protocol);

		$new_prots = $myKses->dumpProtocols();
		if(count($new_prots) > 0)
		{
			array_push($out_text, "Resulting protocols from KSES" . KSESTEST_VER . " after using SetProtocols:");
			array_push($out_text, output_ul($new_prots));
			array_push($out_text, output_hr());
		}

		//	Invisible reset
		$myKses->SetProtocols(array("http", "proto1", "proto2", "proto9"));

		return $out_text;
	}

	function test1_html(&$myKses)
	{
		$out = array();

		//	Allows <p>|</p> tag
		$myKses->AddHTML("p");

		//	Allows 'a' tag with href|name attributes,
		//	href has minlen of 10 chars, and maxlen of 25 chars
		//	name has minlen of  2 chars
		$myKses->AddHTML(
			"a",
			array(
				"href" => array('maxlen' => 25, 'minlen' => 10),
				"name" => array('minlen' => 2)
			)
		);

		//	Allows 'td' tag with colspan|rowspan|class|style|width|nowrap attributes,
		//		colspan has minval of   2       and maxval of 5
		//		rowspan has minval of   3       and maxval of 6
		//		class   has minlen of   1 char  and maxlen of   10 chars
		//		style   has minlen of  10 chars and maxlen of 100 chars
		//		width   has maxval of 100
		//		nowrap  is valueless
		$myKses->AddHTML(
			"td",
			array(
				"colspan" => array('minval' =>   2, 'maxval' =>   5),
				"rowspan" => array('minval' =>   3, 'maxval' =>   6),
				"class"   => array("minlen" =>   1, 'maxlen' =>  10),
				"width"   => array("maxval" => 100),
				"style"   => array('minlen' =>  10, 'maxlen' => 100),
				"nowrap"  => array('valueless' => 'y')
			)
		);

		array_push($out, "Modifying HTML Tests:");
		$code_text  = "<pre>\n";
		$code_text .= "      //   Allows &lt;p&gt;|&lt;/p&gt; tag\n";
		$code_text .= "      \$myKses-&gt;AddHTML(\"p\");\n";
		$code_text .= "\n";
		$code_text .= "      //   Allows 'a' tag with href|name attributes,\n";
		$code_text .= "      //   href has minlen of 10 chars, and maxlen of 25 chars\n";
		$code_text .= "      //   name has minlen of  2 chars\n";
		$code_text .= "      \$myKses-&gt;AddHTML(\n";
		$code_text .= "         \"a\",\n";
		$code_text .= "         array(\n";
		$code_text .= "            \"href\" =&gt; array('maxlen' =&gt; 25, 'minlen' =&gt; 10),\n";
		$code_text .= "            \"name\" =&gt; array('minlen' =&gt; 2)\n";
		$code_text .= "         )\n";
		$code_text .= "      );\n";
		$code_text .= "\n";
		$code_text .= "      //   Allows 'td' tag with colspan|rowspan|class|style|width|nowrap attributes,\n";
		$code_text .= "      //      colspan has minval of   2       and maxval of 5\n";
		$code_text .= "      //      rowspan has minval of   3       and maxval of 6\n";
		$code_text .= "      //      class   has minlen of   1 char  and maxlen of   10 chars\n";
		$code_text .= "      //      style   has minlen of  10 chars and maxlen of 100 chars\n";
		$code_text .= "      //      width   has maxval of 100\n";
		$code_text .= "      //      nowrap  is valueless\n";
		$code_text .= "      \$myKses-&gt;AddHTML(\n";
		$code_text .= "         \"td\",\n";
		$code_text .= "         array(\n";
		$code_text .= "            \"colspan\" =&gt; array('minval' =&gt;   2, 'maxval' =&gt;   5),\n";
		$code_text .= "            \"rowspan\" =&gt; array('minval' =&gt;   3, 'maxval' =&gt;   6),\n";
		$code_text .= "            \"class\"   =&gt; array(\"minlen\" =&gt;   1, 'maxlen' =&gt;  10),\n";
		$code_text .= "            \"width\"   =&gt; array(\"maxval\" =&gt; 100),\n";
		$code_text .= "            \"style\"   =&gt; array('minlen' =&gt;  10, 'maxlen' =&gt; 100),\n";
		$code_text .= "            \"nowrap\"  =&gt; array('valueless' =&gt; 'y')\n";
		$code_text .= "         )\n";
		$code_text .= "      );\n";
		$code_text .= "</pre>\n";

		array_push($out, $code_text);
		array_push($out, output_hr());
		array_push($out, "Net results:");

		$out_elems = $myKses->DumpElements();
		if(count($out_elems) > 0)
		{
			//array_push($out, "\t\t\t<ul>\n");
			foreach($out_elems as $tag => $attr_data)
			{
				$out_li_elems = array();
				$elem_text = "(X)HTML element $tag";
				$allow = "";
				if(isset($attr_data) && is_array($attr_data) && count($attr_data) > 0)
				{
					$allow = " allows attribute";
					if(count($attr_data) > 1)
					{
						$allow .= "s";
					}
					$allow .= ":\n";
				}

				array_push($out_li_elems, "$elem_text$allow");

				$attr_test_li = array();
				if(isset($attr_data) && is_array($attr_data) && count($attr_data) > 0)
				{
					foreach($attr_data as $attr_name => $attr_tests)
					{
						$li_text = $attr_name;
						if(isset($attr_tests) && count($attr_tests) > 0)
						{
							foreach($attr_tests as $test_name => $test_val)
							{
								switch($test_name)
								{
									case "maxlen":
										$li_text .= " - maximum length of '" . $test_val . "' characters";
										break;
									case "minlen":
										$li_text .= " - minimum length of '" . $test_val . "' characters";
										break;
									case "minval":
										$li_text .= " - minimum value of '" . $test_val . "'";
										break;
									case "maxval":
										$li_text .= " - maximum value of '" . $test_val . "'";
										break;
									case "valueless":
										switch(strtolower($test_val))
										{
											case 'n':
												$li_text .= " - must not be valueless";
												break;
											case 'y':
												$li_text .= " - must be valueless";
												break;
											default:
												break;
										}
										break;
									default:
										break;
								}
							}
						}
						array_push($attr_test_li, $li_text);
					}
					if(count($attr_test_li) > 0)
					{
						$attr_test_li = output_ul($attr_test_li, "   ");
						$out_li_elems = array("$elem_text$allow$attr_test_li");
					}
				}
				$out = array_merge($out, $out_li_elems);
			}
		}

		return $out;
	}

?>