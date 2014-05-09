$(document).ready(function() {
    !verboseBuild || console.log('-- starting vdr.dashboard build');
    vdr.dashboard.build();
});

vdr.dashboard = {
	build: function () {
		//vdr.dashboard.events();
		//vdr.dashboard.quickLaunchSort();
		vdr.dashboard.loadWidgetPositions()
		vdr.dashboard.setBlankWidgets();
		vdr.dashboard.widgetSort();
		vdr.dashboard.drawCharts();
		vdr.dashboard.select2();
		vdr.dashboard.lightUp();
		//vdr.dashboard.alerts();

		!verboseBuild || console.log('            vdr.dashboard build DONE');

		// Morris Charts
	},
	events : function () {
		!verboseBuild || console.log('            vdr.dashboard binding events');

		// toggle dashboar menu
		$(document).on('click', '.dashboard-menu', function(event) {
			event.preventDefault();
			$(this).toggleClass('expanded');
			$('.menu-state-icon').toggleClass('active');
		});
		// toggle widget setup state
		$(document).on('click', '.toggle-widget-setup', function(event) {
			event.preventDefault();
			$(this).parents('.vdr-widget').toggleClass('setup');
		});
	},
	quickLaunchSort : function () {
		!verboseBuild || console.log('            vdr.dashboard.quickLaunchSort()');

		vdr.dashboard.isDragActive = false;
		$( ".quick-launch-bar ul" ).sortable({
		    containment: 'parent',
		    tolerance: 'pointer',
		    start: function(event, ui) {
		        vdr.dashboard.isDragActive = true;
		        $('.tooltip').tooltip('hide');
		    },
		    stop: function(event, ui) {
		        vdr.dashboard.isDragActive = false;
		    }
		});
	},
	lightUp : function () {
		!verboseBuild || console.log('            vdr.dashboard.lightUp()');

		var numWidgets = $('.vdr-widget').length;
		var currentWidget = 0;
		setTimeout(showWidget, 200);

		function showWidget () {
			$('.vdr-widget').eq(currentWidget).addClass('lit');
			if(currentWidget == numWidgets) return;
			currentWidget++;
			setTimeout(showWidget, 100);
		}
	},
	widgetPositions : [],
	loadWidgetPositions : function () {
		!verboseBuild || console.log('            vdr.dashboard.loadWidgetPositions()');
		var positionArray = vdr.dashboard.widgetPositions;
		var positionsFromCookie = $.cookie('vdr_widgetPositions') || false;
		if(positionsFromCookie){
			positionArray = positionsFromCookie.split(',');
			$.each(positionArray, function(index, val) {
				$('#' + val).appendTo('.widget-group');
			});
		}
		else vdr.dashboard.saveWidgetPositions();
	},
	saveWidgetPositions : function () {
		!verboseBuild || console.log('            vdr.dashboard.saveWidgetPositions()');
		
		var positionArray = vdr.dashboard.widgetPositions = [];
		$('.vdr-widget').not('.placeholder').each(function(index, el) {
			var wid = $(el).attr('id');
			positionArray.push(wid);
		});
		$.cookie('vdr_widgetPositions', positionArray, {
	        expires: 365,
	        path: '/'
	    });
	},
	widgetSort : function () {
		!verboseBuild || console.log('            vdr.dashboard.widgetSort()');

		vdr.dashboard.isDragActive = false;
		$( ".widget-group" ).sortable({
		    cancel: '.placeholder, .flip-it',
		    placeholder: 'drag-placeholder',
		    start: function(event, ui) {
		        vdr.dashboard.isDragActive = true;
		        $('.tooltip').tooltip('hide');
		    },
		    stop: function(event, ui) {
		        vdr.dashboard.saveWidgetPositions();
		        vdr.dashboard.isDragActive = false;
		    },
		    tolerance: 'pointer',
		    handle: ".panel-heading"
		});
	},
	setBlankWidgets: function () {
		!verboseBuild || console.log('            vdr.dashboard.setBlankWidgets()');

		var realWidgetNum = $('.vdr-widget').not('.placeholder').length;
		var placeholderNum = $('.vdr-widget.placeholder').length;

		var availableWidth = $('.widget-group').width();
		var widgetWidth = $('.vdr-widget').outerWidth(true);
		var widgetsPerRow = Math.floor(availableWidth / widgetWidth);
		var widgetRows = Math.ceil(realWidgetNum / widgetsPerRow);

		var newPlaceholderNum = (widgetRows * widgetsPerRow) - realWidgetNum;

		$('.vdr-widget.placeholder').appendTo('.widget-group');
		if(newPlaceholderNum === placeholderNum){
			return;
		}
		if(newPlaceholderNum <= placeholderNum){
			for (var i = placeholderNum - newPlaceholderNum; i > 0; i--) {
			    $('.vdr-widget.placeholder').last().remove();
			}
			return;
		}
		if(newPlaceholderNum >= placeholderNum){
			for (var i = newPlaceholderNum - placeholderNum; i > 0; i--) {
			    $('<div class="vdr-widget placeholder lit"></div>').appendTo('.widget-group');
			}
			return;
		}
	},
	graph : {},
	drawCharts : function () {
		!verboseBuild || console.log('            vdr.dashboard.drawCharts()');

		/*
		var tentativas = $("#hero-donut").data("tentativas");
		var terminadas = $("#hero-donut").data("terminadas");


		vdr.dashboard.graph.Donut = Morris.Donut({
		    element: 'hero-donut',
		    data: [
		      {label: 'Tentivas', value: tentativas },
		      {label: 'Terminadas', value: terminadas }
		    ],
		    formatter: function (y) { return y; },
		    colors : ['#428bca', '#5cb85c', '#d9534f', '#5bc0de']
		});
		*/
		var info = $("#hero-bar").data("info");
		alert(info);
		//var info_semana = $("#hero-bar-semana").data("info");

		vdr.dashboard.graph.Bar = Morris.Bar({
		    element: 'hero-bar',
		    data: info,
		    xkey: 'year',
		    ykeys: ['income'],
		    labels: ['Income'],
		    barRatio: 0.1,
		    xLabelAngle: 90,
		    hideHover: 'auto'
		});
		// vdr.dashboard.graph.Bar = Morris.Bar({
		//     element: 'hero-bar-semana',
		//     data: info_semana,
		//     xkey: 'year',
		//     ykeys: ['income'],
		//     labels: ['Income'],
		//     barRatio: 0.1,
		//     xLabelAngle: 90,
		//     hideHover: 'auto'
		// });
	},
	select2 : function () {
		!verboseBuild || console.log('            vdr.dashboard.select2()');
		
        $('.select2').select2({ maximumSelectionSize: 6 });
	},
	alerts : function () {
		!verboseBuild || console.log('            vdr.dashboard.alerts()');

		// Set up notifications
		$.pnotify.defaults.delay = 7000;
		$.pnotify.defaults.shadow = false;
		$.pnotify.defaults.cornerclass = 'ui-pnotify-sharp';
		$.pnotify.defaults.stack = {"dir1": "down", "dir2": "left", "push": "bottom", "spacing1": 5, "spacing2": 5};

		setTimeout(function(){
		    $.pnotify({
		        title: 'Drag & Drop',
		        type: 'success',
		        history: false,
		        text: 'Reorder Widgets or Quicklaunch bar items by dragging & dropping them.'
		    });
		}, 2000);
		setTimeout(function(){
		    $.pnotify({
		        title: 'Widget Settings',
		        type: 'info',
		        history: false,
		        text: 'Hover over widget, than click on a gear icon to set widget options.'
		    });
		}, 8000);
	}
}