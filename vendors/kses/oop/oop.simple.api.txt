Parse($string = "")
	The basic function of kses.  Give it a $string, and it will strip
	out the unwanted HTML and attributes.

AddProtocols()
	Add a protocol or list of protocols to the kses object to be
	considered valid during a Parse().  The parameter can be a string
	containing a single protocol, or an array of strings, each
	containing a single protocol.

Protocols()
	Deprecated.  Use AddProtocols()

AddProtocol($protocol = "")
	Adds a single protocol to the kses object that will be considered
	valid during a Parse().

SetProtocols()
	This is a straight setting/overwrite of existing protocols in the
	kses object.  All existing protocols are removed, and the parameter
	is used to determine what protocol(s) the kses object will consider
	valid.  The parameter can be a string containing a single protocol,
	or an array of strings, each constaining a single protocol.

DumpProtocols()
	This returns an indexed array of the valid protocols contained in
	the kses object.

DumpElements()
	This returns an associative array of the valid (X)HTML elements in
	the kses object along with attributes for each element, and tests
	that will be performed on each attribute.

AddHTML($tag = "", $attribs = array())
	This allows the end user to add a single (X)HTML element to the
	kses object along with the (if any) attributes that the specific
	(X)HTML element is allowed to have.
	
	See the file 'attribute-value-checks' for more information as to
	the format of the data to be provided to this method.

RemoveProtocol($protocol = "")
	This allows for the removal of a single protocol from the list of
	valid protocols in the kses object.

RemoveProtocols()
	This allows for the single or batch removal of protocols from the
	kses object.  The parameter is either a string containing a
	protocol to be removed, or an array of strings that each contain
	a protocol.

filterKsesTextHook($string)
	For the OOP version of kses, this is an additional hook that allows
	the end user to perform additional postprocessing of a string
	that's being run through Parse().

_hook()
	Deprecated.  Use filterKsesTextHook().