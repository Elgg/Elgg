<?php

	// XSL template
	
	require("../includes.php");
	header("Content-type: text/xml");
	
	global $page_owner;

	$title = gettext("RSS Management");
	$subelgg = gettext("To add this feed to your resources page, click here.");
	$subother = gettext("To add this feed to an external RSS aggregator, click the link that corresponds to your aggregator:");
       $gubbins = gettext("Summary");
	$headertitle = gettext("This is an RSS feed for ");
       $exit = gettext("Didn't mean to come here? Click to exit!");

	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		
?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
	<html>
        <head>
        <title><?php echo $title; ?></title>
        <style type="text/css">

body{
	padding: 0;
	font-family: arial, verdana, helvetica, sans-serif;
	color: #333;
	background: #fff;
	width:100%;
	margin:auto;
	font-size:80%;
	}

a {
		text-decoration: none;
		color: #7289AF;
		font-family:verdana, arial, helvetica, sans-serif;
		font-size:100%;

	}

p {
	font-size: 100%;
}

h1 {
	margin:0px 0px 15px 0px;
	padding:0px;
	font-size:120%;
	font-weight:900;
}


h2 {
	margin:0px 0px 5px 0px;
	padding:0px;
	font-size:100%
}


h3 {
	margin:0px 0px 5px 0px;
	padding:0px;
	font-size:100%
}

h4 {
	margin:0px 0px 5px 0px;
	padding:0px;
	font-size:100%
}

h5 {
	margin:0px 0px 5px 0px;
	padding:0px;
	color:#1181AA;
	background:#fff;
	font-size:100%
}

img {
  border:0px;
}

blockquote {
	padding: 0 1pc 1pc 1pc;
	border: 1px solid #ddd;
       background-color: #F0F0F0;
	color:#000;
	background-image: url("/_templates/double-quotes.png");
	background-repeat: no-repeat;
	background-position: -10px -7px;
}

/*---------------------------------------
Wraps the entire page 
-----------------------------------------*/

#container {
	margin: 0 auto;
	width: 100%;
	min-width: 750px;
     	}

/*---------------------------------------------
HEADER 
------------------------------------------------*/

#header {
	width: 100%;
	background-color: #1181AA; 
	color: #333333;
	border: 0px solid #ccc;
	border-bottom: 4px solid #FAC83D;
	padding: 0px;
	margin: 0px;
	text-align: left;
	}

#header h1 {
	padding: 0 0 4px 0;
	margin: 0px 0 0 10px;
	background-color: #1181AA;
	color: #fff;
	text-align: left;
	font-size:140%;
	font-weight:normal;
	}

#header h2 {
	padding: 0 0 7px 0;
	margin: 0 0 0 20px;
	font-weight: normal;
	background-color: #1181AA; 
	color: #fff;
	border: none;
	font-family: "Lucida Grande", arial, sans-serif;
	font-size:120%;
	}

#intro {
   padding:10px;
}

#actions {
   padding:10px;
   text-align: center;
}

#actions p {
   text-align: center;
}

#external {
   padding:10px;
}

#gubbins {
   padding:10px;
   background:#fff;
}

#subscribe {
width: 307px;
height: 91px;
       /*border:1px solid #4473A1;*/
      /*padding:15px 0;*/
       text-align:center;
       font-size:1.15em;
      font-weight:bold;
     font-size:140%;
    /* -moz-border-radius: 0.5em;*/
}

#subscribe a {
  background-image: url(/_rss/subscribe.png);
  background-repeat: no-repeat;
  display: block; width: 307px; height: 91px; padding:15px 0 0 0;
}

#subscribe a:hover {
   background: url(/_rss/subscribe-high.png);
   background-repeat: no-repeat;
   display: block; width: 307px; height: 91px;  padding:15px 0 0 0;
}

#mistake {
      width: 307px;
      height:91px;
       /*border:1px solid #E504BD; */
       text-align:center;
       font-size:1.15em;
      font-weight:bold;
     font-size:140%;
   /*   -moz-border-radius: 0.5em; */
 }

#mistake a {
   background-image: url(/_rss/mistake.png);
   background-repeat: no-repeat;
  display: block; width: 307px; height: 91px; padding:15px 0 0 0;
}

#mistake a:hover {
   background: url(/_rss/mistake-high.png);
   background-repeat: no-repeat;
   display: block; width: 307px; height: 91px; padding:15px 0 0 0;

}


</style>
     	 </head>
		<body>
<?php

		$url = url . $_GET['url'];
		$rssurl = url . $_GET['rssurl'];

		$output = <<< END
               <div id="header"><!-- start header -->
		<h1>$headertitle <xsl:value-of select="rss/channel/title"/></h1>
	        </div><!-- end header -->
		
END;
		
		if (logged_on) {

		$subscribeurl = url . "_rss/subscriptions.php?profile_name=" . $_SESSION['username'];
		
		$output .= <<< END
		
		<div id="actions">    
		<p>
		     <div id="subscribe"><a href="{$subscribeurl}&amp;url=$rssurl&amp;action=subscribe-new">$subelgg</a></div>
		</p>
              
		<p>
			<div id="mistake"><a href="javascript:history.go(-1)">$exit</a></div>
		</p>
              </div>
		
END;

		}
		
		$output .= <<< END
		
		<div id="external">
		<p>
			$subother
		</p>
		<ul>
			<li><a href="http://www.bloglines.com/sub/$rssurl"><img src="http://solosub.com/img/bloglines.png" alt="Bloglines" /></a></li><li> 
			<a href="http://add.my.yahoo.com/rss?url=$rssurl"><img src="http://us.i1.yimg.com/us.yimg.com/i/us/my/addtomyyahoo4.gif" alt="My Yahoo" /></a> </li><li> 
			<a href="http://my.msn.com/addtomymsn.armx?id=rss&amp;ut=$rssurl&amp;tt=CENTRALDIRECTORY&amp;ru=http://rss.msn.com'"><img src="http://solosub.com/img/mymsn.gif" alt="MyMSN" /></a></li><li> 
			<a href="http://www.newsgator.com/ngs/subscriber/subext.aspx?url=$rssurl"><img src="http://www.newsgator.com/images/ngsub1.gif" alt="Newsgator" /></a> </li>
			<li><a target="_blank" href="http://solosub.com/sub/$rssurl">Syndicate in any reader via <img src="http://solosub.com/feed_button.gif" alt="SoloSub" /></a></li>
		</ul>
              </div>
		<hr />
       <div id="gubbins">
       <h2>$gubbins</h2>
    	<h3><xsl:value-of select="rss/channel/title"/></h3>
    	<h4><xsl:value-of select="rss/channel/description"/></h4>
       <ul>
    		<xsl:for-each select="rss/channel/item">
    			<li>
    				<p><xsl:value-of select="title"/>
    				  <a><xsl:attribute name="href">
       					<xsl:value-of select="link"/></xsl:attribute> 
       					&gt;&gt;
       					</a></p>
       				<xsl:for-each select="enclosure">
					<p>File:
						<a><xsl:attribute name="href">
							<xsl:value-of select="@url"/></xsl:attribute> 
							<xsl:value-of select="@url"/>
						</a> (<xsl:value-of select="@type"/>)
					</p>
       				</xsl:for-each>
    			</li>
    		</xsl:for-each>
    	</ul>
    	</div>
    	
END;

		echo $output;

?>
	</body>
	</html>
</xsl:template>

</xsl:stylesheet>