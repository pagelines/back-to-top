jQuery(document).ready(function($) {
    $.scrollUp({
        scrollName: 'button-back-to-top',
        scrollText: ('image' === pagelines_scroll_up.style) ? '' : pagelines_scroll_up.text,
        scrollImg: ('image' === pagelines_scroll_up.style) ? true : false,
        zIndex: parseInt(pagelines_scroll_up.zIndex)
    });
});