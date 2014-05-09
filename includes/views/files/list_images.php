<?php
if ($data->files[0]->category) {
	$id = $data->files[0]->category;
}else{
	$id = $_GET["id"];
}
$file  = new Files();
$tags = $file->get_all_tags();
$this_file = $file->get_everything_from_folder( $id );
//var_dump($this_file);

?>

<section class="panel panel-default panel-block  gallery-uploader">



	<div class="panel-heading datatable-heading dropzone-container">
        <span class="gallery-title" style="width:720px;">
            <span id="change_folder_name" style="cursor:pointer;" title="Duplo clique para editar" class="put_name_here">Galeria de Ficheiros</span>
            <a href="#" id="edit-icon-change-folder"><i class="icon icon-edit"></i></a>
            <form class="form_update_folder" action="index.php?mod=files&act=change_folder&id=<?php echo $id; ?>" style="display:none;" method="post">
                <div  class="input-group input-group-sm col-lg-8">
                    <input class="form-control" type="text" name="folder_new_name" value="" />
                    <span class="input-group-btn">
                        <button type="submit" style="background-color:#3699d2;" class="btn btn-warning"><i class="icon icon-arrow-right"></i></button>
                    </span>
                </div>
                <div class="col-lg-8">
                    <h4>Emails associados a esta pasta</h4>
                    <div id="various_emails_list">
                            <?php foreach ($this_file["emails"] as $key => $value): ?>
                                <?php $value["email"] = str_replace( "@".$this->settings->domain[0] , "", $value["email"]); ?>
                                <div class="input-group input-group-sm">
                                    <input class="form-control" name="emails[]" value="<?php echo $value["email"] ?>" />
                                    <span class="input-group-addon">@<?php echo $this->settings->domain[0]; ?></span>
                                    <span class="input-group-btn">
                                        <button style="background-color:#e77755;" class="btn btn-danger"><i class="icon icon-remove-sign"></i></button>
                                    </span>
                                </div>
                            <?php endforeach ?>


                        <div class="input-group input-group-sm">
                            <input class="form-control" name="emails[]" value="" />
                            <span class="input-group-addon">@<?php echo $this->settings->domain[0]; ?></span>
                            <span class="input-group-btn">
                                <button style="background-color:#e77755;" class="btn btn-danger"><i class="icon icon-remove-sign"></i></button>
                            </span>
                        </div>
                    </div>

                    <a onclick="add_new_email();" href="Javascript:void(0);">Adicionar novo email</a>
                </div>
                <div class="col-lg-4">
                    <h4>Tags</h4>
                    <select placeholder="seleccione uma tag" name="tags[]" class="form-control select2 populate" multiple="multiple" style="width:370px;">
                    <?php foreach ($tags as $key => $tags_categ): ?>
                        <option<?php echo ( in_array($tags_categ["id"], $this_file["tags"])?' selected="selected"':'' ); ?> value="<?php echo $tags_categ["id"] ?>"><?php echo $tags_categ["tag"]; ?></option>
                    <?php endforeach ?>
                    </select>

                </div>
                <div class="col-lg-12 form-actions">
                    <input type="submit" value="Gravar" class="btn btn-success" name="update_folder" style="position:relative; right:-160px;" />
                </div>
            </form>
        </span>
        <a href="javascript:;" class="add insert">
            <i class="icon-plus-sign"></i>
            <span>
                Adicionar Ficheiros
            </span>
        </a>
        <a href="javascript:;" class="add finished">
            <i class="icon-check-sign"></i>
            <span>
                Terminar
            </span>
        </a>
	</div>




	<div class="list-group">
		<div class="list-group-item dropzone-container">
			<div class="form-group">
				<form action="<?php echo base_url("index.php?mod=ajax&act=ajax_image_upload&folder_id=" . $id) ?>" class="dropzone" id="imageGalleryDropzone">
					<div class="dz-message clearfix">
						<i class="icon-picture"></i>
						<span>Arraste os ficheiros para aqui ou clique para seleccionar</span>
						<div class="hover">
							<i class="icon-download"></i>
							<span>ARRASTAR FICHEIROS PARA AQUI</span>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="list-group-item preview-container">
			<div class="form-group">
				<div class="gallery-container">
					<?php foreach ($data->files as $file): ?>
					<div class="dz-preview dz-file-preview">
						<div style="background: url(<?php echo base_url("get_file.php?id=" . $file->id) ?>)" class="dz-details">
							<!--img data-dz-thumbnail alt="<?php echo $file->title ?>" src="<?php echo base_url("get_file.php?id=" . $file->id) ?>"/-->
							<div class="overlay">
								<div class="dz-filename"><span data-dz-name><?php echo $file->title ?></span></div>
								<!-- <div class="dz-size" data-dz-size></div> -->
								<div class="status">
									<a class="dz-error-mark remove-item" href="javascript:;"><i class="icon-remove-sign"></i></a>
									<div class="dz-error-message">Error: <span data-dz-errormessage></span></div>
								</div>
								<div class="controls clearfix">
                                    <a class="fancythis" rel="fancybox" href="<?php echo base_url("get_file.php?id=" . $file->id.'&full=true') ?>"><i class="icon-search"></i></a>
									<a href="index.php?mod=files_view&amp;id=<?php echo $file->id; ?>"><i class="icon-pencil"></i></a>
									<a class="trash-item" href="#"><i class="icon-trash"></i></a>
								</div>
								<div class="controls confirm-removal clearfix">
									<a class="remove-item" href="index.php?mod=files_view&amp;act=delete_file&amp;id=<?php echo $file->id; ?>">Sim</a>
									<a class="remove-cancel" href="javascript:;">N&atilde;o</a>
								</div>
							</div>
						</div>
						<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
					</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	</div>
