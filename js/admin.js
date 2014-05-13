$(document).ready(function(){

	//permitir edição de campos na visualização de processo
	$("button.btn-edit-process").click(function(){
		
		var fields = $("div.seamless-inputs").find("input, textarea");

		$(".btn-confirm-changes").removeAttr("disabled");

		for (var i = fields.length - 1; i >= 0; i--) {
			current_input = $(fields[i]);
			if(current_input.data("editable") == 0)
				continue;
			else
				current_input.removeAttr('readonly');
		};
	})

	$("input[name=process_montante]").on("keyup", function(){
		var valor = $("input[name=process_montante]").val();
		$("input[name=process_montante_extenso]").val(valor.extenso(true));

		if(valor > 50000)
			$("div.montante-analise-risco").fadeIn();
		else
			$("div.montante-analise-risco").fadeOut();
	})

	//load dos widgets
	$(".vdr-widget").css({
		opacity: '1'
	});

	$(".list-selectable").click( function () {
		$(".list-selectable").removeClass("selected");
		$(this).addClass("selected");
	} );

	//remove o history (consequentemente removendo o "Redisplay" do topo direito)
	$.pnotify.defaults.history = false;

	//notificacoes
	$("#notify-messages li").each(function(){

		switch($(this).data("type")){
			case "success": title = "Sucesso"; break;
			case "info": title = "Informação"; break;
			case "error": title = "Erro"; break;
		}

		$.pnotify({
            title: title,
            text: $(this).html(),
            type: $(this).data("type")
        });
	})
	
	$('.nav-tabs a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	})

	$("#multiple-actions-select-all").change( function(){
		if ( this.checked ) {
			$(".multiple-actions").prop( "checked", true );
		}else{
			$(".multiple-actions").prop( "checked", false );
		}
	} );

	//confirmação no delete de links
	$(".link-confirm").click( function(){

		$( '#'+$(this).data("toggle") ).modal();
		event.preventDefault();
		var form = $(this);
		$("#confirm").click( function(){
			window.location = form.attr("href");
		});
	} );


	$(".form-confirmation").submit( function(){
		$( '#'+$(this).data("toggle") ).modal();
		event.preventDefault();
		var form = $(this);
		$("#confirm").click( function(){
			form.submit();
		});
	} );

	$(".special-form-confirmation").submit( function( event ){

		console.log(this);
		console.log($(this).attr("name"));
		alert( event.target.name );
	    var val = $("button[type=submit][clicked=true]").html();
	    alert(val)

		console.log( event );
		$( '#'+$(this).data("toggle") ).modal();
		event.preventDefault();
		var form = $(this);
		$("#confirm").click( function(){
			form.submit();
		});
	} );


	//CKEDITOR
	//$('.ck-this').ckeditor();

	//Falta o request para fazer update às coisas
	$('.nested-sortable-1').nestedSortable({
		maxLevels: 1,
	    //items: 'li',
      	items: "li:not(.no-sort)",
	    placeholder: "ui-state-highlight",
	    stop: function (event, ui){
	    	//console.log(this);
	    	//console.log($(this));
	    	var string = $(this).nestedSortable('serialize');
	    	var url = $(this).data('source');
	    	$.ajax({
	    		url: url + '&order=1&'+string,
	    	}).done(function(msg){
	    		if( msg != '' ) {
	    			//alert(msg);
	    			alert('Ocorreu um erro, por favor faça refresh à página.');
	    		}else{
	    			
	    		}
	    	});
	    	//console.log(string);
	    }
	});
	//Falta o request para fazer update às coisas
	$('.nested-sortable-2').nestedSortable({
		maxLevels: 2,
	    //items: 'li',
      	items: "li:not(.no-sort)",
	    placeholder: "ui-state-highlight",
	    stop: function (event, ui){
	    	//console.log(this);
	    	//console.log($(this));
	    	var string = $(this).nestedSortable('serialize');
	    	var url = $(this).data('source');
	    	$.ajax({
	    		url: url + '&order=1&'+string,
	    	}).done(function(msg){
	    		if( msg != '' ) {
	    			//alert(msg);
	    			alert('Ocorreu um erro, por favor faça refresh à página.');
	    		}else{
	    			
	    		}
	    	});
	    	//console.log(string);
	    }
	});

	$(".table_tab").click( function () {
		var id = $(this).data("id");
		$(".tabs_group_"+id).toggle("fast");
	} );


	$(".datepicker-this").datepicker( {
		dateFormat: "yy-mm-dd"
	} );


});


function load_edit_tag(id) {

	$("#tag-info-block").load("index.php?mod=tags_list&act=tag_info&id="+id, function() {
		//Corre isto depois de ir buscar o html
		var tag_name = $("#tag_info").data("tag_name");
		var tag_id = $("#tag_info").data("tag_id");
		var tag_active = $("#tag_info").data("tag_active");
		$("#input_tag").val( tag_name );
		$("#input_tag_id").val( tag_id );
		$(".change_to_edit").html("Editar Tag");


	    var $radios = $('input:radio[name=active]');
	    if($radios.is(':checked') === false) {
	        $radios.filter('[value='+tag_active+']').prop('checked', true);
	    }


	} );
	return false;
}
function load_login_info(id) {

	$("#ac-info-block").load("index.php?mod=access_control_list&act=login_info&id="+id, function() {

	} );
	return false;
}




function password_recover(){
	if ( !$("#email_recover").length ) { return false; };

	var email = $("#email_recover").val();
	if ( email == "" ) { $("#email_recover").css("border", "1px solid red");  return false; };

	$("#recover-target").load( "index.php?mod=login&act=recover_email&email_recover="+email );

}