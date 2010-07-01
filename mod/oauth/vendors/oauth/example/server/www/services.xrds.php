<?php

/**
 * oauth-php: Example OAuth server
 *
 * XRDS discovery for OAuth. This file helps the consumer program to
 * discover where the OAuth endpoints for this server are.
 *
 * @author Arjan Scherpenisse <arjan@scherpenisse.net>
 *
 * 
 * The MIT License
 * 
 * Copyright (c) 2007-2008 Mediamatic Lab
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

header('Content-Type: application/xrds+xml');

$server = $_SERVER['SERVER_NAME'];

echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";

?>
<XRDS xmlns="xri://$xrds">
    <XRD xmlns:simple="http://xrds-simple.net/core/1.0" xmlns="xri://$XRD*($v*2.0)" xmlns:openid="http://openid.net/xmlns/1.0" version="2.0" xml:id="main">
	<Type>xri://$xrds*simple</Type>
	<Service>
	    <Type>http://oauth.net/discovery/1.0</Type>
	    <URI>#main</URI>
	</Service>
	<Service>
	    <Type>http://oauth.net/core/1.0/endpoint/request</Type>
	    <Type>http://oauth.net/core/1.0/parameters/auth-header</Type>
	    <Type>http://oauth.net/core/1.0/parameters/uri-query</Type>
	    <Type>http://oauth.net/core/1.0/signature/HMAC-SHA1</Type>
	    <Type>http://oauth.net/core/1.0/signature/PLAINTEXT</Type>
	    <URI>http://<?=$server?>/oauth/request_token</URI>
	</Service>
	<Service>
	    <Type>http://oauth.net/core/1.0/endpoint/authorize</Type>
	    <Type>http://oauth.net/core/1.0/parameters/uri-query</Type>
	    <URI>http://<?=$server?>/oauth/authorize</URI>
	</Service>
	<Service>
	    <Type>http://oauth.net/core/1.0/endpoint/access</Type>
	    <Type>http://oauth.net/core/1.0/parameters/auth-header</Type>
	    <Type>http://oauth.net/core/1.0/parameters/uri-query</Type>
	    <Type>http://oauth.net/core/1.0/signature/HMAC-SHA1</Type>
	    <Type>http://oauth.net/core/1.0/signature/PLAINTEXT</Type>
	    <URI>http://<?=$server?>/oauth/access_token</URI>
	</Service>
    </XRD>
</XRDS>
