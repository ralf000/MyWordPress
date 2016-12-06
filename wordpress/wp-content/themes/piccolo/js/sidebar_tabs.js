/**
 * Created by kudinov on 02.12.2016.
 */

function makeTabs() {
    var output = '';
    $.each($('.tab-content .tab-pane'), function (key, val) {
        var t = $(this);
        var id = t.attr('id');
        var title = t.find('.widgettitle').text();
        output +=
            '<li>' +
            '<a href="#' + id + '" data-toggle="tab">' + title + '</a>' +
            '</li>';
    });
    return output;
}

$(function () {
    var tabs = makeTabs();
    $('ul.nav.nav-tabs').append(tabs);
    $('.nav-tabs a:first').tab('show');
});