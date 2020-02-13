<?php

namespace Elgg\Security;

use Elgg\Config;
use Elgg\PluginHooksService;
use Elgg\Values;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Exceptions\Security\InvalidPasswordLengthException;
use Elgg\Exceptions\Security\InvalidPasswordCharacterRequirementsException;
use Elgg\I18n\Translator;
use Hackzilla\PasswordGenerator\Generator\RequirementPasswordGenerator;

/**
 * Password generator service
 *
 * Can generate and validate passwords based on config settings regarding password requirements
 *
 * @since 3.2
 * @internal
 */
class PasswordGeneratorService {

	/**
	 * @var Config
	 */
	protected $config;
	
	/**
	 * @var Translator
	 */
	protected $translator;
	
	/**
	 * @var PluginHooksService
	 */
	protected $hooks;
	
	/**
	 * Constructor
	 *
	 * @param Config             $config     Elgg config
	 * @param Translator         $translator Translator
	 * @param PluginHooksService $hooks      Hooks service
	 */
	public function __construct(Config $config, Translator $translator, PluginHooksService $hooks) {
		$this->config = $config;
		$this->translator = $translator;
		$this->hooks = $hooks;
	}
	
	/**
	 * Generate a new random password
	 *
	 * @param int $length the length of the password (can't be less the minimal password length config setting)
	 *
	 * @return string
	 */
	public function generatePassword(int $length = 12) {
		
		$length = $this->getValidLength($length);
		
		$generator = $this->getGenerator();
		$generator->setLength($length);
		
		return $this->hooks->trigger('generate', 'password', [], $generator->generatePassword());
	}
	
	/**
	 * Assert that a given string matches the password requirements
	 *
	 * @param string $password the password to validate
	 *
	 * @return void
	 * @throws InvalidPasswordLengthException
	 * @throws InvalidPasswordCharacterRequirementsException
	 */
	public function assertValidPassword(string $password) {
		
		if (!$this->validatePasswordLength($password)) {
			throw new InvalidPasswordLengthException();
		}
		
		if ($this->isValidPassword($password)) {
			return;
		}
		
		throw new InvalidPasswordCharacterRequirementsException();
	}
	
	/**
	 * Validate that a given string matches the password requirements
	 *
	 * @param string $password the password to validate
	 *
	 * @return bool
	 */
	public function isValidPassword(string $password) {
		
		// generator can't handle min length only length
		if (!$this->validatePasswordLength($password)) {
			return false;
		}
		
		$generator = $this->getGenerator();
		// no make sure length isn't a failure condition
		$generator->setLength(strlen($password));
		
		return $generator->validatePassword($password);
	}
	
	/**
	 * Get the regex to set on an input/password to validate password requirements during input
	 *
	 * Note: This regex is meant for use in html/javascript NOT PHP
	 *
	 * @return string
	 */
	public function getInputRegEx() {
		$regex = '';
		
		$lower = $this->config->min_password_lower;
		$upper = $this->config->min_password_upper;
		$number = $this->config->min_password_number;
		$special = $this->config->min_password_special;
		
		if (!Values::isEmpty($lower)) {
			$lower = (int) $lower;
			if ($lower < 1) {
				$regex .= '(?!.*[a-z])';
			} else {
				$regex .= '(?=' . str_repeat('.*[a-z]', $lower) . ')';
			}
		}
		
		if (!Values::isEmpty($upper)) {
			$upper = (int) $upper;
			if ($upper < 1) {
				$regex .= '(?!.*[A-Z])';
			} else {
				$regex .= '(?=' . str_repeat('.*[A-Z]', $upper) . ')';
			}
		}
		
		if (!Values::isEmpty($number)) {
			$number = (int) $number;
			if ($number < 1) {
				$regex .= '(?!.*[0-9])';
			} else {
				$regex .= '(?=' . str_repeat('.*[0-9]', $number) . ')';
			}
		}
		
		if (!Values::isEmpty($special)) {
			$generator = $this->getGenerator();
			$special_chars = $generator->getParameter(RequirementPasswordGenerator::PARAMETER_SYMBOLS);
			$special_chars = str_replace(']', '\\]', $special_chars);
			$special_chars = str_replace('-', '\\-', $special_chars);
			
			$special = (int) $special;
			if ($special < 1) {
				$regex .= '(?!.*[' . $special_chars . '])';
			} else {
				$regex .= '(?=' . str_repeat('.*[' . $special_chars . ']', $special) . ')';
			}
		}
		
		$length = (int) $this->config->min_password_length;
		if ($length < 1) {
			$length = 6;
		}
		
		$regex .= '.{' . $length . ',}';
		
		return $regex;
	}
	
