<?php
if (!$data->id) {
    $data->id = $_GET["id"];
}

$file  = new Files();
$tags = $file->get_all_tags();
$this_file = $file->get_everything_from_folder( $data->id );
//var_dump($this_file);
?>
<div class="panel panel-default panel-block  gallery-uploader">
	<div id="data-table" class="panel-heading datatable-heading dropzone-container">
        <span class="gallery-title" style="width:860px;">
            <span id="change_folder_name" style="cursor:pointer;" title="Duplo clique para editar" class="put_name_here">Galeria de Ficheiros</span>
            <?php if ( $this_file["parent"] == 1 ): //Edição da pasta de clientes desactivada ?>
                <?php 
                switch ( $this_file["avaliacao"] ) {
                    case 0: //Não está em avaliação
                        echo '<a class="btn btn-primary pull-right" style="margin-left:20px;" href="index.php?mod=files&act=para_avaliar&id='.$this_file["id"].'">Avaliar</a>';
                        break;
                    case 1: //Está em avalição, se já votou poderá mudar o voto
                        echo '<a class="btn btn-success pull-right" style="margin-left:20px;" href="index.php?mod=workflow_view&act=aceitar&id='.$this_file["id"].'">Aceitar</a>';
                        echo '<a class="btn btn-danger pull-right" style="margin-left:20px;" href="index.php?mod=workflow_view&act=rejeitar&id='.$this_file["id"].'">Rejeitar</a>';
                        break;
                    case 2: //Já foi avaliado, mostrar decisão
                        if ( $this_file["resultado"] ) {
                            echo '<a class="btn btn-success pull-right" style="margin-left:20px;" href="#">Cliente Aceite</a>';
                        }else{
                            echo '<a class="btn btn-danger pull-right" style="margin-left:20px;" href="#">Cliente Rejeitado</a>';
                        }
                        break;
                    
                    default:
                        # code...
                        break;
                }

                 ?>
                <a href="javascript:;" class="add insert btn btn-link">
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

            <a href="#" id="edit-icon-change-folder"><i class="icon icon-edit"></i></a>
            <form class="form_update_folder" action="index.php?mod=files&act=change_folder&id=<?php echo $data->id; ?>" style="display:none;" method="post">
                <div  class="input-group input-group-sm col-lg-6">
                    <input class="form-control" type="text" name="folder_new_name" value="" />
                    <span class="input-group-btn">
                        <button type="submit" style="background-color:#3699d2;" class="btn btn-warning"><i class="icon icon-arrow-right"></i></button>
                    </span>
                </div>
                <div class="col-lg-8">
                <?php if ($this_file["parent"] == 1 ): ?>
                    <div  class="input-group input-group-sm col-lg-8">
                        <label for="client_id_list" />ID do cliente:</label>
                        <input type="text" class="form-control" name="client_id" id="client_id_list" value="<?php echo $this_file["client_id"]; ?>" />
                    </div>
                <?php endif ?>
                       <h4>Emails associados a esta pasta</h4>
                    <div id="various_emails_list">
                            <?php foreach ($this_file["emails"] as $key => $value): ?>
                                <?php if ($value["is_default"]): ?>
                                    <div class="input-group input-group-sm">
                                        <span><?php echo $value["email"]; ?></span>
                                    </div>
                                <?php else: ?>
                                <?php $value["email"] = str_replace( "@".$this->settings->domain[0] , "", $value["email"]); ?>
                                <div class="input-group input-group-sm">
                                    <input class="form-control" name="emails[]" value="<?php echo $value["email"] ?>" />
                                    <span class="input-group-addon">@<?php echo $this->settings->domain[0]; ?></span>
                                    <span class="input-group-btn">
                                        <button style="background-color:#e77755;" class="btn btn-danger"><i class="icon icon-remove-sign"></i></button>
                                    </span>
                                </div>
                                <?php endif ?>
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
                <div class="col-lg-12">
                    <h4>Tags</h4>
                    <select placeholder="seleccione uma tag" name="tags[]" class="form-control select2 populate" multiple="multiple" style="width:370px;">
                    <?php foreach ($tags as $key => $tags_categ): ?>
                        <option<?php echo ( in_array($tags_categ["id"], $this_file["tags"])?' selected="selected"':'' ); ?> value="<?php echo $tags_categ["id"] ?>"><?php echo $tags_categ["tag"]; ?></option>
                    <?php endforeach ?>
                    </select>

                </div>
                <?php if ( $this_file["parent"] == 1 ): ?>
                    <div class="col-lg-4" style="margin-top:10px;">
                        <div class="form-group">
                            <div class="control-label"><label>É VIP?</label></div>
                            <label class="checkbox-inline">
                                <input type="radio" name="is_vip" value="1" <?php echo (!isset($this_file["is_vip"]) || $this_file["is_vip"] == 1 )?'checked="checked"':''; ?> />
                                Sim
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="is_vip" value="0" <?php echo (isset($this_file["is_vip"]) && $this_file["is_vip"] != 1 )?'checked="checked"':''; ?> />
                                Não
                            </label>
                        </div>
                    </div>
                <?php endif ?>

                <div class="col-lg-12 form-actions">
                    <input type="submit" value="Gravar" class="btn btn-success" name="update_folder" />
                </div>
            </form>
        </span>
    
        <?php endif ?>
	</div>

    <div class="list-group">
        <div class="list-group-item dropzone-container">
            <div class="form-group">
                <form action="<?php echo base_url("index.php?mod=ajax&act=ajax_image_upload&folder_id=" . $data->id) ?>" class="dropzone" id="imageGalleryDropzone">
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
            <div class="gallery-container files-container">
            </div>

            <table class="table table-bordered table-striped" id="tableSortable">
                <thead>
                    <tr>
                        <th style="width:30px;">#</th>
                        <th>Nome</th>
                        <th>Data</th>
                        <th style="width:130px;">Ac&ccedil;&otilde;es</th>
                        <!-- th>Peso</th -->
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</div>
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