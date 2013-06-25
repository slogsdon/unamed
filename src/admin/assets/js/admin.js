(function (window, document, $, undefined) {
	$(document).ready(function () {

        // posts-edit
        if ($('.posts-edit').length > 0) {
            $('.btn-toggle-meta').click(function () {
                var $this = $(this),
                    currentText = $('span', $this).text();
                $('.meta-info').slideToggle();
                $('.lbl', $this).text(currentText.toLowerCase() == 'show' ? 'Hide' : 'Show');
                $('i', $this).attr('class', 'icon-chevron-' + (currentText.toLowerCase() == 'show' ? 'up' : 'down'));
            });
            $('input[name="post_name"]').focus(function () {
                $(this).attr('class', '');
            });
            $('input[name="post_name"]').focusout(function () {
                $(this).attr('class', 'just-text');
            });
            $('.form-horizontal input[type="text"]').focus(function () {
                $(this).attr('class', '');
            });
            $('.form-horizontal input[type="text"]').focusout(function () {
                $(this).attr('class', 'no-border');
            });
            tinymce.init({selector:'.posts-edit textarea[name="post_content"]'});
            tinymce.init({selector:'.posts-edit textarea[name="post_excerpt"]'});
            $('input[name="use-post-excerpt"]').change(function () {
                $('.post-excerpt-container').slideToggle();
            });
        }

	});
})(window, document, jQuery);