# CKEditor 5

This dependency is a custom build of ckeditor. Using NPM and Webpack you can update ckeditor.

 - ``npm install``
 - change required major/minor version of the ckeditor dependencies
 - ``npm update``
 - ``yarn run build``

Make sure you only commit changes in the `build` folder.

With a configuration in the `webpack.config.js` we extract all editor and content css into `build/styles.css`.

If you need to update the css for Elgg, you will need to copy the `styles.css` contents 
to `views/default/ckeditor/editor.css` without all `.ck-content` definitions. 
The content related styling resides in the `ckeditor/content.css` view. 
You can update those using: https://ckeditor.com/docs/ckeditor5/latest/installation/advanced/content-styles.html#the-full-list-of-content-styles

The `blockquote` styling is removed as that is already provided by Elgg.
Also the mentions styling ``-ck-color-mention-*`` is changed to be more inline with default Elgg styling.
