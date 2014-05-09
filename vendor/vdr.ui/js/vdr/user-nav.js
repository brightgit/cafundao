$(document).ready(function() {
    !verboseBuild || console.log('-- starting vdr.userNav build');
    vdr.userNav.build();
});

vdr.userNav = {
	build: function () {
		// Initiate userNav events
		vdr.userNav.events();

		// Check screen size, shuffle user nav if needed
		vdr.userNav.shuffleUserNav();

		// Bounce notification counter
		setTimeout(function() {
			vdr.userNav.bounceCounter();
		}, 3000);

		!verboseBuild || console.log('            vdr.userNav build DONE');
	},
	events : function () {
		!verboseBuild || console.log('            vdr.userNav binding events');

		$(document).on('click', '.user-menu-wrapper a', function(event) {
			var viewToToggle = $(this).attr('data-expand');
			if($(this).is('.unread')){
				$(this).removeClass('unread');
				$(this).find('.menu-counter').fadeOut('100', function() {
					$(this).remove();
				});
			}
			$('nav.main-menu').removeClass('expanded');
			$('.main-menu-access').removeClass('active');
			$('nav.user-menu > section .active').not(this).removeClass('active');
			$(this).toggleClass('active');
			$('.nav-view').not(viewToToggle).fadeOut(60);
			setTimeout(function() {
				$(viewToToggle).fadeToggle(60);
			}, 60);
			return false;
		});

		$(document).on('click', '.close-user-menu', function(event) {
			$('nav.user-menu > section .active').removeClass('active');
			$('.nav-view').fadeOut(30);
		});

		$(document).on('click', '.theme-view li', function(event) {
			var theme = $(this).attr('data-theme');
			$('body').removeClass (function (index, css) {
			    return (css.match (/\btheme-\S+/g) || []).join(' ');
			});
			$.cookie('vdrTheme', theme, {
			    expires: 7,
			    path: '/'
			});
			if (theme === 'default') return;
			$('body').addClass(theme);
		});
		
	},
	shuffleUserNav : function () {
		!verboseBuild || console.log('            vdr.userNav.shuffleUserNav()');

		if(ltIE9 || Modernizr.mq('(min-width:' + (screenXs) + 'px)')){
			$('body > .user-menu').prependTo('.wrapper');
		}
		else{
			$('.wrapper .user-menu').prependTo('body');
		}
	},
	bounceCounter : function () {
		// !verboseBuild || console.log('            vdr.userNav.bounceCounter()');
		
		if(!$('.menu-counter').length)
			return;
		$('.menu-counter').toggleClass('animated bounce');
		setTimeout(function() {
			$('.menu-counter').toggleClass('animated bounce');
		}, 1000);
		setTimeout(function() {
			vdr.userNav.bounceCounter();
		}, 5000);
	}
}