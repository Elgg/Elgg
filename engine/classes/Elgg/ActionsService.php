<?php
namespace Elgg;

use Elgg\Http\ResponseBuilder;
use ElggCrypto;
use ElggSession;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 * 
 * @package    Elgg.Core
 * @subpackage Actions
 * @since      1.9.0
 */
class ActionsService {

	use \Elgg\TimeUsing;
	
	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var ElggSession
	 */
	private $session;

	/**
	 * @var ElggCrypto
	 */
	private $crypto;

	/**
	 * Registered actions storage
	 *
	 * Each element has keys:
	 *   "file" => filename
	 *   "access" => access level
	 *
	 * @var array
	 */
	private $actions = array();

	/** 
	 * The current action being processed
	 * @var string 
	 */
	private $currentAction = null;

	/**
	 * @var string[]
	 */
	private static $access_levels = ['public', 'logged_in', 'admin'];

	/**
	 * Constructor
	 *
	 * @param Config      $config  Config
	 * @param ElggSession $session Session
	 * @param ElggCrypto  $crypto  Crypto service
	 */
	public function __construct(Config $config, ElggSession $session, ElggCrypto $crypto) {
		$this->config = $config;
		$this->session = $session;
		$this->crypto = $crypto;
	}

	/**
	 * Executes an action
	 * If called from action() redirect will be issued by the response factory
	 * If called as /action page handler response will be handled by \Elgg\Router
	 * 
	 * @param string $action    Action name
	 * @param string $forwarder URL to forward to after completion
	 * @return ResponseBuilder|null
	 * @see action
	 * @access private
	 */
	public function execute($action, $forwarder = "") {
		$action = rtrim($action, '/');
		$this->currentAction = $action;
		
		// @todo REMOVE THESE ONCE #1509 IS IN PLACE.
		// Allow users to disable plugins without a token in order to
		// remove plugins that are incompatible.
		// Login and logout are for convenience.
		// file/download (see #2010)
		$exceptions = array(
			'admin/plugins/disable',
			'logout',
			'file/download',
		);
	
		if (!in_array($action, $exceptions)) {
			// All actions require a token.
			$pass = $this->gatekeeper($action);
			if (!$pass) {
				return;
			}
		}
	
		$forwarder = str_replace($this->config->getSiteUrl(), "", $forwarder);
		$forwarder = str_replace("http://", "", $forwarder);
		$forwarder = str_replace("@", "", $forwarder);
		if (substr($forwarder, 0, 1) == "/") {
			$forwarder = substr($forwarder, 1);
		}

		$ob_started = false;

		/**
		 * Prepare action response
		 *
		 * @param string $error_key   Error message key
		 * @param int    $status_code HTTP status code
		 * @return ResponseBuilder
		 */
		$forward = function ($error_key = '', $status_code = ELGG_HTTP_OK) use ($action, $forwarder, &$ob_started) {
			if ($error_key) {
				if ($ob_started) {
					ob_end_clean();
				}
				$msg = _elgg_services()->translator->translate($error_key, [$action]);
				_elgg_services()->systemMessages->addErrorMessage($msg);
				$response = new \Elgg\Http\ErrorResponse($msg, $status_code);
			} else {
				$content = ob_get_clean();
				$response = new \Elgg\Http\OkResponse($content, $status_code);
			}
			
			$forwarder = empty($forwarder) ? REFERER : $forwarder;
			$response->setForwardURL($forwarder);
			return $response;
		};

		if (!isset($this->actions[$action])) {
			return $forward('actionundefined', ELGG_HTTP_NOT_IMPLEMENTED);
		}

		$user = $this->session->getLoggedInUser();

		// access checks
		switch ($this->actions[$action]['access']) {
			case 'public':
				break;
			case 'logged_in':
				if (!$user) {
					return $forward('actionloggedout', ELGG_HTTP_FORBIDDEN);
				}
				break;
			default:
				// admin or misspelling
				if (!$user || !$user->isAdmin()) {
					return $forward('actionunauthorized', ELGG_HTTP_FORBIDDEN);
				}
		}

		ob_start();
		
		// To quietly cancel the file, return a falsey value in the "action" hook.
		if (!_elgg_services()->hooks->trigger('action', $action, null, true)) {
			return $forward('', ELGG_HTTP_OK);
		}

		$file = $this->actions[$action]['file'];

		if (!is_file($file) || !is_readable($file)) {
			return $forward('actionnotfound', ELGG_HTTP_NOT_IMPLEMENTED);
		}

		$result = Includer::includeFile($file);
		if ($result instanceof ResponseBuilder) {
			ob_end_clean();
			return $result;
		}

		return $forward('', ELGG_HTTP_OK);
	}
	
