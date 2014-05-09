$(document).ready(function() {
    !verboseBuild || console.log('-- starting vdr.intro build');
    
    vdr.intro.build();
});

vdr.intro = {
	build: function () {
		if (!$('.sidebar').is('.extended')){
			vdr.sidebar.toogleSidebar();
		}
		setTimeout(function() {
			introJs().setOptions({'showStepNumbers': false}).start();
		}, 1300);
	}	
}