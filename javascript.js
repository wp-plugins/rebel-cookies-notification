jQuery(document).ready(function() {
	var wrapper = jQuery('#rebel_cookie_notification_bar_id');
	var openbar = jQuery('#open_rebel_cookie_notification_bar_id');

	jQuery('.close').click(function() {
		wrapper.fadeOut(function() {
			openbar.fadeIn();
			jQuery.cookie("rebel_cookie_bar", "close", { path: '/' });
		});
		return false;
	});

	openbar.click(function() {
		openbar.fadeOut(function() {
			wrapper.fadeIn();
			jQuery.cookie("rebel_cookie_bar", null, { path: '/' });
		});
		return false;
	});

	if(jQuery.cookie("rebel_cookie_bar") === 'close') {
		wrapper.hide();
		openbar.show();
	} else {
		wrapper.show();
		openbar.hide();
	}
	
});