	/**
	 * @see elgg_register_action
	 * @access private
	 */
	public function register($action, $filename = "", $access = 'logged_in') {
		// plugins are encouraged to call actions with a trailing / to prevent 301
		// redirects but we store the actions without it
		$action = rtrim($action, '/');
	
		if (empty($filename)) {
			$path = __DIR__ . '/../../../actions';
			$filename = realpath("$path/$action.php");
		}

		if (!in_array($access, self::$access_levels)) {
			_elgg_services()->logger->error("Unrecognized value '$access' for \$access in " . __METHOD__);
			$access = 'admin';
		}
	
		$this->actions[$action] = array(
			'file' => $filename,
			'access' => $access,
		);
		return true;
	}
	
	/**
	 * @see elgg_unregister_action
	 * @access private
	 */
	public function unregister($action) {
		if (isset($this->actions[$action])) {
			unset($this->actions[$action]);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @see validate_action_token
	 * @access private
	 */
	public function validateActionToken($visible_errors = true, $token = null, $ts = null) {
		if (!$token) {
			$token = get_input('__elgg_token');
		}
	
		if (!$ts) {
			$ts = get_input('__elgg_ts');
		}

		$session_id = $this->session->getId();

		if (($token) && ($ts) && ($session_id)) {
			if ($this->validateTokenOwnership($token, $ts)) {
				if ($this->validateTokenTimestamp($ts)) {
					// We have already got this far, so unless anything
					// else says something to the contrary we assume we're ok
					$returnval = _elgg_services()->hooks->trigger('action_gatekeeper:permissions:check', 'all', array(
						'token' => $token,
						'time' => $ts
					), true);

					if ($returnval) {
						return true;
					} else if ($visible_errors) {
						register_error(_elgg_services()->translator->translate('actiongatekeeper:pluginprevents'));
					}
				} else if ($visible_errors) {
					// this is necessary because of #5133
					if (elgg_is_xhr()) {
						register_error(_elgg_services()->translator->translate('js:security:token_refresh_failed', array($this->config->getSiteUrl())));
					} else {
						register_error(_elgg_services()->translator->translate('actiongatekeeper:timeerror'));
					}
				}
			} else if ($visible_errors) {
				// this is necessary because of #5133
				if (elgg_is_xhr()) {
					register_error(_elgg_services()->translator->translate('js:security:token_refresh_failed', array($this->config->getSiteUrl())));
				} else {
					register_error(_elgg_services()->translator->translate('actiongatekeeper:tokeninvalid'));
				}
			}
		} else {
			$req = _elgg_services()->request;
			$length = $req->server->get('CONTENT_LENGTH');
			$post_count = count($req->request);
			if ($length && $post_count < 1) {
				// The size of $_POST or uploaded file has exceed the size limit
				$error_msg = _elgg_services()->hooks->trigger('action_gatekeeper:upload_exceeded_msg', 'all', array(
					'post_size' => $length,
					'visible_errors' => $visible_errors,
				), _elgg_services()->translator->translate('actiongatekeeper:uploadexceeded'));
			} else {
				$error_msg = _elgg_services()->translator->translate('actiongatekeeper:missingfields');
			}
			if ($visible_errors) {
				register_error($error_msg);
			}
		}

		return false;
	}

	/**
	 * Is the token timestamp within acceptable range?
	 * 
	 * @param int $ts timestamp from the CSRF token
	 * 
	 * @return bool
	 */
	protected function validateTokenTimestamp($ts) {
		$timeout = $this->getActionTokenTimeout();
		$now = $this->getCurrentTime()->getTimestamp();
		return ($timeout == 0 || ($ts > $now - $timeout) && ($ts < $now + $timeout));
	}

	/**
	 * @see ActionsService::validateActionToken
	 * @access private
	 * @since 1.9.0
	 * @return int number of seconds that action token is valid
	 */
	public function getActionTokenTimeout() {
		if (($timeout = $this->config->get('action_token_timeout')) === null) {
			// default to 2 hours
			$timeout = 2;
		}
		$hour = 60 * 60;
		return (int)((float)$timeout * $hour);
	}

	/**
	 * @return bool
	 * @see action_gatekeeper
	 * @access private
	 */
	public function gatekeeper($action) {
		if ($action === 'login') {
			if ($this->validateActionToken(false)) {
				return true;
			}

			$token = get_input('__elgg_token');
			$ts = (int)get_input('__elgg_ts');
			if ($token && $this->validateTokenTimestamp($ts)) {
				// The tokens are present and the time looks valid: this is probably a mismatch due to the 
				// login form being on a different domain.
				register_error(_elgg_services()->translator->translate('actiongatekeeper:crosssitelogin'));
				_elgg_services()->responseFactory->redirect('login', 'csrf');
				return false;
			}
		}
		
		if ($this->validateActionToken()) {
			return true;
		}
			
		_elgg_services()->responseFactory->redirect(REFERER, 'csrf');
		return false;
	}

	/**
	 * Was the given token generated for the session defined by session_token?
	 *
	 * @param string $token         CSRF token
	 * @param int    $timestamp     Unix time
	 * @param string $session_token Session-specific token
	 *
	 * @return bool
	 * @access private
	 */
	public function validateTokenOwnership($token, $timestamp, $session_token = '') {
		$required_token = $this->generateActionToken($timestamp, $session_token);

		return _elgg_services()->crypto->areEqual($token, $required_token);
	}
	
	/**
	 * Generate a token from a session token (specifying the user), the timestamp, and the site key.
	 *
	 * @see generate_action_token
	 *
	 * @param int    $timestamp     Unix timestamp
	 * @param string $session_token Session-specific token
	 *
	 * @return string
	 * @access private
	 */
	public function generateActionToken($timestamp, $session_token = '') {
		if (!$session_token) {
			$session_token = elgg_get_session()->get('__elgg_session');
			if (!$session_token) {
				return false;
			}
		}

		return _elgg_services()->crypto->getHmac([(int)$timestamp, $session_token], 'md5')
			->getToken();
	}
	
	/**
	 * @see elgg_action_exists
	 * @access private
	 */
	public function exists($action) {
		return (isset($this->actions[$action]) && file_exists($this->actions[$action]['file']));
	}
	
	/**
	 * @see ajax_forward_hook
	 * @access private
	 * @deprecated 2.3
	 */
	public function ajaxForwardHook($hook, $reason, $forward_url, $params) {
		if (!elgg_is_xhr()) {
			return;
		}

		// grab any data echo'd in the action
		$output = ob_get_clean();

		if ($reason == 'walled_garden' || $reason == 'csrf') {
			$reason = '403';
		}

		$status_code = (int) $reason;
		if ($status_code < 100 || ($status_code > 299 && $status_code < 400) || $status_code > 599) {
			// We only want to preserve OK and error codes
			// Redirect responses should be converted to OK responses as this is an XHR request
			$status_code = ELGG_HTTP_OK;
		}

		$response = elgg_ok_response($output, '', $forward_url, $status_code);

		$headers = $response->getHeaders();
		$headers['Content-Type'] = 'application/json; charset=UTF-8';
		$response->setHeaders($headers);

		_elgg_services()->responseFactory->respond($response);
		exit;
	}
	
	/**
	 * @see ajax_action_hook
	 * @access private
	 * @deprecated 2.3
	 */
	public function ajaxActionHook() {
		if (elgg_is_xhr()) {
			ob_start();
		}
	}

	/**
	 * Get all actions
	 * 
	 * @return array
	 */
	public function getAllActions() {
		return $this->actions;
	}

	/**
	 * Send an updated CSRF token, provided the page's current tokens were not fake.
	 *
	 * @return ResponseBuilder
	 * @access private
	 */
	public function handleTokenRefreshRequest() {
		if (!elgg_is_xhr()) {
			return false;
		}

		// the page's session_token might have expired (not matching __elgg_session in the session), but
		// we still allow it to be given to validate the tokens in the page.
		$session_token = get_input('session_token', null, false);
		$pairs = (array)get_input('pairs', array(), false);
		$valid_tokens = (object)array();
		foreach ($pairs as $pair) {
			list($ts, $token) = explode(',', $pair, 2);
			if ($this->validateTokenOwnership($token, $ts, $session_token)) {
				$valid_tokens->{$token} = true;
			}
		}

		$ts = $this->getCurrentTime()->getTimestamp();
		$token = $this->generateActionToken($ts);
		$data = array(
			'token' => array(
				'__elgg_ts' => $ts,
				'__elgg_token' => $token,
				'logged_in' => $this->session->isLoggedIn(),
			),
			'valid_tokens' => $valid_tokens,
			'session_token' => $this->session->get('__elgg_session'),
			'user_guid' => $this->session->getLoggedInUserGuid(),
		);

		elgg_set_http_header("Content-Type: application/json;charset=utf-8");
		return elgg_ok_response($data);
	}
}

