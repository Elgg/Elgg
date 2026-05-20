<?php

namespace Elgg\SystemLog\Controllers;

use Elgg\Controllers\GenericAction;
use Elgg\Database\Update;
use Elgg\Exceptions\DatabaseException;
use Elgg\Exceptions\Http\InternalServerErrorException;
use Elgg\Http\OkResponse;
use Elgg\SystemLog\SystemLog;

/**
 * Remove the logged IP addresses from the system_log table
 */
class ClearIpAddresses extends GenericAction {
	
	/**
	 * {@inheritdoc}
	 */
	protected function execute(): void {
		$update = Update::table(SystemLog::TABLE_NAME);
		$update->set('ip_address', $update->param('', ELGG_VALUE_STRING))
			->where($update->compare('ip_address', '!=', '', ELGG_VALUE_STRING));
		
		try {
			elgg()->db->updateData($update);
		} catch (DatabaseException $e) {
			throw new InternalServerErrorException($e->getMessage(), 0, $e);
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function success(): OkResponse {
		return elgg_ok_response('', elgg_echo('system_log:action:clear_ip_addresses:success'));
	}
}
