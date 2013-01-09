/* ALL JAVASCRIPT FUNCTIONS FOR BACKEND */
jQuery(document).ready(function() {

    var toggleUpDownIcon = function(subject) {

        var $icon = jQuery(subject).find('i');
        if ($icon.hasClass("icon-chevron-up")) {
            $icon.addClass("icon-chevron-down");
            $icon.removeClass("icon-chevron-up");
        } else {
            $icon.removeClass("icon-chevron-down");
            $icon.addClass("icon-chevron-up");
        }
    }

    // show/hide filters
    jQuery('.show_filters').click(function() {
        jQuery('.filter_container').toggle();
        toggleUpDownIcon(this);
        
    })
    // show/hide form fieldset
    jQuery('.form_box_fieldset-collapsed').not(':has(.error)').find('.box-content').hide();
    jQuery('.form_box_fieldset-collapsed').has('.error').find('div.box-icon i').removeClass('icon-chevron-down').addClass('icon-chevron-up');
    jQuery('.form_box_fieldset-collapsed .box-header').live("click", function(){
        toggleUpDownIcon(this);
        jQuery(this).next().toggle();
    });
})