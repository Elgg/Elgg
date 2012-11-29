<?php
/**
 * Class that serves the concept of lazy views rendering. When it's treated as a string it does the actual rendering. 
 * @see http://www.php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
 */
class ElggView {
	
	private $view;
	private $vars;
	private $bypass;
	private $debug;
	private $viewtype;
	
	/**
	 * @see elgg_view function
	 */
	public function __construct($view, $vars = array(), $bypass = false, $debug = false, $viewtype = '') {
		$this->view = $view;
		$this->vars = $vars;
		$this->bypass = $bypass;
		$this->debug = $debug;
		$this->viewtype = $viewtype;
	}
	
	/**
	 * @see http://www.php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
	 * @return string
	 */
	public function __toString() {
		return elgg_view($this->view, $this->vars, $this->bypass, $this->debug, $this->viewtype);
	}
}