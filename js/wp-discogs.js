jQuery(document).ready(function($){

    $(".fancybox").fancybox({
        openEffect  : 'none',
        closeEffect : 'none',
        prevEffect  : 'none',
        nextEffect  : 'none',
        maxWidth    : 600,
        maxHeight   : 400,
        fitToView   : true,
        width       : '80%',
        height      : '80%',
        autoSize    : false,
        helpers     : {
            media  : {},
            overlay: {
                locked: false
            }
        },
    });

});
