<?php

$subject = elgg_extract('subject', $vars);
$body = elgg_extract('body', $vars);

$body_title = !empty($subject) ? elgg_view_title($subject) : '';

$site_link = elgg_view_entity_url(elgg_get_site_entity());

echo <<<__BODY
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
												<h1>{$body_title}</h1>
											</td>
										</tr>
									</table>
									<table class="content">
										<tr>
											<td>
												{$body}
											</td>
										</tr>
									</table>
									<table class="footer">
										<tr>
											<td>
												{$site_link}
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
__BODY;
