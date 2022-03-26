<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <form id="load_file" method="post"  enctype="multipart/form-data">
                <div class="col-12 form-group">
                    <input type="file" name="select-file" class="form-control-file">
                    <input type="button" id="sendFile" class="btn btn-primary" value="Enviar"> <!-- CUIDADO AO CRIAR UM BOTÃO TYPE SUBMIT E REALIZAR UMA MUDANÇA COM FUNCOES COMO .HTML PORQUE NA HORA QUE VOCE CLICAR PARA ENVIAR ELE VAI AGIR COMO UM FORMULARIO E RECARREGAR A PAGINA ENTÃO A MUDANÇA DO ESTILO .CSS E RETORNO DE TEXTO NAO APARECERÁ -->
                </div>
            </form>
            <div  id="erros" role="alert"></div>
            </div>
            <div class="row">
                <iframe src="" id="pdfin" width="600" height="780" style="display:flex;border: none;"></iframe>
            </div>
    </div>
   


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script>

        $(document).ready(function(){
                readfile(); //função que vai realizar a leitura do arquivo
            })


        function readfile(){
            $('#sendFile').on('click', function(event){ //vai monitor o momento que o botão for clicado para continuar para um função de callback
                //event.preventDefault(); 
                if($('input[type=file]').val() != ''){ //verificar se o arquivo está vazio
                    senddata(); // chamar funcao pra enviar o arquivo
                }else{
                    $('#erros').html('Impossivel enviar um arquivo vazio').addClass('alert alert-danger');
                }
            })
        }   

        function senddata(){
            $.ajax({
                url:"funcoes/funcoes.php",
                method:"POST",
                data:new FormData(load_file),
                contentType:false,
                /* beforeSend: (jqXHR, settings) => {
                    $('#load_file').empty();
                }, */
                cache:false,
                processData:false,
                dataType:"json",
                success:function(retorno){
                    if(retorno.status == 1){
                        $('#erros').html(retorno.text).addClass('alert alert-success');
                        if(retorno.arquivo){
                            document.querySelector("#pdfin").setAttribute('src',retorno.arquivo);
                            $('#pdfin').css()
                        }
                    }else{
                        $('#erros').html('Deu algum erro').addClass('alert alert-success');
                    }
                }
            });
        }
    </script>
</body>

</html>


    