/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	
	config.uiColor = '#ccc';
	//config.contentsCss = ['/path/to/css/file','s'];
	//config.customConfig = '/path/to/custom/config'
	config.toolbar = 'Koolah';
    config.toolbar_Koolah = [
        { name: 'tools',       items : [ 'Maximize', '-','About' ] },
        { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteFromWord','-','Undo','Redo' ] },
        { name: 'insert',      items : [ 'Image', 'Table','HorizontalRule','SpecialChar' ] },
        { name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        { name: 'orientation', items : [ 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl'] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','Link','Unlink','Anchor'] },
        { name: 'styles',      items : [ 'Styles','Format','FontSize' ] },
        { name: 'colors',      items : [ 'TextColor','BGColor' ] }
    ];
        
};
