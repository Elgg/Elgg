<?php
/**
 * HTML body of an email
 *
 * @uses $vars['subject']  the subject of the email
 * @uses $vars['body']     the body/message of the email
 * @uses $vars['language'] the language of the recipient
 * @uses $vars['email']    the \Elgg\Email which is being processed
 */

$subject = elgg_extract('subject', $vars);
$body = elgg_extract('body', $vars);

$body_title = !empty($subject) ? elgg_view_title($subject) : '';

$site_link = elgg_view_entity_url(elgg_get_site_entity());

$email_params = [];
$email = elgg_extract('email', $vars);
if ($email instanceof \Elgg\Email) {
	$email_params = $email->getParams();
}

$object = elgg_extract('object', $email_params);
$recipient = elgg_extract('recipient', $email_params);
$add_mute_link = (bool) elgg_extract('add_mute_link', $email_params, true);

if ($add_mute_link && $object instanceof \ElggEntity && $recipient instanceof \ElggUser) {
	$site_link .= ' | ' . elgg_view_url(elgg_http_get_signed_url(elgg_generate_url('notifications:mute', [
		'entity_guid' => $object->guid,
		'recipient_guid' => $recipient->guid,
	])), elgg_echo('notifications:mute:email:footer', [], $recipient->getLanguage()));
}

?>
<table class="body-wrapper">
	<tr>
		<td>
			<table class="edge-wrapper">
				<tr>
					<td>
						<table class="content-wrapper">
							<tr>
								<td>
									<table class="header">
										<tr>
											<td>
												<h1><?= $body_title; ?></h1>
											</td>
										</tr>
									</table>
									<table class="content">
										<tr>
											<td>
												<?= $body; ?>
											</td>
										</tr>
									</table>
									<table class="footer">
										<tr>
											<td>
												<?= $site_link; ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
