/**
 * @source: http://docs.cksource.com/CKEditor_3.x/Developers_Guide 
 */
CKEDITOR.editorConfig = function( config ){
    
    config.uiColor = '#ccc';
    //config.contentsCss = ['/path/to/css/file','s'];
    //config.customConfig = '/path/to/custom/config'
    config.toolbar = 'Koolah';
    config.toolbar_Koolah = [
        { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
        { name: 'forms',       items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
        '/',
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert',      items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
        '/',
        { name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
        { name: 'colors',      items : [ 'TextColor','BGColor' ] },
        { name: 'tools',       items : [ 'Maximize', 'ShowBlocks','-','About' ] }
    ];
};