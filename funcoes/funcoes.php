<?php
    
    error_reporting(0);

    define('APPPATH', __DIR__);

    require_once(APPPATH.'/../classes/tcpdf/tcpdf.php');

    include '../classes\PhpSpreadsheet\vendor\autoload.php';

    require_once(APPPATH.'/../classes/vendor/autoload.php');

    require_once (APPPATH.'/../classes/phpword/autoload.php'); 

    $funcoes = new funcoes;

    $funcoes->getName($_FILES);


class funcoes{
    
    public function getName($file){
        
        $nome = $file["select-file"]["name"];
        $nome_tmp = $file["select-file"]["tmp_name"];

        if($this->checkempty($nome)){
            $this->checkextension($nome, $nome_tmp);
        }

    }

    public function checkempty($arquivo){
        if($arquivo != ''){
            return true;
        }
    }

    public function movefile($arquivo, $local )
    {
        move_uploaded_file($arquivo,$local);
    }

    public function checkextension($arquivo , $arquivo_tmp){

        $word_allowed_extension = array('docx');

        $image_allowed_extension = array('jpeg','jpg','png');

        $excel_allowed_extension = array('xls');

        $file_array = explode(".", $arquivo);

        $name_noextension = $file_array[0];

        $file_extension = end($file_array);

        if(in_array($file_extension, $word_allowed_extension)){
            
            //variavel de localização+nome+extensao
            $local = '../file_converted/'.$arquivo;
            //movendo o arquivo para pasta da img
            $this->movefile($arquivo_tmp , $local);
            //enviando as informacoes do arquivo para a funcao transformar em pdf
            $response =$this->word_to_pdf($name_noextension, $arquivo);
            // para nao percorrer mais os ifs caso ele caia aqui
            echo $response;
            exit();

        }if(in_array($file_extension,$image_allowed_extension)){
            
            //variavel de localização+nome+extensao
            $local = '../file_converted/'.$arquivo;
            //movendo o arquivo para pasta da img
            $this->movefile($arquivo_tmp , $local);
            //enviando as informacoes do arquivo para a funcao transformar em pdf
            $response = $this->img_to_pdf($name_noextension, $arquivo);
            // para nao percorrer mais os ifs caso ele caia aqui
            echo $response;
            exit();

        }if(in_array($file_extension,$excel_allowed_extension)){

            //variavel de localização+nome+extensao
            $local = '../file_converted/'.$arquivo;
            //movendo o arquivo para pasta da img
            $this->movefile($arquivo_tmp , $local);
            //enviando as informacoes do arquivo para a funcao transformar em pdf
            $response =$this->excel_to_pdf($name_noextension, $arquivo);
            // para nao percorrer mais os ifs caso ele caia aqui
            echo $response;
            exit();
        }
        else{
        
            $response = json_encode(['status'=> 1, 'text'=>'Envie apenas arquivos com as extensoes : DOCX, JPEG, JPG, PNG, XLS']);

            echo $response;
            exit();
        }

    }

    function img_to_pdf($nome , $nomewithextension ){

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);	

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetMargins(0, 10, 0, true);

        $path_img = '../file_converted/'.$nomewithextension;

        //PEGA A IMAGEM
        $image = $path_img;

        $image_size = getimagesize($image);
        $image_w = $image_size[0];
        $image_h = $image_size[1];
        $image_page = ($image_w > $image_h ? 'L' : 'P');
        $image_mime = $image_size['mime'];
        $image_mime = explode('/',$image_mime);
        $image_mime = strtoupper($image_mime[1]);


        //VERIFICA SE A PÁGINA É LANDSCAPE OU PORTRAIT
        $pdf->AddPage($image_page);

        $pdf->Image($image, '', '', '', '', $image_mime,'','T',false,300,'C');

        $pdf->lastPage();

        $full_path = APPPATH.'/../file_converted/'.$nome.'.pdf';

        $short_path = 'file_converted/'.$nome.'.pdf';

        $pdf->Output($full_path, 'F');

        $response = ['status'=> 1, 'text'=>'IMAGEM TRANSFORMADA!','arquivo' => $short_path ];

        return json_encode($response);


    }


    function excel_to_pdf($nome , $nomewithextension)
    {
        
        $filepath = APPPATH."/../file_converted/".$nome.".xls";
        $nomeHTML = APPPATH."/../file_converted/".$nome.".html" ;
        $nomePDF = APPPATH."/../file_converted/".$nome.".pdf" ;

        $mpdf = new \Mpdf\Mpdf();

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");

        $spreadsheet = $reader->load($filepath);

        //escrever
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($spreadsheet);

        $writer->writeAllSheets();

        $writer->save($nomeHTML);

        //guardando o html na variavel
        $dataHTML = $writer->generateSheetData();

        //instanciando a biblioteca para convertar a pdf
        $class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
        \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
        //escrever apenas em arquivos pdf
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);

        $writer->save($nomePDF);

        $short_path = 'file_converted/'.$nome.'.pdf';

        $response = ['status'=> 1, 'text'=>'EXCEL TRANSFORMADO!','arquivo' =>$short_path ];

        return json_encode($response);

    }

    function word_to_pdf($nome , $nomewithextension)
    {
        $rendererLibraryPath = APPPATH.'/../classes/tcpdf';

        $documentName = $nome;

        $FilePath = APPPATH."/../file_converted/".$documentName.".docx"; //SÓ PERMITE DOCX WORD > 2007
        $FilePathPdf = APPPATH."/../file_converted/".$documentName.".pdf";
        $FilePathHtml = APPPATH."/../file_converted/".$documentName.".html";
        $FilePathRTF = APPPATH."/../file_converted/".$documentName.".rtf";

        \PhpOffice\PhpWord\Settings::setPdfRendererPath($rendererLibraryPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('TCPDF');

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($FilePath);

        $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
        $pdfWriter->save($FilePathPdf);

        $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        $htmlWriter->save($FilePathHtml);

        $rtfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'RTF');
        $rtfWriter->save($FilePathRTF);

        $short_path = 'file_converted/'.$nome.'.pdf';

        $response = ['status'=> 1, 'text'=>'WORD TRANSFORMADO!','arquivo' =>$short_path ];

        return json_encode($response);


    }


}



    


?>