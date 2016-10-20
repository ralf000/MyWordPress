jQuery(function($){

    $('.carousel').homecarousel();
    $('.page-gallery').imagegallery();

    // $('.main-nav li.popup a, .footer-text .popup').magnificPopup({
    //   type: 'inline',
    //   removalDelay: 300,
    //   mainClass: 'mfp-img-mobile',
    // });

    // $('.gallery-thumbnails').magnificPopup({
    //     delegate: 'a',
    //     type: 'image',
    //     closeOnContentClick: false,
    //     mainClass: 'mfp-with-zoom mfp-img-mobile',
    //     removalDelay: 300,
    //     image: {
    //         verticalFit: true
    //     },
    //     gallery: {
    //         enabled: true,
    //         navigateByImgClick: true,
    //         preload: [0,1]
    //     },
    // });

    // HAMBURGER - - YUM
    $('.header .m').on('click', function(e) {
        e.preventDefault();
       $('html').toggleClass('nav-open');
       $(this).toggleClass('on');
    });

    // EXPANDABLES
    $('.ham-expand-wrapper').expand();


    jQuery(document).on('gform_post_render', function(){

    // Form Labels
        $('form').find('input,textarea').not('[type="checkbox"], [type="radio"]').each(function() {
            if($.trim( $(this).val() ) != "") $(this).addClass('focus');
        }).on('blur', function(){
            $(this).removeClass('focus');
            if($.trim( $(this).val() ) != "") $(this).addClass('focus');
        }).on('focus', function() {
            $(this).addClass('focus');
        });
    }).trigger('gform_post_render');

});