</section>
    

<script src="<?php echo base_url("vendor/vdr.ui/js/vdr/image-gallery-uploader.js") ?>"></script>
<script src="<?php echo base_url("vendor/vdr.ui/js/vendor/dropzone.min.js") ?>"></script>
<script>

    $("#edit-icon-change-folder").click( function (){
        $("#change_folder_name").trigger("dblclick");
    } )

    $("#change_folder_name").dblclick( function() {
        $(this).hide(0);
        $("#edit-icon-change-folder").hide(0);
        $(".form_update_folder").show(0);
        $("input[name='folder_new_name']").val( this.innerText.trim() );
    } );
    function add_new_email(){
        var input = $('input[name="emails[]"]');
        if ( $( input[input.length-1] ).val() == '' ) {
            return false;
        };
        console.log( input );
        var html='<div class="input-group input-group-sm">'+
                            '<input class="form-control" name="emails[]" value="" />'+
                            '<span class="input-group-addon">@<?php echo $this->settings->domain[0]; ?></span>'+
                            '<span class="input-group-btn">'+
                                '<button type="button" style="background-color:#e77755;" class="btn btn-danger"><i class="icon icon-remove-sign"></i></button>'+
                            '</span>'+
                        '</div>';
        $("#various_emails_list").append( html );
        $(".input-group-btn .icon-remove-sign").click( function () {
            $(this).parent("button").parent("span").parent("div").remove();
        } );

    }

    vdr.formComponents.select2();

    $(".input-group-btn .icon-remove-sign").click( function () {
        $(this).parent("button").parent("span").parent("div").remove();
    } );


    $(".form_update_folder").submit( function () {
        var input = $('input[name="emails[]"]');    //Validar emails
        for (var i = input.length - 1; i >= 0; i--) {

            input[i] = $(input[i]).val();


            if (input[i] == '' ) { continue; };

            input[i] = input[i] + '@<?php echo $this->settings->domain[0]; ?>';

            if ( !validateEmail( input[i] ) ) {
                alert( "Foram encontrados email inválidos." );
                return false;
            };
        };
        return true;

    } )

    function validateEmail(email){ 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}


</script>