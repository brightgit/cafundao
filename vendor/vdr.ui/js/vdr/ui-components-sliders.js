$(document).ready(function() {
    !verboseBuild || console.log('-- starting vdr.uiComponentsSliders build');
    
    vdr.uiComponentsSliders.build();
});

vdr.uiComponentsSliders = {
	build: function () {
		// Initiate Sliders
		$('.bslider').slider();
		
		!verboseBuild || console.log('            vdr.uiComponentsSliders build DONE');
	}
}