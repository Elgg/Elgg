import 'jquery';
import { 
	Alignment,
	Autoformat,
	AutoImage,
	AutoLink,
	BlockQuote,
	Bold,
	Code,
	CodeBlock,
	Essentials,
	FindAndReplace,
	Font,
	GeneralHtmlSupport,
	Heading,
	Highlight,
	HorizontalLine,
	HtmlEmbed,
	Image,
	ImageCaption,
	ImageInsert,
	ImageResize,
	ImageStyle,
	ImageToolbar,
	ImageUpload,
	Indent,
	IndentBlock,
	Italic,
	Link,
	LinkImage,
	List,
	ListProperties,
	MediaEmbed,
	MediaEmbedToolbar,
	Mention,
	Paragraph,
	PasteFromOffice,
	RemoveFormat,
	ShowBlocks,
	SimpleUploadAdapter,
	SourceEditing,
	SpecialCharacters,
	SpecialCharactersArrows,
	SpecialCharactersCurrency,
	SpecialCharactersEssentials,
	SpecialCharactersLatin,
	SpecialCharactersMathematical,
	SpecialCharactersText,
	Strikethrough,
	Style,
	Subscript,
	Superscript,
	Table,
	TableCaption,
	TableCellProperties,
	TableColumnResize,
	TableProperties,
	TableToolbar,
	TextPartLanguage,
	TextTransformation,
	TodoList,
	Underline,
	WordCount
} from 'ckeditor/ckeditor5';
import SimpleButton from 'ckeditor/simplebutton/simple-button-plugin';
import elgg from 'elgg';
	
var topbar_height = $('.elgg-page-topbar').height();

export default {
	licenseKey: 'GPL', // Or '<YOUR_LICENSE_KEY>'.
	plugins: [
		Alignment,
    	Autoformat,
    	AutoImage,
    	AutoLink,
    	BlockQuote,
    	Bold,
    	Code,
    	CodeBlock,
    	Essentials,
    	FindAndReplace,
    	Font,
    	GeneralHtmlSupport,
    	Heading,
    	Highlight,
    	HorizontalLine,
    	HtmlEmbed,
    	Image,
    	ImageCaption,
    	ImageInsert,
    	ImageResize,
    	ImageStyle,
    	ImageToolbar,
    	ImageUpload,
    	Indent,
    	IndentBlock,
    	Italic,
    	Link,
    	LinkImage,
    	List,
    	ListProperties,
    	MediaEmbed,
    	MediaEmbedToolbar,
    	Mention,
    	Paragraph,
    	PasteFromOffice,
    	RemoveFormat,
    	ShowBlocks,
    	SimpleButton,
    	SimpleUploadAdapter,
    	SourceEditing,
    	SpecialCharacters,
    	SpecialCharactersArrows,
    	SpecialCharactersCurrency,
    	SpecialCharactersEssentials,
    	SpecialCharactersLatin,
    	SpecialCharactersMathematical,
    	SpecialCharactersText,
    	Strikethrough,
    	Style,
    	Subscript,
    	Superscript,
    	Table,
    	TableCaption,
    	TableCellProperties,
    	TableColumnResize,
    	TableProperties,
    	TableToolbar,
    	TextPartLanguage,
    	TextTransformation,
    	TodoList,
    	Underline,
    	WordCount
	],
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
