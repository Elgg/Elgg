<?php
	/**
	 * API Admin language pack.
	 * 
	 * @package ElggAPIAdmin
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */


	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'apiadmin' => 'API Administration',
	
	
			'apiadmin:keyrevoked' => 'API Key revoked',
			'apiadmin:keynotrevoked' => 'API Key could not be revoked',
			'apiadmin:generated' => 'API Key successfully generated',
	
			'apiadmin:yourref' => 'Your reference',
			'apiadmin:generate' => 'Generate a new keypair',
	
			'apiadmin:noreference' => 'You must provide a reference for your new key.',
			'apiadmin:generationfail' => 'There was a problem generating the new keypair',
			'apiadmin:generated' => 'New API keypair generated successfully',
	
			'apiadmin:revoke' => 'Revoke key',
			'apiadmin:public' => 'Public',
			'apiadmin:private' => 'Private',

	
			'item:object:api_key' => 'API Keys',
	);
					
	add_translation("en",$english);
?>