<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-4">
                    <div class="card-header">
                        LM Conversor PDF
                    </div>
                    <div class="card-body">
                        <form id="load_file" method="post"  enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="col">
                                    <label for="exampleInputEmail1">Enviar Arquivo (xls,word,csv,jpeg,jpg,png)</label>
                                    <input type="file" name="select-file" class="form-control-file">
                                    <small id="emailHelp" class="form-text text-muted">Arquivos transformados são armazenados dentro do sistema.</small>                            
                                </div>
                            </div>
                        </form>                    
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-2">                        
                                <input type="button" id="sendFile" class="btn btn-primary" value="Enviar"> <!-- CUIDADO AO CRIAR UM BOTÃO TYPE SUBMIT E REALIZAR UMA MUDANÇA COM FUNCOES COMO .HTML PORQUE NA HORA QUE VOCE CLICAR PARA ENVIAR ELE VAI AGIR COMO UM FORMULARIO E RECARREGAR A PAGINA ENTÃO A MUDANÇA DO ESTILO .CSS E RETORNO DE TEXTO NAO APARECERÁ -->
                            </div>
                            <div class="col">
                                <div id="erros" role="alert"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
            <div class="row">
                <iframe src="" id="pdfin" width="600" height="780" style="display:flex;border: none;"></iframe>
            </div>
    </div>
   

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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


    