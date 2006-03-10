<?php

	// Determines whether or not a file should be displayed inline (false if not, the mime-type if true)
	// $parameter = the file location
				
	// This needs to be improved to actually look at file contents instead of extension:
	
	// Get file extension
			$run_result = false;
			$extension = substr($parameter, strrpos($parameter, "."));

	// If it's known, set the mimetype
		switch(strtolower($extension)) {
			case ".mp3":	$run_result = "audio/mpeg"; break;
			case ".ai":		$run_result = "application/postscript"; break;
			
			case ".aif":	$run_result = "audio/x-aiff"; break;
			
			case ".aifc":	$run_result = "audio/x-aiff"; break;
			
			case ".aiff":	$run_result = "audio/x-aiff"; break;
			
			case ".asf":	$run_result = "video/x-ms-asf"; break;
			
			case ".asx":	$run_result = "video/x-ms-asf"; break;
			
			case ".au":		$run_result = "audio/basic"; break;
			
			case ".avi":	$run_result = "video/x-msvideo"; break;
			
			case ".bcpio":	$run_result = "application/x-bcpio"; break;
			
			case ".bmp":	$run_result = "image/x-xbitmap"; break;
			
			case ".cdf":	$run_result = "application/x-netcdf"; break;
			
			case ".cpio":	$run_result = "application/x-cpio"; break;
			
			case ".cpt":	$run_result = "application/mac-compactpro"; break;
			
			case ".csh":	$run_result = "application/x-csh"; break;
			
			case ".css":	$run_result = "text/css"; break;
			
			case ".dcr":	$run_result = "application/x-director"; break;
			
			case ".dir":	$run_result = "application/x-director"; break;
			
			case ".doc":	$run_result = "application/msword"; break;
			
			case ".dtd":	$run_result = "text/xml"; break;
			
			case ".dvi":	$run_result = "application/x-dvi"; break;
			
			case ".dxr":	$run_result = "application/x-director"; break;
			
			case ".eps":	$run_result = "application/postscript"; break;
			
			case ".etx":	$run_result = "text/x-setext"; break;
			
			case ".evy":	$run_result = "application/x-envoy"; break;
			
			case ".fif":	$run_result = "application/fractals"; break;
			
			case ".gif":	$run_result = "image/gif"; break;
			
			case ".gtar":	$run_result = "application/x-gtar"; break;
			
			case ".gz":	$run_result = "application/x-gzip"; break;
			
			case ".hdf":	$run_result = "application/x-hdf"; break;
			
			case ".hpx":	$run_result = "application/mac-binhex40"; break;
			
			case ".hqx":	$run_result = "application/mac-binhex40"; break;
			
			case ".htm":	$run_result = "text/html"; break;
			
			case ".html":	$run_result = "text/html"; break;
			
			case ".ice":	$run_result = "x-conference/x-cooltalk"; break;
			
			case ".ief":	$run_result = "image/ief"; break;
			
			case ".iges":	$run_result = "model/iges"; break;
			
			case ".igs":	$run_result = "model/iges"; break;
			
			case ".isv":	$run_result = "bws-internal/intrasrv-urlencoded"; break;
			
			case ".jfm":	$run_result = "bws-internal/intrasrv-form"; break;
			
			case ".jpe":	$run_result = "image/jpeg"; break;
			
			case ".jpeg":	$run_result = "image/jpeg"; break;
			
			case ".jpg":	$run_result = "image/jpeg"; break;
			
			case ".jrp":	$run_result = "bws-internal/intrasrv-report"; break;
			
			case ".js":	$run_result = "application/x-javascript"; break;
			
			case ".kar":	$run_result = "audio/midi"; break;
			
			case ".latex":	$run_result = "application/x-latex"; break;
			
			case ".ls":	$run_result = "application/x-javascript"; break;
			
			case ".man":	$run_result = "application/x-troff-man"; break;
			
			case ".me":	$run_result = "application/x-troff-me"; break;
			
			case ".mesh":	$run_result = "model/mesh"; break;
			
			case ".mid":	$run_result = "audio/midi"; break;
			
			case ".midi":	$run_result = "audio/midi"; break;
			
			case ".mif":	$run_result = "application/x-mif"; break;
			
			case ".mocha":	$run_result = "application/x-javascript"; break;
			
			case ".mov":	$run_result = "video/quicktime"; break;
			
			case ".movie":	$run_result = "video/x-sgi-movie"; break;
			
			case ".mp2":	$run_result = "audio/mpeg"; break;
			
			case ".mp3":	$run_result = "audio/mpeg"; break;
			
			case ".mpe":	$run_result = "video/mpeg"; break;
			
			case ".mpeg":	$run_result = "video/mpeg"; break;
			
			case ".mpg":	$run_result = "video/mpeg"; break;
			
			case ".mpga":	$run_result = "audio/mpeg"; break;
			
			case ".ms":	$run_result = "application/x-troff-ms"; break;
			
			case ".msh":	$run_result = "model/mesh"; break;
			
			case ".nc":	$run_result = "application/x-netcdf"; break;
			
			case ".oda":	$run_result = "application/oda"; break;
			
			case ".pac":	$run_result = "application/x-ns-proxy-autoconfig"; break;
			
			case ".pbm":	$run_result = "image/x-portable-bitmap"; break;
			
			case ".pdb":	$run_result = "chemical/x-pdb"; break;
			
			case ".pdf":	$run_result = "application/pdf"; break;
			
			case ".pgm":	$run_result = "image/x-portable-graymap"; break;
			
			case ".php3":	$run_result = "application/x-httpd-php3"; break;
			
			case ".msql2":	$run_result = "application/x-httpd-php-msql2"; break;
			
			case ".phtml":	$run_result = "application/x-httpd-php"; break;
			
			case ".png":	$run_result = "image/png"; break;
			
			case ".pnm":	$run_result = "image/x-portable-anymap"; break;
			
			case ".ppm":	$run_result = "image/x-portable-pixmap"; break;
			
			case ".ppt":	$run_result = "application/powerpoint"; break;
			
			case ".ps":	$run_result = "application/postscript"; break;
			
			case ".qt":	$run_result = "video/quicktime"; break;
			
			case ".ra":	$run_result = "audio/x-realaudio"; break;
			
			case ".ram":	$run_result = "audio/x-pn-realaudio"; break;
			
			case ".ras":	$run_result = "image/x-cmu-raster"; break;
			
			case ".rgb":	$run_result = "image/x-rgb"; break;
			
			case ".roff":	$run_result = "application/x-troff"; break;
			
			case ".rpm":	$run_result = "audio/x-pn-realaudio-plugin"; break;
			
			case ".rtf":	$run_result = "application/rtf"; break;
			
			case ".rtx":	$run_result = "text/richtext"; break;
			
			case ".sgm":	$run_result = "text/x-sgml"; break;
			
			case ".sgml":	$run_result = "text/x-sgml"; break;
			
			case ".sh":	$run_result = "application/x-sh"; break;
			
			case ".shar":	$run_result = "application/x-shar"; break;
			
			case ".silo":	$run_result = "model/mesh"; break;
			
			case ".sit":	$run_result = "application/stuffit"; break;
			
			case ".sit":	$run_result = "application/x-stuffit"; break;
			
			case ".skd":	$run_result = "application/x-koan"; break;
			
			case ".skm":	$run_result = "application/x-koan"; break;
			
			case ".skp":	$run_result = "application/x-koan"; break;
			
			case ".skt":	$run_result = "application/x-koan"; break;
			
			case ".snd":	$run_result = "audio/basic"; break;
			
			case ".src":	$run_result = "application/x-wais-source"; break;
			
			case ".sv4cpio":	$run_result = "application/x-sv4cpio"; break;
			
			case ".sv4crc":	$run_result = "application/x-sv4crc"; break;
			
			case ".swf":	$run_result = "application/x-shockwave-flash"; break;
			
			case ".t":	$run_result = "application/x-troff"; break;
			
			case ".tar":	$run_result = "application/x-tar"; break;
			
			case ".tcl":	$run_result = "application/x-tcl"; break;
			
			case ".tex":	$run_result = "application/x-tex"; break;
			
			case ".texi":	$run_result = "application/x-texinfo"; break;
			
			case ".texi":	$run_result = "application/x-textinfo"; break;
			
			case ".texinfo":	$run_result = "application/x-textinfo"; break;
			
			case ".text":	$run_result = "text/plain"; break;
			
			case ".tif":	$run_result = "image/tiff"; break;
			
			case ".tiff":	$run_result = "image/tiff"; break;
			
			case ".tr":	$run_result = "application/x-troff"; break;
			
			case ".tsp":	$run_result = "application/dsptype"; break;
			
			case ".tsv":	$run_result = "text/tab-separated-values"; break;
			
			case ".txt":	$run_result = "text/plain"; break;
			
			case ".ustar":	$run_result = "application/x-ustar"; break;
			
			case ".vcd":	$run_result = "application/x-cdlink"; break;
			
			case ".vox":	$run_result = "audio/voxware"; break;
			
			case ".vrml":	$run_result = "model/vrml"; break;
			
			case ".wav":	$run_result = "audio/x-wav"; break;
			
			case ".wax":	$run_result = "audio/x-ms-wax"; break;
			
			case ".wm":	$run_result = "video/x-ms-wm"; break;
			
			case ".wma":	$run_result = "audio/x-ms-wma"; break;
			
			case ".wmd":	$run_result = "application/x-ms-wmd"; break;
			
			case ".wmv":	$run_result = "video/x-ms-wmv"; break;
			
			case ".wmx":	$run_result = "video/x-ms-wmx"; break;
			
			case ".wmz":	$run_result = "application/x-ms-wmz"; break;
			
			case ".wrl":	$run_result = "model/vrml"; break;
			
			case ".wvx":	$run_result = "video/x-ms-wvx"; break;
			
			case ".xbm":	$run_result = "image/x-xbitmap"; break;
			
			case ".xml":	$run_result = "text/xml"; break;
			
			case ".xpm":	$run_result = "image/x-xpixmap"; break;
			
			case ".xwd":	$run_result = "image/x-xwindowdump"; break;
			
			case ".xyz":	$run_result = "chemical/x-pdb"; break;
			
			case ".z":	$run_result = "application/x-compress"; break;
			
			case ".zip":	$run_result = "application/zip"; break;
		}

?>