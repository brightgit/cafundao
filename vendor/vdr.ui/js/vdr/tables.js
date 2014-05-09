$(document).ready(function() {
    !verboseBuild || console.log('-- starting vdr.tables build');
    
    vdr.tables.build();
});


vdr.tables = {
	pesquisa_avancada_table: false,
	build: function () {
		//alert("building");
		// Data Tables
		var my_table = $('#tableSortable').dataTable( {
	    	"bPaginate": true,
	        "bLengthChange": true,
	        "bFilter": true,
	        "bSort": true,
	        "bInfo": true,
	        "bProcessing": true,
	        "bServerSide": true,
	        "sAjaxSource": 'index.php?mod=ajax&act=get_files',
	        "oLanguage": {
		        "sZeroRecords": "Sem Resultados",
		        "sSearch": "<i class=\"icon-search\"></i> Pesquisa:",
		        "sProcessing": "A processar...",
		        "sLoadingRecords": "A processar...",
		        "sLengthMenu": '<i class="icon-eye-open"></i> Mostrar <select style="width:100px;">'+
		            '<option value="10">10</option>'+
		            '<option value="20">20</option>'+
		            '<option value="50">50</option>'+
		            '<option value="100">100</option>'+
		            '</select>',
		        "sInfoFiltered": " - a filtrar de _MAX_ registos",
		        "sInfoEmpty": "Não existem registos",
		        "sInfo": "A mostrar _TOTAL_ registos (_START_ a _END_)",
		        "sEmptyTable": "Não existem registos."
	        },
	        'fnServerParams': function (aoData, ele){
				var folder_id = $(".jstree-clicked").parent("li").data("id");
				if ( !folder_id ) {
					return false;
				};
				var send = new Array();
				send[0] = {};
				send[0].name = 'folder_id';
				send[0].value = folder_id;

	        	var count  = 0;
	        	if(send.length){
			    	while(count<send.length){
			    		aoData.push( send[count] );
			    		count++;
			    	}
	        	}
	        },

		    "fnPreDrawCallback": function( oSettings ) {
		    	oSettings._iDisplayLength = Math.abs(oSettings._iDisplayLength);
		    },
	        "aaSorting": [ [2, "desc"] ],
	        "aoColumnDefs": [
	            { "bSearchable": false, "aTargets": [ 'nosearch' ] },
	            { "bSortable": false, "aTargets": [ 'nosort' ] }
	        ]
		});
		// Pesquisa Avaçada
		vdr.tables.pesquisa_avancada_table = $('#pesquisa_avancada').dataTable( {
	    	"bPaginate": true,
	        "bLengthChange": true,
	        "bFilter": false,
	        "bSort": true,
	        "bInfo": true,
	        "bProcessing": true,
	        "bServerSide": true,
	        "sAjaxSource": 'index.php?mod=pesquisa_avancada&act=search',
	        "oLanguage": {
		        "sZeroRecords": "Sem Resultados",
		        "sSearch": "<i class=\"icon-search\"></i> Pesquisa:",
		        "sProcessing": "A processar...",
		        "sLoadingRecords": "A processar...",
		        "sLengthMenu": '<i class="icon-eye-open"></i> Mostrar <select style="width:100px;">'+
		            '<option value="10">10</option>'+
		            '<option value="20">20</option>'+
		            '<option value="50">50</option>'+
		            '<option value="100">100</option>'+
		            '</select>',
		        "sInfoFiltered": " - a filtrar de _MAX_ registos",
		        "sInfoEmpty": "Não existem registos",
		        "sInfo": "A mostrar (_START_ a _END_) de _TOTAL_ registos.",
		        "sEmptyTable": "Não existem registos."
	        },
	        'fnServerParams': function (aoData, ele){

	        	//Nome do ficheiro

	        	var i = 0;
				var send = new Array();

	        	var nome_ficheiro = $("#nome_ficheiro").val();
	        	if ( nome_ficheiro ) {
					send[i] = {};
					send[i].name = 'nome_ficheiro';
					send[i].value = nome_ficheiro;
					i++;
	        	};


	        	var date_start = $("#date_start").val();
	        	if ( date_start ) {
					send[i] = {};
					send[i].name = 'date_start';
					send[i].value = date_start;
					i++;
	        	};
	        	var date_end = $("#date_end").val();
	        	if ( date_end ) {
					send[i] = {};
					send[i].name = 'date_end';
					send[i].value = date_end;
					i++;
	        	};



	        	var count  = 0;
	        	if(send.length){
			    	while(count<send.length){
			    		aoData.push( send[count] );
			    		count++;
			    	}
	        	}
	        },

		    "fnPreDrawCallback": function( oSettings ) {
		    	oSettings._iDisplayLength = Math.abs(oSettings._iDisplayLength);
		    },
	        "aaSorting": [ [2, "desc"] ],
	        "aoColumnDefs": [
	            { "bSearchable": false, "aTargets": [ 'nosearch' ] },
	            { "bSortable": false, "aTargets": [ 'nosort' ] }
	        ]
		});





	//Table tags
	var my_table2 = $('#tags_sortable').dataTable( {
    	"bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": 'index.php?mod=ajax&act=get_tags',
        "oLanguage": {
	        "sZeroRecords": "Sem Resultados",
	        "sSearch": "<i class=\"icon-search\"></i> Pesquisa:",
	        "sProcessing": "A processar...",
	        "sLoadingRecords": "A processar...",
	        "sLengthMenu": '<i class="icon-eye-open"></i> Mostrar <select style="width:100px;">'+
	            '<option value="10">10</option>'+
	            '<option value="20">20</option>'+
	            '<option value="50">50</option>'+
	            '<option value="100">100</option>'+
	            '</select>',
	        "sInfoFiltered": " - a filtrar de _MAX_ registos",
	        "sInfoEmpty": "Não existem registos",
	        "sInfo": "A mostrar _TOTAL_ registos (_START_ a _END_)",
	        "sEmptyTable": "Não existem registos."
        },
        "aaSorting": [ [1, "asc"] ],
        "aoColumnDefs": [
            { "bSearchable": false, "aTargets": [ 'nosearch' ] },
            { "bSortable": false, "aTargets": [ 'nosort' ] }
        ]
	});



	//Table tags
	var my_table3 = $('#access_control').dataTable( {
    	"bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": 'index.php?mod=access_control_list&act=get_logins',
        "oLanguage": {
	        "sZeroRecords": "Sem Resultados",
	        "sSearch": "<i class=\"icon-search\"></i> Pesquisa:",
	        "sProcessing": "A processar...",
	        "sLoadingRecords": "A processar...",
	        "sLengthMenu": '<i class="icon-eye-open"></i> Mostrar <select style="width:100px;">'+
	            '<option value="10">10</option>'+
	            '<option value="20">20</option>'+
	            '<option value="50">50</option>'+
	            '<option value="100">100</option>'+
	            '</select>',
	        "sInfoFiltered": " - a filtrar de _MAX_ registos",
	        "sInfoEmpty": "Não existem registos",
	        "sInfo": "A mostrar _TOTAL_ registos (_START_ a _END_)",
	        "sEmptyTable": "Não existem registos."
        },
        "aaSorting": [ [1, "asc"] ],
        "aoColumnDefs": [
            { "bSearchable": false, "aTargets": [ 'nosearch' ] },
            { "bSortable": false, "aTargets": [ 'nosort' ] }
        ]
	});




	//Correcções de alguns css
	$('.dataTables_wrapper').find('input, select').addClass('form-control');
	$('.dataTables_wrapper').find('input').attr('placeholder', 'Quick Search');

	$('.dataTables_wrapper select').select2();

	}
}