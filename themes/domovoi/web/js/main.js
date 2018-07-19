function showNotify(element, result, message) {
    $('#notifications').html('<div>' + message + '</div>').fadeIn().delay(3000).fadeOut();
}
$(document).ajaxError(function () {
    $('#notifications').html('<div>Произошла ошибка =(</div>').fadeIn().delay(3000).fadeOut();
});

function toTopAfterAjaxUpdatePagination() {
    $('.pagination>li>a').click(function () {
        var offset;
        var scroll_top_duration = 300;
        offset = $('.catalog__box').offset().top;

        setTimeout(
            function () {
                $('body,html').animate({
                        scrollTop: offset,
                    }, scroll_top_duration
                )
            },
            300);

    });
}
$( document ).ready(function() {
    toTopAfterAjaxUpdatePagination();

    $('.product-tabs').tabs();
});


(function($) {
    $.fn.searchRender = function(){
        var container = $('.search-block ul'),
            item = '<li>' +
                '<a href="">' +
                '<span class="search-img">' +
                '<img src="" title="" />' +
                '</span>' +
                '<span class="search-name"></span>' +
                '<div class="search-priceblock">' +
                '<span class="search-price"></span>' +
                '<span class="search-stock"></span>' +
                '</div>' +
                '</a></li>';
        item = $(item);
        console.log(this[0]);
        item.find('a').attr('href', this[0].url);
        item.find('img').attr('src', this[0].img).attr('title', this[0].name);
        item.find('.search-name').text(this[0].name);
        item.find('.search-price').text(this[0].price);
        if(this[0].instock === '1'){
            item.find('.search-stock').addClass('in-stock').text('В наличии');
        } else{
            item.find('.search-stock').addClass('not-in-stock').text('Под заказ');
        }
        container.append(item);
    };
})(jQuery);

function searchTrigger(e){
    $('.quick-search').removeClass('visible');
    $('body').off('click', searchTrigger);
}

var ajaxSearch = function (e){
    var url = '/store/search',
        data = 'q=' + e.target.value;
    // console.log('search');
    $.ajax({
        type: 'get',
        data: data,
        dataType: 'json',
        url: url,
        success: function (data) {
            $('.search-block ul').empty();
            $(data).each(function (i, el) {
                $(el).searchRender();
            });
            $('.quick-search').addClass('visible');
            $('body').on('click', searchTrigger);
            // showNotify(button, data.result ? 'success' : 'danger', data.data);
        }
    });
};
var ajaxTimer;
$('#q').on('input', function (e) {
    e.preventDefault();
    if(ajaxTimer) clearTimeout(ajaxTimer);
    ajaxTimer = setTimeout(ajaxSearch, 400, e);
});

function onSubmitReCaptcha(token) {
    var idForm = '#feedback-form';
    formSend(idForm);
}
function formSubmit(token) {
    console.log('formSubmit');
    // grecaptcha.execute();
}
function formCheck(form, data, hasError) {
    console.log('formCheck');
    if (hasError) {
        // grecaptcha.reset();
        return false;
    }

    grecaptcha.execute();
}
function formSend() {
    console.log('formSend');

    var form = $('#feedback-form');
    console.log(form);
    $.ajax({
        method: 'POST',
        url: form[0].action,
        data: form.serialize(),
        success: function (response) {
            console.log(response);
            if (response.result) {
                form[0].reset();
            }
            $('#notifications').html('<div>' + response.data + '</div>').fadeIn().delay(3000).fadeOut();
            grecaptcha.reset();
        },
        error: function (response) {
            $('#notifications').html('ERROR <br/>' + response.data).fadeIn().delay(3000).fadeOut();
            // grecaptcha.reset();
        }
    });

    return false;
}
$(function() {

});

function mapOnLoad(){
    $('.loader').fadeOut(300);
}