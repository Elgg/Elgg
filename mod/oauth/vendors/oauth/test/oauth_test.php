<?php

/**
 * Tests of OAuth implementation.
 * 
 * @version $Id$
 * @author Marc Worrell <marcw@pobox.com>
 * @date  Nov 29, 2007 3:46:56 PM
 * @see http://wiki.oauth.net/TestCases
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

require_once dirname(__FILE__) . '/../library/OAuthRequest.php';
require_once dirname(__FILE__) . '/../library/OAuthRequester.php';
require_once dirname(__FILE__) . '/../library/OAuthRequestSigner.php';
require_once dirname(__FILE__) . '/../library/OAuthRequestVerifier.php';

if (!function_exists('getallheaders'))
{
	function getallheaders()
	{
		return array();
	}
}


oauth_test();

function oauth_test ()
{
	error_reporting(E_ALL);

	header('Content-Type: text/plain; charset=utf-8');
	
	echo "Performing OAuth module tests.\n\n";
	echo "See also: http://wiki.oauth.net/TestCases\n\n";
	
	assert_options(ASSERT_CALLBACK, 'oauth_assert_handler');
	assert_options(ASSERT_WARNING,  0);
	
	$req = new OAuthRequest('http://www.example.com', 'GET');

	echo "***** Parameter Encoding *****\n\n";
	
	assert('$req->urlencode(\'abcABC123\') == \'abcABC123\'');
	assert('$req->urlencode(\'-._~\') == \'-._~\'');
	assert('$req->urlencode(\'%\') == \'%25\'');
	assert('$req->urlencode(\'&=*\') == \'%26%3D%2A\'');
	assert('$req->urlencode(\'&=*\') == \'%26%3D%2A\'');
	assert('$req->urlencode("\n") == \'%0A\'');
	assert('$req->urlencode(" ") == \'%20\'');
	assert('$req->urlencode("\x7f") == \'%7F\'');


	echo "***** Normalize Request Parameters *****\n\n";
	
	$req = new OAuthRequest('http://example.com/?name', 'GET');
	assert('$req->getNormalizedParams() == \'name=\'');

	$req = new OAuthRequest('http://example.com/?a=b', 'GET');
	assert('$req->getNormalizedParams() == \'a=b\'');
	
	$req = new OAuthRequest('http://example.com/?a=b&c=d', 'GET');
	assert('$req->getNormalizedParams() == \'a=b&c=d\'');
	
	// At this moment we don't support two parameters with the same name
	// so I changed this test case to "a=" and "b=" and not "a=" and "a="
	$req = new OAuthRequest('http://example.com/?b=x!y&a=x+y', 'GET');
	assert('$req->getNormalizedParams() == \'a=x%20y&b=x%21y\'');
	
	$req = new OAuthRequest('http://example.com/?x!y=a&x=a', 'GET');
	assert('$req->getNormalizedParams() == \'x=a&x%21y=a\'');
	

	echo "***** Base String *****\n\n";
	
	$req  = new OAuthRequest('http://example.com/?n=v', 'GET');
	assert('$req->signatureBaseString() == \'GET&http%3A%2F%2Fexample.com%2F&n%3Dv\'');
	
	$req = new OAuthRequest(
							'https://photos.example.net/request_token', 
							'POST',
							'oauth_version=1.0&oauth_consumer_key=dpf43f3p2l4k3l03&oauth_timestamp=1191242090&oauth_nonce=hsu94j3884jdopsl&oauth_signature_method=PLAINTEXT&oauth_signature=ignored',
							array('X-OAuth-Test' => true));
	assert('$req->signatureBaseString() == \'POST&https%3A%2F%2Fphotos.example.net%2Frequest_token&oauth_consumer_key%3Ddpf43f3p2l4k3l03%26oauth_nonce%3Dhsu94j3884jdopsl%26oauth_signature_method%3DPLAINTEXT%26oauth_timestamp%3D1191242090%26oauth_version%3D1.0\'');

	$req = new OAuthRequest(
							'http://photos.example.net/photos?file=vacation.jpg&size=original&oauth_version=1.0&oauth_consumer_key=dpf43f3p2l4k3l03&oauth_token=nnch734d00sl2jdk&oauth_timestamp=1191242096&oauth_nonce=kllo9940pd9333jh&oauth_signature=ignored&oauth_signature_method=HMAC-SHA1', 
							'GET');
	assert('$req->signatureBaseString() == \'GET&http%3A%2F%2Fphotos.example.net%2Fphotos&file%3Dvacation.jpg%26oauth_consumer_key%3Ddpf43f3p2l4k3l03%26oauth_nonce%3Dkllo9940pd9333jh%26oauth_signature_method%3DHMAC-SHA1%26oauth_timestamp%3D1191242096%26oauth_token%3Dnnch734d00sl2jdk%26oauth_version%3D1.0%26size%3Doriginal\'');


	echo "***** HMAC-SHA1 *****\nRequest signing\n";

	OAuthStore::instance('MySQL', array('conn'=>false));
	$req = new OAuthRequestSigner('http://photos.example.net/photos?file=vacation.jpg&size=original', 'GET');	

	assert('$req->urldecode($req->calculateDataSignature(\'bs\', \'cs\', \'\',   \'HMAC-SHA1\')) == \'egQqG5AJep5sJ7anhXju1unge2I=\'');
	assert('$req->urldecode($req->calculateDataSignature(\'bs\', \'cs\', \'ts\', \'HMAC-SHA1\')) == \'VZVjXceV7JgPq/dOTnNmEfO0Fv8=\'');
	
	$secrets = array(
				'consumer_key'		=> 'dpf43f3p2l4k3l03',
				'consumer_secret'	=> 'kd94hf93k423kf44',
				'token'				=> 'nnch734d00sl2jdk',
				'token_secret'		=> 'pfkkdhi9sl3r4s00',
				'signature_methods'	=> array('HMAC-SHA1'),
				'nonce'				=> 'kllo9940pd9333jh',
				'timestamp'			=> '1191242096'
				);
	$req->sign(0, $secrets);
	assert('$req->getParam(\'oauth_signature\', true) == \'tR3+Ty81lMeYAr/Fid0kMTYa/WM=\'');

	echo "***** HMAC-SHA1 *****\nRequest verification\n";

	$req = new OAuthRequestVerifier(
				'http://photos.example.net/photos?file=vacation.jpg&size=original'
				.'&oauth_consumer_key=dpf43f3p2l4k3l03&oauth_token=nnch734d00sl2jdk'
				.'&oauth_signature_method=HMAC-SHA1&oauth_nonce=kllo9940pd9333jh'
				.'&oauth_timestamp=1191242096&oauth_version=1.0'
				.'&oauth_signature='.rawurlencode('tR3+Ty81lMeYAr/Fid0kMTYa/WM=')
				, 'GET');
	
	$req->verifySignature('kd94hf93k423kf44', 'pfkkdhi9sl3r4s00');

	echo "\n";
	echo "***** Yahoo! test case ******\n\n";

	OAuthStore::instance('MySQL', array('conn'=>false));
	$req = new OAuthRequestSigner('http://example.com:80/photo', 'GET');
	
	$req->setParam('title',   'taken with a 30% orange filter');
	$req->setParam('file',    'mountain & water view');
	$req->setParam('format',  'jpeg');
	$req->setParam('include', array('date','aperture'));

	$secrets = array(
				'consumer_key'		=> '1234=asdf=4567',
				'consumer_secret'	=> 'erks823*43=asd&123ls%23',
				'token'				=> 'asdf-4354=asew-5698',
				'token_secret'		=> 'dis9$#$Js009%==',
				'signature_methods'	=> array('HMAC-SHA1'),
				'nonce'				=> '3jd834jd9',
				'timestamp'			=> '12303202302'
				);
	$req->sign(0, $secrets);

	// echo "Basestring:\n",$req->signatureBaseString(), "\n\n";

	//echo "queryString:\n",$req->getQueryString(), "\n\n";
	assert('$req->getQueryString() == \'title=taken%20with%20a%2030%25%20orange%20filter&file=mountain%20%26%20water%20view&format=jpeg&include=date&include=aperture\'');	

	//echo "oauth_signature:\n",$req->getParam('oauth_signature', true),"\n\n";
	assert('$req->getParam(\'oauth_signature\', true) == \'jMdUSR1vOr3SzNv3gZ5DDDuGirA=\'');
	
	echo "\n\nFinished.\n";
}


function oauth_assert_handler ( $file, $line, $code )
{
	echo "\nAssertion failed in $file:$line
   $code\n\n";
}

/* vi:set ts=4 sts=4 sw=4 binary noeol: */

?>