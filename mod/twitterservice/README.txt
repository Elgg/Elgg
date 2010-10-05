Twitter Service

This allows users to Tweet through supported plugins.

To enable:
	1. Enable in the Tool Administration page.
	2. Visit http://dev.twitter.com/apps/new and register your site with Twitter.
		* The callback URL is http://yoursite.com/pg/twitterservice/authorize.
		* The access type MUST be Read & Write.
	3. Copy the Consumer Key and the Consumer Secret from the Twitter application
	   page to the Twitter Services settings sections on Elgg's Tool Administration page.
	4. Visit the Elgg User Settings page by clicking the "Settings" link at the top of the page.
	   Go to "Configure your tools" and authorize your Twitter account.
	5. Check the plugins you want to allow to Tweet.

Note: Users MUST authorize their Twitter accounts AND select plugins that
are allowed to tweet before Twitter will accept any posts.


Developers:
	You can register your plugin to provide Twitter integration.
	
	1.  Respond to the "plugin_list", "twitter_services" plugin hook:
		register_plugin_hook('plugin_list', 'twitter_service', 'blog_twitter_integration');

		function blog_twitter_integration($hook, $type, $value, $params) {
			return $value['blog'] = array(
				'name' => 'Blog',
				'description' => 'Tweet the first 140 characters of all public blog posts' 
			);

		}

	2.  When you want to tweet, emit a "tweet", "twitter_services" plugin hook:
	
		file: actions/blog/save.php

		$blog = new ElggBlog();
		$blog->body = get_input('body');
		$blog->title = get_input('title');

		if ($blog->save()) {
			$params = array(
				// plugin here must match the array index in the callback for "plugin_list", "twitter_services"
				'plugin' => 'blog',
				'message' => elgg_get_excerpt($blog->body, 140)
			);
			trigger_plugin_hook("tweet", "twitter_services", $params);
		}
