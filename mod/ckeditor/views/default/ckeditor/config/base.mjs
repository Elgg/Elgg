import 'jquery';
import elgg from 'elgg';
	
var topbar_height = $('.elgg-page-topbar').height();

export default {
	htmlSupport: {
		allow: [{
			name: /.*/,
			attributes: true,
			classes: true,
			styles: true
		}]
	},
	language: elgg.config.current_language,
	ui: {
		viewportOffset: {
			top: topbar_height
		}
	},
	image: {
		toolbar: ['toggleImageCaption', 'imageTextAlternative',	{
			title: '',
			name: 'imageStyle:alignment',
			items: [
				'imageStyle:alignBlockLeft',
				'imageStyle:block',
				'imageStyle:alignBlockRight',
			],
			defaultItem: 'imageStyle:alignBlockLeft'
		}, 'imageStyle:side', 'linkImage'],
		resizeUnit: 'px'
	},
	link: {
		defaultProtocol: 'http://'
	},
	removePlugins: [
		'MediaEmbedToolbar', // is not yet supported in CKEditor5 but throws console warning
		'MediaEmbed', // this plugin only provides backend oembed support. Can be enabled by plugin that provides frontend support
		'TodoList' // disable this by default as we do not allow input form elements in htmlawed
	]
};
