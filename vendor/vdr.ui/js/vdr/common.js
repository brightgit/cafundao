$(document).ready(function() {
    !verboseBuild || console.log('-- starting vdr.common build');
    vdr.common.build();
});

vdr.common = {
	build: function () {
		// Initiate common events
		vdr.common.events();

		// enables tooltips
		vdr.common.enableTooltips();

		// enables ScrollSpy
		vdr.common.enableScrollSpy();

		// On window resize end (throttle protected) execute vdr.commonOnResizeEnd function
		var resizeEnd;
		var resizeThrottleBuffer = 50;
		$(window).resize(function() {
			clearTimeout(resizeEnd);
			resizeEnd = setTimeout(function () {
				vdr.common.onResizeEnd();
			}, resizeThrottleBuffer);
		});

		
		if(ltIE9 || Modernizr.mq('(min-width:' + (screenXs) + 'px)')){
			setTimeout(function() {
				$('.sidebar').addClass('animated fadeInLeft');
				setTimeout(function() {
					$('.sidebar').removeClass('animated fadeInLeft').css('opacity', '1');
				}, 1050);
			}, 50);
			setTimeout(function() {
				$('.wrapper').addClass('animated fadeInRight');
				setTimeout(function() {
					$('.wrapper').removeClass('animated fadeInRight').css('opacity', '1');
				}, 1050);
			}, 150);
		}
		else{
			setTimeout(function() {
				$('.sidebar, .wrapper').addClass('animated fadeInUp');
				setTimeout(function() {
					$('.sidebar, .wrapper').removeClass('animated fadeInUp').css('opacity', '1');
				}, 1050);
			}, 50);
		}

		!verboseBuild || console.log('            vdr.common build DONE');
	},
	events : function () {
		!verboseBuild || console.log('            vdr.common binding events');

		// Disables scroll except for allowed elements that prevent touchmove event propagation
		$(document).on('touchmove', function(event){
			event.preventDefault();
		});
		// Elements which are allowed touchmove event (by stopping event propagation to document)
		$('body').on('touchmove','.scrollable, nav', function(event) {
			event.stopPropagation();
		});
		// Prevents scrollable elements from ever reaching the end of scroll, and thus prevents scroll overflow on ipad
		$('body').on('touchstart','.scrollable', function(event) {
			if (event.currentTarget.scrollTop === 0) {
				event.currentTarget.scrollTop = 1;
			}
			else if (event.currentTarget.scrollHeight === event.currentTarget.scrollTop + event.currentTarget.offsetHeight) {
				event.currentTarget.scrollTop -= 1;
			}
		});
		// $('.wrapper input').eq(0).focus(); // if you want to scroll using keyboard on doc ready
	},
	onResizeEnd : function () {
		!verboseBuild || console.log('Window size changed');
		!verboseBuild || console.log('            vdr.common.onResizeEnd()');
		
		// if current page has a user menu, move the element when entering mobile mode
		!vdr.userNav || vdr.userNav.shuffleUserNav();

		// if current page is a dashboard, fill the row with widget placeholder if there are not enough real widgets
		!vdr.dashboard || vdr.dashboard.setBlankWidgets();

		// if current page has graphs, redraw on resize end
		setTimeout(function() {
			!(vdr.graphsStats && vdr.graphsStats.redrawCharts) || vdr.graphsStats.redrawCharts();
			!(vdr.userProfile && vdr.userProfile.redrawCharts) || vdr.userProfile.redrawCharts();
		}, 1000);

		// adjust sidebar CSS for mobile mode change
		!vdr.sidebar || vdr.sidebar.retractOnResize()
		!vdr.sidebar || vdr.sidebar.setSidebarMobHeight()
	},
	enableTooltips : function () {
		!verboseBuild || console.log('            vdr.common.enableTooltips()');
		
		// Activate tooltips on all elements with class .uses-tooltip
		$('.uses-tooltip').tooltip({'container' : 'body'});
		$('.progress-bar').each(function(index, el) {
			var progress = Math.round(parseInt($(this).css('width')) / parseInt($(this).parent().css('width')) * 100) + '%';
			$(this).tooltip({
				'container' : 'body',
				'title' : progress
			})
		});
	},
	enableScrollSpy : function () {
		// !verboseBuild || console.log('            vdr.common.enableScrollSpy()');
		
		// setTimeout(function() {
		// 	console.log('spy-init')
		// 	$('.wrapper').scrollspy();
		// 	$('.wrapper').on('activate.bs.scrollspy', function () {
		// 	  console.log('spy')
		// 	})
		// }, 3000);		
	}
}