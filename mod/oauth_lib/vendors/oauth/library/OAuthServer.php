<?php

/**
 * Server layer over the OAuthRequest handler
 * 
 * @version $Id: OAuthServer.php 51 2008-10-15 15:15:47Z marcw@pobox.com $
 * @author Marc Worrell <marcw@pobox.com>
 * @date  Nov 27, 2007 12:36:38 PM
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

require_once 'OAuthRequestVerifier.php';

class OAuthServer extends OAuthRequestVerifier
{
	/**
	 * Handle the request_token request.
	 * Returns the new request token and request token secret.
	 * 
	 * TODO: add correct result code to exception
	 * 
	 * @return string 	returned request token, false on an error
	 */
	public function requestToken ()
	{
		OAuthRequestLogger::start($this);
		try
		{
			$this->verify(false);
			
			$options = array();
			$ttl     = $this->getParam('xoauth_token_ttl', false);
			if ($ttl)
			{
				$options['token_ttl'] = $ttl;
			}

			// Create a request token
			$store  = elggconnect_get_oauth_store();//OAuthStore::instance();
			$token  = $store->addConsumerRequestToken($this->getParam('oauth_consumer_key', true), $options);
			$result = 'oauth_token='.$this->urlencode($token['token'])
					.'&oauth_token_secret='.$this->urlencode($token['token_secret']);

			if (!empty($token['token_ttl']))
			{
				$result .= '&xoauth_token_ttl='.$this->urlencode($token['token_ttl']);
			}

			$request_token = $token['token'];
					
			header('HTTP/1.1 200 OK');
			header('Content-Length: '.strlen($result));
			header('Content-Type: application/x-www-form-urlencoded');

			echo $result;
		}
		catch (OAuthException $e)
		{
			$request_token = false;

			header('HTTP/1.1 401 Unauthorized');
			header('Content-Type: text/plain');

			echo "OAuth Verification Failed: " . $e->getMessage();
		}

		OAuthRequestLogger::flush();
		return $request_token;
	}
	
	
	/**
	 * Verify the start of an authorization request.  Verifies if the request token is valid.
	 * Next step is the method authorizeFinish()
	 * 
	 * Nota bene: this stores the current token, consumer key and callback in the _SESSION
	 * 
	 * @exception OAuthException thrown when not a valid request
	 * @return array token description
	 */
	public function authorizeVerify ( )
	{
		OAuthRequestLogger::start($this);

		$store = elggconnect_get_oauth_store();//OAuthStore::instance();
		$token = $this->getParam('oauth_token', true);
		$rs    = $store->getConsumerRequestToken($token);
		if (empty($rs))
		{
			throw new OAuthException('Unknown request token "'.$token.'"');
		}

		// We need to remember the callback		
		if (	empty($_SESSION['verify_oauth_token'])
			||	strcmp($_SESSION['verify_oauth_token'], $rs['token']))
		{
			$_SESSION['verify_oauth_token'] 		= $rs['token'];
			$_SESSION['verify_oauth_consumer_key']	= $rs['consumer_key'];
			$_SESSION['verify_oauth_callback']		= $this->getParam('oauth_callback', true);
		}
		OAuthRequestLogger::flush();
		return $rs;
	}
	
	
	/**
	 * Overrule this method when you want to display a nice page when
	 * the authorization is finished.  This function does not know if the authorization was
	 * succesfull, you need to check the token in the database.
	 * 
	 * @param boolean authorized	if the current token (oauth_token param) is authorized or not
	 * @param int user_id			user for which the token was authorized (or denied)
	 */
	public function authorizeFinish ( $authorized, $user_id )
	{
		OAuthRequestLogger::start($this);

		$token = $this->getParam('oauth_token', true);
		if (	isset($_SESSION['verify_oauth_token']) 
			&&	$_SESSION['verify_oauth_token'] == $token)
		{
			// Flag the token as authorized, or remove the token when not authorized
			$store = elggconnect_get_oauth_store();//OAuthStore::instance();

			// Fetch the referrer host from the oauth callback parameter
			$referrer_host  = '';
			$oauth_callback = false;
			if (!empty($_SESSION['verify_oauth_callback']))
			{
				$oauth_callback = $_SESSION['verify_oauth_callback'];
				$ps = parse_url($oauth_callback);
				if (isset($ps['host']))
				{
					$referrer_host = $ps['host'];
				}
			}
			
			if ($authorized)
			{
				OAuthRequestLogger::addNote('Authorized token "'.$token.'" for user '.$user_id.' with referrer "'.$referrer_host.'"');
				$store->authorizeConsumerRequestToken($token, $user_id, $referrer_host);
			}
			else
			{
				OAuthRequestLogger::addNote('Authorization rejected for token "'.$token.'" for user '.$user_id."\nToken has been deleted");
				$store->deleteConsumerRequestToken($token);
			}
			
			if (!empty($oauth_callback))
			{
				$this->redirect($oauth_callback, array('oauth_token'=>rawurlencode($token)));
			}
		}
		OAuthRequestLogger::flush();
	}
	
	
	/**
	 * Exchange a request token for an access token.
	 * The exchange is only succesful iff the request token has been authorized.
	 * 
	 * Never returns, calls exit() when token is exchanged or when error is returned.
	 */
	public function accessToken ()
	{
		OAuthRequestLogger::start($this);

		try
		{
			$this->verify('request');

			$options = array();
			$ttl     = $this->getParam('xoauth_token_ttl', false);
			if ($ttl)
			{
				$options['token_ttl'] = $ttl;
			}
			
			$store  = elggconnect_get_oauth_store();//OAuthStore::instance();
			$token  = $store->exchangeConsumerRequestForAccessToken($this->getParam('oauth_token', true), $options);
			$result = 'oauth_token='.$this->urlencode($token['token'])
					.'&oauth_token_secret='.$this->urlencode($token['token_secret']);
					
			if (!empty($token['token_ttl']))
			{
				$result .= '&xoauth_token_ttl='.$this->urlencode($token['token_ttl']);
			}
					
			header('HTTP/1.1 200 OK');
			header('Content-Length: '.strlen($result));
			header('Content-Type: application/x-www-form-urlencoded');

			echo $result;
		}
		catch (OAuthException $e)
		{
			header('HTTP/1.1 401 Access Denied');
			header('Content-Type: text/plain');

			echo "OAuth Verification Failed: " . $e->getMessage();
		}
		
		OAuthRequestLogger::flush();
		exit();
	}	
}

/* vi:set ts=4 sts=4 sw=4 binary noeol: */

?>