<?php
	/**
	 * Elgg kses tag filtering.
	 * 
	 * @package ElggKses
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise kses
	 *
	 */
	function kses_init()
	{
		/** For now declare allowed tags and protocols here, TODO: Make this configurable */
		global $CONFIG;
		$CONFIG->kses_allowedtags = array(
		'address' => array(),
		'a' => array(
			'class' => array (),
			'href' => array (),
			'id' => array (),
			'title' => array (),
			'rel' => array (),
			'rev' => array (),
			'name' => array (),
			'target' => array()),
		'abbr' => array(
			'class' => array (),
			'title' => array ()),
		'acronym' => array(
			'title' => array ()),
		'b' => array(),
		'big' => array(),
		'blockquote' => array(
			'id' => array (),
			'cite' => array (),
			'class' => array(),
			'lang' => array(),
			'xml:lang' => array()),
		'br' => array (
			'class' => array ()),
		'button' => array(
			'disabled' => array (),
			'name' => array (),
			'type' => array (),
			'value' => array ()),
		'caption' => array(
			'align' => array (),
			'class' => array ()),
		'cite' => array (
			'class' => array(),
			'dir' => array(),
			'lang' => array(),
			'title' => array ()),
		'code' => array (),
//			'style' => array()),
//		'col' => array(
//			'align' => array (),
//			'char' => array (),
//			'charoff' => array (),
//			'span' => array (),
//			'dir' => array(),
//			'style' => array (),
//			'valign' => array (),
//			'width' => array ()),
		'del' => array(
			'datetime' => array ()),
		'dd' => array(),
		'div' => array(
			'align' => array (),
			'class' => array (),
			'dir' => array (),
			'lang' => array(),
//			'style' => array (),
			'xml:lang' => array()),
		'dl' => array(),
		'dt' => array(),
		'em' => array(),
//		'fieldset' => array(),
		'font' => array(
			'color' => array (),
			'face' => array (),
			'size' => array ()),
//		'form' => array(
//			'action' => array (),
//			'accept' => array (),
//			'accept-charset' => array (),
//			'enctype' => array (),
//			'method' => array (),
//			'name' => array (),
//			'target' => array ()),
		'h1' => array(
			'align' => array (),
			'class' => array ()),
		'h2' => array(
			'align' => array (),
			'class' => array ()),
		'h3' => array(
			'align' => array (),
			'class' => array ()),
		'h4' => array(
			'align' => array (),
			'class' => array ()),
		'h5' => array(
			'align' => array (),
			'class' => array ()),
		'h6' => array(
			'align' => array (),
			'class' => array ()),
		'hr' => array(
			'align' => array (),
			'class' => array (),
			'noshade' => array (),
			'size' => array (),
			'width' => array ()),
		'i' => array(),
		'img' => array(
			'alt' => array (),
			'align' => array (),
			'border' => array (),
			'class' => array (),
			'height' => array (),
			'hspace' => array (),
			'longdesc' => array (),
			'vspace' => array (),
			'src' => array (),
//			'style' => array (),
			'width' => array ()),
		'ins' => array(
			'datetime' => array (),
			'cite' => array ()),
		'kbd' => array(),
		'label' => array(
			'for' => array ()),
		'legend' => array(
			'align' => array ()),
		'li' => array (
			'align' => array (),
			'class' => array ()),
		'p' => array(
			'class' => array (),
			'align' => array (),
			'dir' => array(),
			'lang' => array(),
//			'style' => array (),
			'xml:lang' => array()),
		'pre' => array(
//			'style' => array(),
			'width' => array ()),
		'q' => array(
			'cite' => array ()),
		's' => array(),
		'span' => array (
			'class' => array (),
			'dir' => array (),
			'align' => array (),
			'lang' => array (),
//			'style' => array (),
			'title' => array (),
			'xml:lang' => array()),
		'strike' => array(),
		'strong' => array(),
		'sub' => array(),
		'sup' => array(),
//		'table' => array(
//			'align' => array (),
//			'bgcolor' => array (),
//			'border' => array (),
//			'cellpadding' => array (),
//			'cellspacing' => array (),
//			'class' => array (),
//			'dir' => array(),
//			'id' => array(),
//			'rules' => array (),
//			'style' => array (),
//			'summary' => array (),
//			'width' => array ()),
//		'tbody' => array(
//			'align' => array (),
//			'char' => array (),
//			'charoff' => array (),
//			'valign' => array ()),
//		'td' => array(
//			'abbr' => array (),
//			'align' => array (),
//			'axis' => array (),
//			'bgcolor' => array (),
//			'char' => array (),
//			'charoff' => array (),
//			'class' => array (),
//			'colspan' => array (),
//			'dir' => array(),
//			'headers' => array (),
//			'height' => array (),
//			'nowrap' => array (),
//			'rowspan' => array (),
//			'scope' => array (),
//			'style' => array (),
//			'valign' => array (),
//			'width' => array ()),
//		'textarea' => array(
//			'cols' => array (),
//			'rows' => array (),
//			'disabled' => array (),
//			'name' => array (),
//			'readonly' => array ()),
//		'tfoot' => array(
//			'align' => array (),
//			'char' => array (),
//			'class' => array (),
//			'charoff' => array (),
//			'valign' => array ()),
//		'th' => array(
//			'abbr' => array (),
//			'align' => array (),
//			'axis' => array (),
//			'bgcolor' => array (),
//			'char' => array (),
//			'charoff' => array (),
//			'class' => array (),
//			'colspan' => array (),
//			'headers' => array (),
//			'height' => array (),
//			'nowrap' => array (),
//			'rowspan' => array (),
//			'scope' => array (),
//			'valign' => array (),
//			'width' => array ()),
//		'thead' => array(
//			'align' => array (),
//			'char' => array (),
//			'charoff' => array (),
//			'class' => array (),
//			'valign' => array ()),
		'title' => array(),
//		'tr' => array(
//			'align' => array (),
//			'bgcolor' => array (),
//			'char' => array (),
//			'charoff' => array (),
//			'class' => array (),
//			'style' => array (),
//			'valign' => array ()),
		'tt' => array(),
		'u' => array(),
		'ul' => array (
			'class' => array (),
//			'style' => array (),
			'type' => array ()),
		'ol' => array (
			'class' => array (),
			'start' => array (),
//			'style' => array (),
			'type' => array ()),
		'var' => array ());
		
		$CONFIG->kses_allowedprotocols = array('http', 'https', 'ftp', 'news', 'mailto', 'rtsp', 'teamspeak', 'gopher', 'mms',
                           'color', 'callto', 'cursor', 'text-align', 'font-size', 'font-weight', 'font-style', 
                           'border', 'margin', 'padding', 'float');
		
		register_plugin_hook('validate', 'input', 'kses_filter_tags', 1);
	}
	
	/**
	 * Kses filtering of tags, called on a plugin hook
	 *
	 * @param mixed $var Variable to filter
	 * @return mixed
	 */
	function kses_filter_tags($hook, $entity_type, $returnvalue, $params)
	{
		$return = $returnvalue;
		$var = $returnvalue;
		
		if (@include_once(dirname(__FILE__) . "/vendors/kses/kses.php")) {
			
			global $CONFIG;
			
			$allowedtags = $CONFIG->kses_allowedtags; 
			$allowedprotocols = $CONFIG->kses_allowedprotocols;
			
			if (!is_array($var)) {
				$return = "";
				$return = kses($var, $allowedtags, $allowedprotocols);
			} else {
				$return = array();
				
				foreach($var as $key => $el) {
					$return[$key] = kses($el, $allowedtags, $allowedprotocols);
				}
			}
		}
	
		return $return;
	}
	
	
	register_elgg_event_handler('init','system','kses_init');
        
?>