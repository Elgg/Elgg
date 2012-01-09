<?php
/**
 * An XMLRPC call
 *
 * @package    Elgg.Core
 * @subpackage XMLRPC
 */
class XMLRPCCall {
	/** Method name */
	private $methodname;

	/** Parameters */
	private $params;

	/**
	 * Construct a new XML RPC Call
	 *
	 * @param string $xml XML
	 */
	function __construct($xml) {
		$this->parse($xml);
	}

	/**
	 * Return the method name associated with the call.
	 *
	 * @return string
	 */
	public function getMethodName() { return $this->methodname; }

	/**
	 * Return the parameters.
	 * Returns a nested array of XmlElement.
	 *
	 * @see XmlElement
	 * @return array
	 */
	public function getParameters() { return $this->params; }

	/**
	 * Parse the xml into its components according to spec.
	 * This first version is a little primitive.
	 *
	 * @param string $xml XML
	 *
	 * @return void
	 */
	private function parse($xml) {
		$xml = xml_to_object($xml);

		// sanity check
		if ((isset($xml->name)) && (strcasecmp($xml->name, "methodCall") != 0)) {
			throw new CallException(elgg_echo('CallException:NotRPCCall'));
		}

		// method name
		$this->methodname = $xml->children[0]->content;

		// parameters
		$this->params = $xml->children[1]->children;
	}
}
