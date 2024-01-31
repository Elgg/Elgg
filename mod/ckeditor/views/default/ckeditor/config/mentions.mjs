import Ajax from 'elgg/Ajax';

function getLivesearch(queryText, type) {
	if (queryText.length === 0) {
		return [];
	}
	
	if (queryText.substring(0,1) === ' ') {
		return [];
	}
	
	queryText = queryText.trim();
	if (queryText.length > 25) {
		return [];
	}
	
	// As an example of an asynchronous action, return a promise
	// that resolves after a 100ms timeout.
	// This can be a server request or any sort of delayed action.
	return new Promise(resolve => {
		setTimeout(() => {
			var ajax = new Ajax(false);
	
			var result = ajax.path('livesearch/' + type, {
				data: {
					q: queryText,
					source: 'ckeditor_mentions'
				},
				success: function(data) {
					data.forEach(function(item, index, arr) {
						switch (item.type) {
							case 'user':
								item.id = '@' + item.username;
								item.text = item.name;
								break;
							case 'group':
								item.id = '!' + item.guid;
								item.text = item.name;
								break;
							default:
								item.id = '[' + item.guid;
								item.text = item.title;
								break;
						}
						
					});
				}
			});

			resolve(result);
		}, 100);
	});
}

function findUsers(queryText) {
	return getLivesearch(queryText, 'users');
}

function findGroups(queryText) {
	return getLivesearch(queryText, 'groups');
}

function findObjects(queryText) {
	if (queryText.includes(']')) {
		// to allow the use of 'some [text] more text'
		// without triggering the mentions functionality
		return [];
	}
	
	return getLivesearch(queryText, 'objects');
}

function itemRenderer(data) {
	const itemElement = document.createElement('div');
	itemElement.classList.add('custom-item');
	itemElement.innerHTML = data.label.trim();
	
	return itemElement;
}

function MentionOutputRendering(editor) {
	editor.conversion.for('upcast').elementToAttribute({
		view: {
			name: 'a',
			key: 'data-mention',
			attributes: {
				href: true,
				'data-entity-type': true,
				'data-entity-subtype': true
			}
		},
		model: {
			key: 'mention',
			value: viewItem => {
				const mentionAttribute = editor.plugins.get('Mention').toMentionAttribute(viewItem, {
					id: viewItem.getAttribute('data-mention'),
					url: viewItem.getAttribute('href'),
					username: viewItem.getAttribute('data-user-id'),
					type: viewItem.getAttribute('data-entity-type'),
					subtype: viewItem.getAttribute('data-entity-subtype'),
				});
				
				return mentionAttribute;
			}
		},
		converterPriority: 'high'
	});
	
	editor.conversion.for('downcast').attributeToElement({
		model: 'mention',
		view: (modelAttributeValue, {writer}) => {
			if (!modelAttributeValue) {
				return;
			}
			
			const attributes = {
				class: 'mention',
				'data-mention': modelAttributeValue.id,
				'data-entity-type': modelAttributeValue.type,
				'data-entity-subtype': modelAttributeValue.subtype,
				'href': modelAttributeValue.url,
			};
			
			switch (modelAttributeValue.type) {
				case 'user':
					attributes['data-user-id'] = modelAttributeValue.username;
					break;
			}
			
			return writer.createAttributeElement('a', attributes, {
				priority: 20,
				id: modelAttributeValue.uid
			});
		},
		converterPriority: 'high'
	});
}

export default {
	extraPlugins: [MentionOutputRendering],
	mention: {
		feeds: [{
			marker: '@',
			feed: findUsers,
			itemRenderer: itemRenderer,
			minimumCharacters: 1
		}, {
			marker: '!',
			feed: findGroups,
			itemRenderer: itemRenderer,
			minimumCharacters: 1
		}, {
			marker: '[',
			feed: findObjects,
			itemRenderer: itemRenderer,
			minimumCharacters: 1
		}]
	}
};
