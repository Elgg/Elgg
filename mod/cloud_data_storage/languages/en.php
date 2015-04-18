<?php
/**
 * English strings for cloud storage
 */

return [
	// aws s3
	'admin:settings:user_data_store:aws_s3' => 'AWS S3 cloud',
	'admin:settings:user_data_store:aws_s3:info' => 'User data will be stored in an AWS S3 cloud bucket.',
	'admin:settings:user_data_store:aws_s3:key' => 'AWS key:',
	'admin:settings:user_data_store:aws_s3:secret' => 'AWS secret:',
	'admin:settings:user_data_store:aws_s3:bucket' => 'AWS bucket:',
	
	// MS azure
	'admin:settings:user_data_store:ms_azure' => 'Micosoft Azure Blob Storage',
	'admin:settings:user_data_store:ms_azure:info' => 'User data will be stored in an Azure Blog Storage account.',
	'admin:settings:user_data_store:ms_azure:endpoint' => 'Endpoint:',
	'admin:settings:user_data_store:ms_azure:endpoint_help' => 'This is the full URL of your endpoint. '
		. 'It\'s usually in the form of https://your-account-name.blob.core.windows.net/',
	'admin:settings:user_data_store:ms_azure:account' => 'Account:',
	'admin:settings:user_data_store:ms_azure:key' => 'Key:',
	'admin:settings:user_data_store:ms_azure:container' => 'Container:',
	
	// Google cloud
	'admin:settings:user_data_store:google_cloud' => 'Google Cloud Storage',
	'admin:settings:user_data_store:google_cloud:info' => 'User data will be stored in a Google Cloud account.',
	'admin:settings:user_data_store:google_cloud:p12_key_location' => 'p12 Key Location',
	'admin:settings:user_data_store:google_cloud:p12_key_location:info' => 'The full server path to the p12 key downloaded from the Google Developer Console for your service account.',
	'admin:settings:user_data_store:google_cloud:account_name' => 'Account Name:',
	'admin:settings:user_data_store:google_cloud:account_name:info' => 'The service account name. This is also called the email address, and ends with @developer.gserviceaccount.com',
	
	'admin:settings:user_data_store:google_cloud:bucket' => 'Bucket:',
];