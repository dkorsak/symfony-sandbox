CKEDITOR.editorConfig = function( config ) {
    config.emailProtection = 'encode';
    config.filebrowserBrowseUrl = '/bundles/appbackend/js/kcfinder/browse.php?type=files';
    config.filebrowserImageBrowseUrl = '/bundles/appbackend/js/kcfinder/browse.php?type=images';
    config.filebrowserFlashBrowseUrl = '/bundles/appbackend/js/kcfinder/browse.php?type=flash';
    config.filebrowserUploadUrl = '/bundles/appbackend/js/kcfinder/upload.php?type=files';
    config.filebrowserImageUploadUrl = '/bundles/appbackend/js/kcfinder/upload.php?type=images';
    config.filebrowserFlashUploadUrl = '/bundles/appbackend/js/kcfinder/upload.php?type=flash';
};