	/**
	 * Get a description of how a valid password should be made
	 *
	 * @return string
	 */
	public function getPasswordRequirementsDescription() {
		
		$result = [];
		$result[] = $this->translator->translate('password:requirements:min_length', [$this->config->min_password_length]);
		
		$lower = $this->config->min_password_lower;
		if (!Values::isEmpty($lower)) {
			$lower = (int) $lower;
			if ($lower > 0) {
				$result[] = $this->translator->translate('password:requirements:lower', [$lower]);
			} else {
				$result[] = $this->translator->translate('password:requirements:no_lower');
			}
		}
		
		$upper = $this->config->min_password_upper;
		if (!Values::isEmpty($upper)) {
			$upper = (int) $upper;
			if ($upper > 0) {
				$result[] = $this->translator->translate('password:requirements:upper', [$upper]);
			} else {
				$result[] = $this->translator->translate('password:requirements:no_upper');
			}
		}
		
		$number = $this->config->min_password_number;
		if (!Values::isEmpty($number)) {
			$number = (int) $number;
			if ($number > 0) {
				$result[] = $this->translator->translate('password:requirements:number', [$number]);
			} else {
				$result[] = $this->translator->translate('password:requirements:no_number');
			}
		}
		
		$special = $this->config->min_password_special;
		if (!Values::isEmpty($special)) {
			$special = (int) $special;
			if ($special > 0) {
				$result[] = $this->translator->translate('password:requirements:special', [$special]);
			} else {
				$result[] = $this->translator->translate('password:requirements:no_special');
			}
		}
		
		return implode(' ', $result);
	}
	
	/**
	 * Add the security password requirements to an input/password field
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'input/password'
	 *
	 * @return void|array
	 */
	public function addInputRequirements(\Elgg\Hook $hook) {
		
		$vars = $hook->getValue();
		if (!(bool) elgg_extract('add_security_requirements', $vars, false)) {
			return;
		}
		
		// add requirements pattern
		$vars['pattern'] = $this->getInputRegEx();
		
		// add requirements to title so on pattern failure the user knows what to do
		$title = elgg_extract('title', $vars, '');
		$title .= ' ' . $this->getPasswordRequirementsDescription();
		$vars['title'] = trim($title);
		
		return $vars;
	}
	
	/**
	 * Validate password during user registration
	 *
	 * @param \Elgg\Hook $hook 'registeruser:validate:password', 'all'
	 *
	 * @return void
	 * @throws RegistrationException
	 */
	public function registerUserPasswordValidation(\Elgg\Hook $hook) {
		
		$password = $hook->getParam('password');
		if (empty($password)) {
			return;
		}
		
		try {
			$this->assertValidPassword($password);
		} catch (\Exception $e) {
			throw new RegistrationException($e->getMessage(), $e->getCode(), $e);
		}
	}
	
	/**
	 * Get helper generator
	 *
	 * @return RequirementPasswordGenerator
	 */
	protected function getGenerator() {
		$generator = new RequirementPasswordGenerator();
		$generator->setLength($this->config->min_password_length);
		
		// set lower case requirements
		$lower = $this->config->min_password_lower;
		if (!Values::isEmpty($lower)) {
			$lower = (int) $lower;
			if (empty($lower)) {
				$generator->setOptionValue(RequirementPasswordGenerator::OPTION_LOWER_CASE, false);
			} else {
				$generator->setOptionValue(RequirementPasswordGenerator::OPTION_LOWER_CASE, true);
				$generator->setMinimumCount(RequirementPasswordGenerator::OPTION_LOWER_CASE, $lower);
			}
		}
		
		// set upper case requirements
		$upper = $this->config->min_password_upper;
		if (!Values::isEmpty($upper)) {
			$upper = (int) $upper;
			if (empty($upper)) {
				$generator->setOptionValue(RequirementPasswordGenerator::OPTION_UPPER_CASE, false);
			} else {
				$generator->setOptionValue(RequirementPasswordGenerator::OPTION_UPPER_CASE, true);
				$generator->setMinimumCount(RequirementPasswordGenerator::OPTION_UPPER_CASE, $upper);
			}
		}
		
		// set number requirements
		$number = $this->config->min_password_number;
		if (!Values::isEmpty($number)) {
			$number = (int) $number;
			if (empty($number)) {
				$generator->setOptionValue(RequirementPasswordGenerator::OPTION_NUMBERS, false);
			} else {
				$generator->setOptionValue(RequirementPasswordGenerator::OPTION_NUMBERS, true);
				$generator->setMinimumCount(RequirementPasswordGenerator::OPTION_NUMBERS, $number);
			}
		}
		
		// set special character requirements
		$special = $this->config->min_password_special;
		if (!Values::isEmpty($special)) {
			$special = (int) $special;
			if (empty($special)) {
				$generator->setOptionValue(RequirementPasswordGenerator::OPTION_SYMBOLS, false);
			} else {
				$generator->setOptionValue(RequirementPasswordGenerator::OPTION_SYMBOLS, true);
				$generator->setMinimumCount(RequirementPasswordGenerator::OPTION_SYMBOLS, $special);
			}
		}
		
		return $generator;
	}
	
	/**
	 * Make sure the password length requirement can be met by the password settings
	 *
	 * @param int $length the requested length
	 *
	 * @return int
	 */
	protected function getValidLength(int $length) {
		$min_length = (int) $this->config->min_password_length;
		
		$requirements_length = (int) $this->config->min_password_lower;
		$requirements_length += (int) $this->config->min_password_upper;
		$requirements_length += (int) $this->config->min_password_number;
		$requirements_length += (int) $this->config->min_password_special;
		
		return max($min_length, $requirements_length, $length);
	}
	
	/**
	 * Validate that a password meets the minimal length requirement
	 *
	 * @param string $password the password to check
	 *
	 * @return bool
	 */
	protected function validatePasswordLength(string $password) {
		if (elgg_strlen($password) < $this->config->min_password_length) {
			return false;
		}
		
		return true;
	}
}
