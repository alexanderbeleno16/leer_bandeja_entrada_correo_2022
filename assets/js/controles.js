/**
 * CONSULTA MAIL
 */
 $(document).ready(function(){

    $(document).on('click','#btn_consulta_mail',function(){
        let contenedor = $(this).attr("contenedor_space");
        let accion = $(this).attr("accion");

        contenedor = $("#"+contenedor+"");
        contenedor.html("");

        $.ajax({
            url: "assets/ctr_ajax/ctr_ajax.php",
            type: "POST",
            dataType: "json",
            data: {
                "accion":accion
            },
            cache:false,
            beforeSend: function(){
                contenedor.html('<div class="alert alert-primary">Cargando... <div class="spinner-border spinner-border-sm" role="status">  <span class="visually-hidden">Loading...</span></div></div>');
            },
            success:function(data){
                contenedor.html(data.html);
                console.log(data);
            },
            error:function(data){
                alert("Error interno");
            }
        });
    });

});

