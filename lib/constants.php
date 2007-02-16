<?php

// define all the constants and other global vars that are used by elgglib.

/// Parameter constants - if set then the parameter is cleaned of scripts etc. ///
/**
 * PARAM_RAW specifies a parameter that should contain:
 */
define('PARAM_RAW',      0x0000);

/**
 * PARAM_CLEAN specifies a parameter that should contain:
 */
define('PARAM_CLEAN',    0x0001);

/**
 * PARAM_INT  specifies a parameter that should contain an integer value only.
 */
define('PARAM_INT',      0x0002);

/**
 * PARAM_INTEGER - an alias for PARAM_INT
 */
define('PARAM_INTEGER',  0x0002);

/**
 * PARAM_ALPHA  specifies a parameter that should contain a string type (?).
 */
define('PARAM_ALPHA',    0x0004);

/**
 * PARAM_ACTION - an alias for PARAM_ALPHA
 */
define('PARAM_ACTION',   0x0004);

/**
 * PARAM_FORMAT - an alias for PARAM_ALPHA
 */
define('PARAM_FORMAT',   0x0004);

/**
 * PARAM_NOTAGS specifies a parameter that should contain:
 */
define('PARAM_NOTAGS',   0x0008);

/**
 * PARAM_FILE specifies a parameter that should contain:
 */
define('PARAM_FILE',     0x0010);

/**
 * PARAM_PATH specifies a parameter that should contain:
 */
define('PARAM_PATH',     0x0020);

/**
 * PARAM_HOST specifies a parameter that should contain a fully qualified domain name (FQDN) or an IPv4 dotted quad (IP address)
 */
define('PARAM_HOST',     0x0040);

/**
 * PARAM_URL specifies a parameter that should contain a string in the form of a properly formatted URL.
 */
define('PARAM_URL',      0x0080);

/**
 * PARAM_LOCALURL specifies a parameter that should contain a string in the form of a properly formatted URL as well as one that refers to the local server itself. (NOT orthogonal to the others! Implies PARAM_URL!)
 */
define('PARAM_LOCALURL', 0x0180);

/**
 * PARAM_CLEANFILE specifies a parameter that should contain:
 */
define('PARAM_CLEANFILE',0x0200);

/**
 * PARAM_ALPHANUM specifies a parameter that should contain either numbers or letters only.
 */
define('PARAM_ALPHANUM', 0x0400);

/**
 * PARAM_BOOL specifies a parameter that should contain a 0 or 1 boolean value only. It will convert to value 1 or 0 using empty()
 */
define('PARAM_BOOL',     0x0800);

/**
 * PARAM_CLEANHTML specifies a parameter that should contain actual HTML code that you want cleaned and slashes removed
 */
define('PARAM_CLEANHTML',0x1000);

/**
 * PARAM_ALPHAEXT specifies a parameter that should contain the same contents as PARAM_ALPHA plus the chars in quotes: "/-_" allowed
 */
define('PARAM_ALPHAEXT', 0x2000);

/**
 * PARAM_SAFEDIR specifies a parameter that should contain a safe directory name, suitable for include() and require()
 */
define('PARAM_SAFEDIR',  0x4000);


/// Define text formatting types ... eventually we can add Wiki, BBcode etc

/**
 * Does all sorts of transformations and filtering
 */
define('FORMAT_MOODLE',   '0');   // Does all sorts of transformations and filtering

/**
 * Plain HTML (with some tags stripped)
 */
define('FORMAT_HTML',     '1');   // Plain HTML (with some tags stripped)

/**
 * Plain text (even tags are printed in full)
 */
define('FORMAT_PLAIN',    '2');   // Plain text (even tags are printed in full)

/**
 * Wiki-formatted text
 * Deprecated: left here just to note that '3' is not used (at the moment)
 * and to catch any latent wiki-like text (which generates an error)
 */
define('FORMAT_WIKI',     '3');   // Wiki-formatted text

/**
 * Markdown-formatted text http://daringfireball.net/projects/markdown/
 */
define('FORMAT_MARKDOWN', '4');   // Markdown-formatted text http://daringfireball.net/projects/markdown/


/**
 * Allowed tags - string of html tags that can be tested against for safe html tags
 * @global string $ALLOWED_TAGS
 */
$ALLOWED_TAGS =
'<p><br><b><i><u><font><table><tbody><span><div><tr><td><th><ol><ul><dl><li><dt><dd><h1><h2><h3><h4><h5><h6><hr><img><a><strong><emphasis><em><sup><sub><address><cite><blockquote><pre><strike><acronym><nolink><lang><tex><algebra><math><mi><mn><mo><mtext><mspace><ms><mrow><mfrac><msqrt><mroot><mstyle><merror><mpadded><mphantom><mfenced><msub><msup><msubsup><munder><mover><munderover><mmultiscripts><mtable><mtr><mtd><maligngroup><malignmark><maction><cn><ci><apply><reln><fn><interval><inverse><sep><condition><declare><lambda><compose><ident><quotient><exp><factorial><divide><max><min><minus><plus><power><rem><times><root><gcd><and><or><xor><not><implies><forall><exists><abs><conjugate><eq><neq><gt><lt><geq><leq><ln><log><int><diff><partialdiff><lowlimit><uplimit><bvar><degree><set><list><union><intersect><in><notin><subset><prsubset><notsubset><notprsubset><setdiff><sum><product><limit><tendsto><mean><sdev><variance><median><mode><moment><vector><matrix><matrixrow><determinant><transpose><selector><annotation><semantics><annotation-xml><tt><code>';

/**
 * Allowed protocols - array of protocols that are safe to use in links and so on
 * @global string $ALLOWED_PROTOCOLS
 */
$ALLOWED_PROTOCOLS = array('http', 'https', 'ftp', 'news', 'mailto', 'rtsp', 'teamspeak', 'gopher', 'mms',
                           'color', 'callto', 'cursor', 'text-align', 'font-size', 'font-weight', 'font-style', 
                           'border', 'margin', 'padding');   // CSS as well to get through kses




?>