<?php 
//echo phpinfo();
//return false;
ini_set('display_errors',1);
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        //'default_font' => 'minecraft',
        'default_font_size' => 12,
        'format' => [80, 297],
        'margin_left' => 0,
        'margin_right' => 0,
        'margin_top' => 0,
        'margin_bottom' => 0,
        'margin_header' => 0,
        'margin_footer' => 5,
        'falseBoldWeight' => 10, 
        'dpi' => 96,
        'orientation' => 'P',
        'tempDir' => '/var/www/pdf/tmp'
]);

$data = json_decode(file_get_contents('php://input'), true);
$data['html'] = base64_decode($data['html']);

$mpdf->WriteHTML($data['html']);
// return base64_encode($mpdf->Output('', 'S'));
//$mpdf->Output();
$mpdf->Output('printb.pdf', \Mpdf\Output\Destination::FILE);

