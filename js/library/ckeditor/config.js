/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    config.removePlugins = 'save';
    // config.extraPlugins = 'html5audio,html5video';
    config.extraPlugins = 'youtube,sourcearea';
    config.allowedContent = true;
config.extraPlugins = 'codesnippet';



};

