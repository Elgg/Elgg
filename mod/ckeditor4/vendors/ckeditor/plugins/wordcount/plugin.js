/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
 
CKEDITOR.plugins.add('wordcount', {
    lang: ['de', 'en', 'fr', 'pl'],
    init: function (editor) {
        
        var defaultFormat = '<span class="cke_path_item">';
        
        var intervalId;
        var lastWordCount = 0;
        var lastCharCount = 0;
        var limitReachedNotified = false;
        var limitRestoredNotified = false;
        
        // Default Config
        var defaultConfig = {
            showWordCount: true,
            showCharCount: false,
            charLimit: 'unlimited',
            wordLimit: 'unlimited'
        };
        
        // Get Config & Lang
        var config = CKEDITOR.tools.extend(defaultConfig, editor.config.wordcount || {}, true);
        
        if (config.showCharCount) {
            defaultFormat += editor.lang.wordcount.CharCount + '&nbsp;%charCount%';


            if (config.charLimit != 'unlimited') {
                defaultFormat += '&nbsp;(' + editor.lang.wordcount.limit + '&nbsp;' + config.charLimit + ')';
            }
        }

        if (config.showCharCount && config.showWordCount) {
            defaultFormat += ',&nbsp;';
        }

        if (config.showWordCount) {
            defaultFormat += editor.lang.wordcount.WordCount + ' %wordCount%';
            
            if (config.wordLimit != 'unlimited') {
                defaultFormat += '&nbsp;(' + editor.lang.wordcount.limit + '&nbsp;' + config.wordLimit + ')';
            }
        }
        
        defaultFormat += '</span>';
        
        var format = defaultFormat;

        CKEDITOR.document.appendStyleSheet(this.path + 'css/wordcount.css');

        function counterId(editor) {
            return 'cke_wordcount_' + editor.name;
        }

        function counterElement(editor) {
            return document.getElementById(counterId(editor));
        }

        function strip(html) {
            var tmp = document.createElement("div");
            tmp.innerHTML = html;

            if (tmp.textContent == '' && typeof tmp.innerText == 'undefined') {
               return '0';
            }
            return tmp.textContent || tmp.innerText;
        }
        
        function updateCounter(editor) {
            
            var wordCount = 0;
            var charCount = 0;

            if (editor.getData()) {
                var text = editor.getData().replace(/(\r\n|\n|\r)/gm, " ").replace(/(&nbsp;)/g, " ");
				
                if (config.showWordCount) {
					wordCount = strip(text).trim().split(/\s+/).length;
                }

                charCount = strip(text).trim().length;
            }
            var html = format.replace('%wordCount%', wordCount).replace('%charCount%', charCount);
            
            counterElement(editor).innerHTML = html;

            if (charCount == lastCharCount) {
                return true;
            } else {
                lastWordCount = wordCount;
                lastCharCount = charCount;
            }
            
            // Check for word limit
            if (config.showWordCount && wordCount > config.wordLimit) {
                limitReached(editor, limitReachedNotified);
            } else if (!limitRestoredNotified && wordCount < config.wordLimit) {
                limitRestored(editor);
            }
            
            // Check for char limit
            if (config.showCharCount && charCount > config.charLimit) {
                limitReached(editor, limitReachedNotified);
            } else if (!limitRestoredNotified && charCount < config.charLimit) {
                limitRestored(editor);
               
            }
            
            return true;
        }

        function limitReached(editor, notify) {
            limitReachedNotified = true;
            limitRestoredNotified = false;
            
            editor.execCommand('undo');
            if (!notify) {
                counterElement(editor).className += " cke_wordcountLimitReached";

                editor.fire('limitReached', {}, editor);
            }
            // lock editor
            editor.config.Locked = 1;
            editor.fire("change");
        }

        function limitRestored(editor) {
            limitRestoredNotified = true;
            limitReachedNotified = false;
            editor.config.Locked = 0;
            
            counterElement(editor).className = "cke_wordcount";
        }

        editor.on('uiSpace', function(event) {
            if (event.data.space == 'bottom') {
                event.data.html += '<div id="' + counterId(event.editor) + '" class="cke_wordcount" style=""' + ' title="' + editor.lang.wordcount.title + '"' + '>&nbsp;</div>';
            }
        }, editor, null, 100);
       editor.on('dataReady', function(event) {
            var count = event.editor.getData().length;
            if (count > config.wordLimit) {
                limitReached(editor);
            }
            updateCounter(event.editor);
        }, editor, null, 100);
		editor.on('key', function (event) {
		    updateCounter(event.editor);
		}, editor, null, 100);
		editor.on('afterPaste', function (event) {
		    updateCounter(event.editor);
		}, editor, null, 100);
       /* editor.on('change', function (event) {
            updateCounter(event.editor);
        }, editor, null, 100);*/
        editor.on('focus', function(event) {
            editorHasFocus = true;
            intervalId = window.setInterval(function(editor) {
                updateCounter(editor);
            }, 100, event.editor);
        }, editor, null, 100);
        editor.on('blur', function() {
            editorHasFocus = false;
            if (intervalId) clearInterval(intervalId);
        }, editor, null, 100);
    }
});
