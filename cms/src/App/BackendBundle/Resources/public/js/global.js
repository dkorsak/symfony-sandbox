/* ALL JAVASCRIPT FUNCTIONS FOR BACKEND */
jQuery(document).ready(function() {
    // stop double form submit
    jQuery('.sonata-ba-form form').submit(function() {
        if ("on-submit" == jQuery(this).data("on-sumbit")) {
            return false;
        }
        jQuery(this).data("on-sumbit", "on-submit");
    });
    jQuery(document).ajaxStart(function() {
        jQuery('#ajax_loader').show();
    });

    jQuery(document).ajaxStop(function() {
        jQuery('#ajax_loader').hide();
    });

    Admin.setup_list_modal = function(modal) {};
});