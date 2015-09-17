
Modify the elgg.cron.1min.plist and replace YOUR.DOMAIN.HERE with your domain:
<string>http://YOUR.DOMAIN.HERE/cron/run/</string>

copy the plist into /Library/LaunchAgents

From terminal load the plist like so:
launchctl load -w /Library/LaunchAgents/elgg.cron.1min.plist

