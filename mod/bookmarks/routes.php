<?php

return [
	'/bookmarks' => [
		'/add'                          => 'bookmarks/add',
		'/all'                          => 'bookmarks/all',
		'/bookmarklet/{container_guid}' => 'bookmarks/bookmarklet',
		'/edit/{guid}'                  => 'bookmarks/edit',
		'/friends/{owner}'              => 'bookmarks/friends',
		'/group/{owner}/all'            => 'bookmarks/owner',
		'/owner/{owner}'                => 'bookmarks/owner',
		'/view' => [
			'/{guid}'                   => 'bookmarks/view',
			'/{guid}/{title}'           => 'bookmarks/view',
		],
	],
];
