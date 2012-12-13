/* ALL JAVASCRIPT FUNCTIONS FOR BACKEND */
jQuery(document).ready(function() {
	// action for show/hide filters
	jQuery('.show_filters').click(function() {
		jQuery('.filter_container').toggle();
		var $icon = jQuery(this).find('i');
		if ($icon.hasClass("icon-chevron-up")) {
			$icon.addClass("icon-chevron-down")
			$icon.removeClass("icon-chevron-up")
		} else {
			$icon.removeClass("icon-chevron-down")
			$icon.addClass("icon-chevron-up")
		}
	})
})