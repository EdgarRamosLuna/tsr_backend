<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require "vendor/autoload.php";
use Dompdf\Dompdf;
use Dompdf\Options;
class Pdf
{
    public function __construct()
    {
        $this->CI = &get_instance();
    }
    public function test($test, $test2,  $stream=TRUE, $filename='test'){
        var_dump("asdad");
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml('hello worlds');

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
        if ($stream) {
            $dompdf->stream("Test".".pdf", array("Attachment" => 0));
        } else {
            return $dompdf->output();
        }
    
    }
    public function generate($html, $filename='', $stream=TRUE, $paper = 'A4', $orientation = "portrait")
    {
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        if ($stream) {
            $dompdf->stream($filename.".pdf", array("Attachment" => 0));
        } else {
            return $dompdf->output();
        }
    }
}


/* End of file PDF.php and path \application\libraries\PDF.php */
