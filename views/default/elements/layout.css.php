//<style>

	/***** PAGE NAVBAR ******/
	.elgg-page-navbar {
		position: relative;
	}

	/***** PAGE MESSAGES ******/
	.elgg-system-messages {
		position: fixed;
		top: 5em;
		right: 20px;
		max-width: 500px;
		z-index: 2000;
		list-style: none;
		padding: 0;
	}

	/***** PAGE LAYOUT HEADER ******/
	.elgg-layout-header {
		border-bottom: 1px solid #ddd;
		background: #eceeef;
		margin-bottom: 15px;
		position: relative;
	}
	.elgg-layout-header-cover {
		position: absolute;
		display: block;
		width: 100%;
		height: 100%;
		background-repeat: no-repeat;
		background-position: 50%;
		filter: grayscale(1);
		opacity: 0.5;
	}

	.elgg-layout-header-nav {
		color: #aaa;
		padding: 10px;
	}
	.elgg-layout-header-heading {
		padding: 20px 0 10px;
		width: 100%;
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: flex-end;
	}

	.elgg-layout-header-heading > h1,
	.elgg-layout-header-heading > h2,
	.elgg-layout-header-heading > h3 {
		order: 1;
		padding: 5px;
		margin-right: auto; /* force flexblox to justify to the right */
	}

	.elgg-layout-header-heading > .elgg-menu-container {
		order: 2;
	}

	.elgg-layout-header-filter {
		margin-bottom: -1px;
	}

	.elgg-layout-content {
		min-height: calc(100vh - 10em);
	}
	.elgg-layout-content .elgg-inner {
		padding: 0;
	}

	.elgg-layout-main {
		min-height: 360px;
		flex: 1 1 auto;
	}

	.elgg-layout-main,
	.elgg-layout-sidebar,
	.elgg-layout-sidebar-alt {
		padding: 10px;
		max-width: 100%;
	}

	.elgg-sidebar,
	.elgg-sidebar-alt {
		flex-basis: 250px;
		min-width: 250px;
	}

	/***** PAGE FOOTER ******/
	.elgg-page-footer {
		color: #aaa;
		position: relative;
		border-top: 1px solid #ddd;
		background: #eceeef;
	}

	/***** PROFILE LAYOUT ******/
	.elgg-profile-layout-header {
		margin: 0 auto;
		width: 100%;
		text-align: center;
		max-width: 75%;
	}

	.elgg-profile-layout-header .elgg-menu-title {
		justify-content: center;
		margin: 2em auto;
	}

	.elgg-profile-layout-header .elgg-listing-imprint {
		margin: 2em auto;
	}

	.elgg-profile-layout-header .elgg-menu-entity-imprint {
		justify-content: center;
		margin: 0 auto;
		color: #666;
	}

	.elgg-profile-layout-header .elgg-menu-item-byline {
		flex-basis: 100%;
		margin-bottom: 1rem;
		font-size: 2rem;
		font-weight: 300;
	}

