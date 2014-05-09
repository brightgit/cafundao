$(document).ready(function() {
    !verboseBuild || console.log('-- starting vdr.uiComponentsGeneral build');
    
    vdr.uiComponentsGeneral.build();
});

vdr.uiComponentsGeneral = {
	build: function () {
		// Initiate all events
		vdr.uiComponentsGeneral.events();

		// Set up notifications
		vdr.uiComponentsGeneral.notifications();
		
		// Initiate Star Rating Plugin
		vdr.uiComponentsGeneral.starRating();

		// Initiate Farbtastic Color Picker
		!($.farbtastic && $('.colorpicker-binder').length) || $('.colorpicker-binder').farbtastic('#color');

		!verboseBuild || console.log('            vdr.uiComponentsGeneral build DONE');
	},
	events : function () {
		!verboseBuild || console.log('            vdr.uiComponentsGeneral binding events');

	},
	notifications : function () {
		!verboseBuild || console.log('            vdr.uiComponentsGeneral.notifications()');

		$.pnotify.defaults.delay = 3000;
		$.pnotify.defaults.shadow = false;
		$.pnotify.defaults.cornerclass = 'ui-pnotify-sharp';
		$.pnotify.defaults.stack = {"dir1": "down", "dir2": "left", "push": "bottom", "spacing1": 5, "spacing2": 5};
	},
	starRating : function () {
		!verboseBuild || console.log('            vdr.uiComponentsGeneral.starRating()');

		$('#basic-stars-demo').raty({
			starOff : 'icon-star dimmed',
			cancelOff : 'icon-remove-sign dimmed',
		});
		$('#half-cancel-stars-demo').raty({
			cancel     : true,
			cancelPlace: 'right',
			starOff : 'icon-star dimmed',
			cancelOff : 'icon-remove-sign dimmed',
			half: true
		});
		$('#ten-stars-stars-demo').raty({
			cancel     : true,
			cancelPlace: 'right',
			starOff : 'icon-star dimmed',
			cancelOff : 'icon-remove-sign dimmed',
			number : 10,
			hints : false
		});
		$('#readonly-stars-demo').raty({
			starOff : 'icon-star dimmed',
			cancelOff : 'icon-remove-sign dimmed',
			half: true,
			readOnly: true,
			score: 4
		});
	}
}