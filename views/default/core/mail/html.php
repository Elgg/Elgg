<?php
/**
 * This view is used to create the html version of the email message content.
 * It can output html markup
 *
 * @uses $vars['subject'] the subject of the email message
 * @uses $vars['body'] the contents of the email message
 *
 * @todo make this correct HTML markup
 */

$site = elgg_get_site_entity();

$subject = elgg_extract('subject', $vars);

$body = elgg_extract('body', $vars);
$body = elgg_autop($body);
$body = elgg_parse_emails($body);
$body = parse_urls($body);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//<?= get_language(); ?>" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<base target="_blank" />
		
		<title><?= $subject; ?></title>
		
		<style type="text/css">
			/* reset */
			#outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
			.ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Forces Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */
			table td {border-collapse: collapse;} /* Outlook 07, 10 padding issue fix */
			table {border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; } /* remove spacing around Outlook 07, 10 tables */
		</style>
	</head>
	<body>
		<style type="text/css">
			<?= elgg_view('core/mail/html.css'); ?>
		</style>
		<!-- body wrapper -->
		<table class="body-wrapper">
			<tr>
				<td>
					<!-- edge wrapper -->
					<table class="edge-wrapper">
						<tr>
							<td>
								<!-- content wrapper -->
								<table class="content-wrapper">
									<tr>
										<td>
											<!-- ///////////////////////////////////////////////////// -->
											<table class="header">
												<tr>
													<td>
														<h1><?= $subject; ?></h1>
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
														<a href="<?= $site->getURL(); ?>" title="<?= $site->getDisplayName(); ?>"><?= $site->getDisplayName(); ?></a>
													</td>
												</tr>
											</table>
											<!-- //////////// -->
										</td>
									</tr>
								</table>
								<!-- / content wrapper -->
							</td>
						</tr>
					</table>
					<!-- / edge wrapper -->
				</td>
			</tr>
		</table>
		<!-- / page wrapper -->
	</body>
</html>