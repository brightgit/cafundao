$(document).ready(function() {
    !verboseBuild || console.log('-- starting vdr.dashdemo build');
    vdr.dashdemo.build();
});

vdr.dashdemo = {
	build: function () {
		setTimeout(function() {
			var width = $(".widget-progress-bar .progress-bar").attr("aria-valuenow");
			$('.widget-progress-bar .progress-bar').css({
				width: width+'%'
			});
		}, 800);

		setTimeout(function() {
			//vdr.dashdemo.newMessage();
		}, 3500);

		setTimeout(function() {
			//vdr.dashdemo.newUser();
		}, 4500);

		!verboseBuild || console.log('            vdr.dashdemo build DONE');
	},

	newMessageTimer : false,
	//messageTemplate : '<li class="list-group-item new-item generated-item"><i><img src="images/user-icons/user{{userNum}}.jpg" alt="User Icon"></i><div class="text-holder"><span class="title-text">{{from}}:</span><span class="description-text">{{content}}</span></div><span class="stat-value">a minut ago</span></li>',
	newMessage : function () {
		return false;
		var messageWidgetFlipped = false;
		if (vdr.dashdemo.newMessageTimer){
			clearTimeout(vdr.dashdemo.newMessageTimer);
		}

		var f = vdr.dashdemo.randomNum(0,9);
		var l = vdr.dashdemo.randomNum(0,9);
		var g = vdr.dashdemo.randomNum(0,9);
		var c = vdr.dashdemo.randomNum(0,14);
		var newMessage = vdr.dashdemo.messageTemplate;
		newMessage = newMessage.replace("{{userNum}}", vdr.dashdemo.randomNum(1,10));
		newMessage = newMessage.replace("{{from}}", vdr.dashdemo.firstname[f] + ' ' + vdr.dashdemo.lastname[l]);
		newMessage = newMessage.replace("{{content}}", vdr.dashdemo.greeting[g] + ' ' + vdr.dashdemo.content[c]);
		$(newMessage).prependTo('.messages .front .list-group');

		setTimeout(function() {
			if ($('.messages').is('.setup'))
				messageWidgetFlipped = true;
			$('.messages .front .list-group .generated-item').eq(1).find('.stat-value').text('2 mins ago');
			$('.messages .front .list-group .generated-item').eq(2).find('.stat-value').text('3 mins ago');
			$('.messages .front .list-group .generated-item').eq(3).find('.stat-value').text('5 mins ago');
			$('.messages .front .list-group .generated-item').eq(4).find('.stat-value').text('6 mins ago');
			$('.messages .front .list-group li').eq(5).remove();
			var $new = $('.messages .new-item');
			$new.removeClass('new-item');
			setTimeout(function() {
				!!messageWidgetFlipped || $new.toggleClass('animated flash');
				!!messageWidgetFlipped || setTimeout(function() {
					$new.toggleClass('animated flash');
				}, 1000);
			}, 100);
		}, 1000);

		vdr.dashdemo.newMessageTimer = setTimeout(vdr.dashdemo.newMessage, 1000 * vdr.dashdemo.randomNum(5,12));
	},

	newUserTimer : false,
	newUserCount : 2512,
	userTemplate : '<li class="list-group-item new-item generated-item"><i><img src="images/user-icons/user{{userNum}}.jpg" alt="User Icon"></i><div class="text-holder"><span class="title-text">{{from}}</span></div><span class="stat-value">a minut ago</span></li>',
	newUser : function () {
		var statWidgetFlipped = false;
		var latestWidgetFlipped = false;
		if (vdr.dashdemo.newUserTimer){
			clearTimeout(vdr.dashdemo.newUserTimer);
		}

		var f = vdr.dashdemo.randomNum(0,9);
		var l = vdr.dashdemo.randomNum(0,9);
		var newUser = vdr.dashdemo.userTemplate;
		newUser = newUser.replace("{{userNum}}", vdr.dashdemo.randomNum(1,10));
		newUser = newUser.replace("{{from}}", vdr.dashdemo.firstname[f] + ' ' + vdr.dashdemo.lastname[l]);
		$(newUser).prependTo('.latest-users .front .list-group');

		setTimeout(function() {
			vdr.dashdemo.newUserCount++;
			$('.latest-users .front .list-group .generated-item').eq(1).find('.stat-value').text('2 mins ago');
			$('.latest-users .front .list-group .generated-item').eq(2).find('.stat-value').text('3 mins ago');
			$('.latest-users .front .list-group .generated-item').eq(3).find('.stat-value').text('5 mins ago');
			$('.latest-users .front .list-group .generated-item').eq(4).find('.stat-value').text('5 mins ago');
			$('.latest-users .front .list-group .generated-item').eq(5).find('.stat-value').text('6 mins ago');
			$('.latest-users .front .list-group li').eq(6).remove();
			
			$userCount = $('.general-stats .front .list-group li').eq(0);
			// check if widgets are flipped, disable flash animation if true
			if ($('.general-stats').is('.setup'))
				statWidgetFlipped = true;
			if ($('.latest-users').is('.setup'))
				latestWidgetFlipped = true;

			!!statWidgetFlipped || $userCount.toggleClass('animated flash');
			!!statWidgetFlipped || setTimeout(function() {
				$userCount.toggleClass('animated flash');
			}, 1000);
			setTimeout(function() {
				$userCount.find('.title-text').text(numeral(vdr.dashdemo.newUserCount).format('0,0'));
			}, 200);

			var $new = $('.latest-users .new-item');
			$new.removeClass('new-item');
			!!latestWidgetFlipped || $new.toggleClass('animated flash');
			!!latestWidgetFlipped || setTimeout(function() {
				$new.toggleClass('animated flash');
			}, 1000);
		}, 1000);

		vdr.dashdemo.newUserTimer = setTimeout(vdr.dashdemo.newUser, 1000 * vdr.dashdemo.randomNum(1,8));
	},

	randomNum : function (from,to) {
		return Math.floor(Math.random()*(to-from+1)+from);
	},
	
	firstname : ['Colin','Belshazzar','Chinyere','Sanyu','Roan','Fernando','Lilianne','Robert','Graeme','Artemisios'],

	lastname : ['Menachem','Holgersen','Reuter','MacBride','Van','Moore','Grant','Daubney','Toset','McGee'],

	greeting : ['Hi, ', 'Hi, ', 'Hi there, ', 'Hello, ', 'Hi all, ', 'Hey, ', 'Hey, ', '', '', ''],

	content : [
		'just saying hi. :)',
		'did you go out last night?',
		'where have they all gone?',
		'we have a meeting tomorrom morning.',
		'do you want to go out tonight?',
		'buy vdr now!',
		'you want this UI theme!',
		'you want vdr UI theme.',
		'you need vdr UI theme.',
		'get vdr now!',
		'vdr is the best!',
		'you wish to buy vdr!',
		'do you want vdr?',
		'vdr can run your site.',
		'buy vdr!'
	],

	capitaliseFirstLetter : function (string){
	    return string.charAt(0).toUpperCase() + string.slice(1);
	}
}