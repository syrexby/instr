$.fn.productGallery = function () {
    'use strict';

    var $obj = $(this),
        $main = $('[data-product-image]', $obj),
        fancyboxOptions,
        isThumbnail,
        imageBox,
        imageBox2,
        isLocked = false,
        index = 0;

    fancyboxOptions = {
        padding: 0,
        animationEffect : "fade",
        buttons : [
            'close',
            'thumbs',
            ],
        thumbs : {
            autoStart   : true,
            hideOnClose : true
        },
        helpers: {
            media: {},
            title: {
                type: 'outside'
            },
            thumbs: {
                width: 70,
                height: 70
            }
        },
        beforeLoad: function (instance, current ) {


        }
    };

    $('.product__nav-item', $obj).on('click mouseenter touch', function (e) {
        e.preventDefault();

        var $el = $(this);
        $('[data-product-thumbnail]', $obj).removeClass('active');
        $el.addClass('active');

        index = $el.closest('.owl-item').index();
        $main
            .attr('data-index', index)
            .find('img')
            .attr('src', $el.attr('href'))
            .attr('alt', $el.attr('title'));
    });

    if($('[data-product-thumbnail]', $obj).length){
        imageBox = $('[data-product-thumbnail]', $obj).fancybox(fancyboxOptions);

        $main.on('click touch', function (e) {
            e.preventDefault();
            $.fancybox.open(imageBox, fancyboxOptions, index);
        });
        $('.product__nav-item', $obj).on('click touch', function (e) {
            e.preventDefault();
            $.fancybox.open(imageBox, fancyboxOptions, index);
        });

    } else{
        imageBox = $('.product__img-block', $obj).fancybox(fancyboxOptions);
    }




};