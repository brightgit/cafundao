$(document).ready(function() {
    !verboseBuild || console.log('-- starting vdr.sidebar build');

    vdr.sidebar.build();
   
});

vdr.sidebar = {
	build: function () {
		// Initiate sidebar events
		vdr.sidebar.events();

		// Build Advanced Search sidebar feature
		!$('.advanced-search').length || vdr.sidebar.buildAdvancedSearch();

		// Initiate sidebar retraction on smaller screen sizes
		vdr.sidebar.retractOnResize();

		// Sets max-heigh for sidbar menu in mobile mode (needed for CSS transitions)
		vdr.sidebar.setSidebarMobHeight();

		// Builds page data for sidebar menu
		if ( $("#vdr-tree").length ) {
			vdr.sidebar.buildPageData();
		};

		// Check if jstree plugin exists, initiate if true
		!$.jstree || vdr.sidebar.jstreeSetup();

		!verboseBuild || console.log('            vdr.sidebar build DONE');

	},
	buildAdvancedSearch: function () {
		!verboseBuild || console.log('            vdr.sidebar.buildAdvancedSearch()');
        $('.select2').select2({ minimumResultsForSearch: 6 });
        $('.datetimepicker-search').datetimepicker({
        	format : 'yyyy-mm-dd',
        	minView : 'month',
        	maxView : 'month',
        	autoclose : true
        });
        $('input[type="radio"], input[type="checkbox"]').uniform();
	},
	events : function () {
		!verboseBuild || console.log('            vdr.sidebar binding events');

		$(document).on('click', '.sidebar-handle', function(event) {
			event.preventDefault();
			vdr.sidebar.toogleSidebar();
		});

		$(document).on('click', '.btn-advanced-search, .close-advanced-search', function(event) {
			event.preventDefault();
			vdr.sidebar.toogleAdvancedSearch();
		});
	},
	toogleAdvancedSearch: function () {
		!verboseBuild || console.log('            vdr.sidebar.toogleAdvancedSearch()');
		$('.sidebar').toggleClass('search-mode');
	},
	toogleSidebar: function () {
		!verboseBuild || console.log('            vdr.sidebar.toogleSidebar()');


		$('.sidebar').toggleClass('extended').toggleClass('retracted');
		$('.wrapper').toggleClass('extended').toggleClass('retracted');
		
		if ($('.sidebar').is('.search-mode')){
			vdr.sidebar.toogleAdvancedSearch();
		}

		if ($('.sidebar').is('.retracted')){
		    $.cookie('vdrSidebar', 'retracted', {
		        expires: 7,
		        path: '/'
		    });
		}
		else {
		    $.cookie('vdrSidebar', 'extended', {
		        expires: 7,
		        path: '/'
		    });
		}
		setTimeout(function() {
			!(vdr.graphsStats && vdr.graphsStats.redrawCharts) || vdr.graphsStats.redrawCharts();
		}, 1000);
	},
	retractOnResize: function () {
		!verboseBuild || console.log('            vdr.sidebar.retractOnResize()');
		
		if(!ltIE9 && !Modernizr.mq('(min-width:' + (screenMd) + 'px)')){
			if ($('.sidebar').is('.extended')){
				vdr.sidebar.toogleSidebar();
				
			}
		}
	},
	jstreeSetup : function () {
		!verboseBuild || console.log('            vdr.sidebar.jstreeSetup()');
		
		$.jstree._themes = "./vendor/vdr.ui/css/vendor/jstree-theme/vdr/";
		//$.jstree.defaults.plugins = ["themes", "json_data", "crrm", "contextmenu", "dnd", "ui", "cookies"];
		$.jstree.defaults.plugins.push("state")

		//starts the tree
		$(".jstree-vdr").jstree({
			"cookies": { "save_selected" : true }
		}).on("click", "a", function () {


			var c = $(this).children("span").children("input");
			//console.log(c);
			var i = $(this).children("i");
			

			if (c.prop("checked")) {

				c.prop("checked", false);
				i.removeClass("icon-check");
				i.addClass("icon-unchecked");
				//c.removeAttr("checked");
			}else{
				c.prop("checked", true);
				i.removeClass("icon-unchecked");
				i.addClass("icon-check");
				//c.attr("checked", "checked");
			}
			//alert( c.prop("checked") );

		}).on("loaded.jstree", function(e, data){
			//id selected via cookie
			var selected_folder_id = $.cookie('jstree_selected_folder_id');

			//a não estar definido, definir a primeira pasta como selected
			if(typeof(selected_folder_id) == "undefined"){
				$('#vdr-tree').jstree('select_node', 'ul > li:first');
			}
			else{
				var selected_folder = $("ul li[data-id=15] a");
			    //cookie - seleccionar a pasta com o id guardado em cookie
			    $('#vdr-tree').jstree('select_node', 'ul > li[data-id='+selected_folder_id+']');
			}

		}).on("close_node.jstree", function(e,data){
			//close_node clode
		}).on("select_node.jstree", function(e,data){

			//var current_folder_name = data.rslt.obj.children("a").text();
			var clientid = data.rslt.obj.data("clientid");
			var processid = data.rslt.obj.data("processid");
			var folder_id = data.rslt.obj.data("id");
			//alert(current_folder_name);



			$.cookie('jstree_selected_folder_id', folder_id, {
		        expires: 7,
		        path: '/'
		    });




			REQUEST_FOLDER = $.ajax({
				url: BASEPATH + 'index.php',
				type: 'GET',
				data: {
					mod: "clientes_list",
					act: "list_process",
					clientid: clientid,
					processid: processid
				}
			})
			.done(function(response_string) {
				//alert( data.rslt.obj.data("folder-type") );


				var json_response, json_response_table, table_row, active;
						
				//popular a tabela e a sidebar com a resposta JSON
				//json_response = $.parseJSON(response_string);

				$("#ajax-content").html(response_string);
				//vdr.sidebar.startdatatables();

				//a ser uma galeria, instanciar o imageGallery
				if(typeof(vdr.imageGallery) != "undefined")
					vdr.imageGallery.build();


				//tabela
				// if(typeof(json_response.files) != "undefined"){
				// 	for (var i = json_response.files.length - 1; i >= 0; i--) {
				// 		file_active = json_response.files[i].is_active == 1 ? '<span class="label label-success">Activo</span>' : '<span class="label label-error">Inactivo</span>';
				// 		table_row = "<tr><td><input type=\"checkbox\" name=\"items[]\" value=\""+json_response.files[i].id+"\" class=\"multiple-actions\" /></td><td>"+json_response.files[i].title+"</td><td>N/A</td><td>"+json_response.files[i].date_in+"</td><td>"+json_response.files[i].file+"</td><td>"+file_active+"</td></tr>";
				// 		$(table_row).appendTo('.json_response_table tbody');
				// 	};
				// }
			});

		});
	},
	setSidebarMobHeight : function () {
		!verboseBuild || console.log('            vdr.sidebar.setSidebarMobHeight()');

		if(ltIE9 || Modernizr.mq('(min-width:' + (screenXs) + 'px)')){
			$('.sidebar').css('max-height','none');
		}
		else{
			$('.sidebar').css('max-height','none');
			setTimeout(function() {
				var sidebarMaxH = $('.sidebar > .panel').height() + 30 + 'px';
				$('.sidebar').css('max-height',sidebarMaxH);
			}, 200);
		}
	},
	doThislater : function (){
		$('.sidebar .sidebar-handle').on('click', function(){
		    $('.panel, .main-content').toggleClass('retracted');
		});

		// APPLY THEME COLOR
		if ($.cookie('themeColor') == 'light') {
		    $('body').addClass('light-version');
		}
		if($.cookie('jsTreeMenuNotification')!='true') {
		    $.cookie('jsTreeMenuNotification', 'true', {
		        expires: 7,
		        path: '/'
		    });
		    $.pnotify({
		        title: 'Slide Menu Remembers It\'s State',
		        type: 'info',
		        text: 'Slide menu will remain closed when you browse other pages, until you open it again.'
		    });
		}
	},
	buildPageData : function () {
		!verboseBuild || console.log('            vdr.sidebar.buildPageData()');

		var pageTitle = document.title;
		$('.page-title').text(pageTitle);
		pageTitle = pageTitle.replace("vdr UI - ", "");
		$('.bread-page-title').text(pageTitle);
		
		$('.preface p').text(pageTitle + ' include: ');
		vdr.sidebar.treeJson = {
		 //    'data' : [
			//     {
			//         'data' : { 
			//             'title' : pageTitle, 
			//             'attr' : { 
			//             	'href' : '#top',
			//             	'id' : 'vdr-lvl-0'
			//             } 
			//         },
			//         'children' : [ 
			            
			//         ]
			//     }
			// ]
			
		}

		var numSections = $('.section-title').length;
		$('.section-title').each(function(index, el) {
			if ($(this).is('.preface-title')) return;
			
			var sectionTitle = $.trim($(this).text());
			var sectionId = sectionTitle.replace(/\s+/g, '-').toLowerCase()

			// creates dash-case anchor ID to be used with sidebar links
			$(this).parents('.list-group-item').attr('id', sectionId);

			// Add item to breadcrumb nav
			$('<li role="presentation"><a role="menuitem" tabindex="-1" href="#' + sectionId + '">' + sectionTitle + '</a></li>').appendTo('.breadcrumb-nav .active .dropdown-menu');


			// creates sidebar link object
			var newLinkObject = {
				'data' : {
					'title' : sectionTitle, 
					'attr' : { 'href' : '#' + sectionId }
				}
			};
			vdr.sidebar.treeJson.data[0].children.push(newLinkObject);

			// Add item to title bar
			if ((index + 1) !== numSections)
				$('.preface p').text($('.preface p').text() + sectionTitle + ', ');
			else
				$('.preface p').text($('.preface p').text().slice(0, -2) + ' and ' + sectionTitle + '.');
		});
	},
	startdatatables: function () {
		vdr.tables.build();
	}
}