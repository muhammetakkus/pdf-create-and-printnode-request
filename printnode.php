<?php
    
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    require_once __DIR__ . '/vendor/autoload.php';  

    $data = json_decode(file_get_contents('php://input'), true);
	
    $html = $data['html'];
    $targetPrinter = $data['target_printer'];    
    $api_key = $data['printnode_key'];
    $o = $data['o'] ?? 'P';
    $pdf = toPDF($html, $o);

    /*PrintNode*/
    $credentials = new \PrintNode\Credentials\ApiKey($api_key);
    $client = new \PrintNode\Client($credentials);
    $printJob = new \PrintNode\Entity\PrintJob($client);

    $printJob->content = base64_encode($pdf);
    $printJob->source = rand().' - '.date('Y-m-d H:i:s');
    $printJob->title = rand().' - '.date('Y-m-d H:i:s');
    $printJob->options = ['paper' => 'USER', 'copies' => 1];

    $printJob->printer = $targetPrinter;

    /**
    * Your test print job will contain a base 64 encoded PDF. The client will take
    * care of encoding the PDF, you just need to give it the path to the file.
    */

    $printJob->contentType = 'pdf_base64';
    //$printJob->addPdfFile(__DIR__ . '/printa.pdf');
    $printJobId = $client->createPrintJob($printJob);
    
    return $printJobId;
    /**/


 /*** ESKİ PrintNode Client
    $pdf = toPDF($html,$o);
    $credentials = new \PrintNode\Credentials();
    $credentials->setApiKey(********'printnode_key'*******);
    $req = new \PrintNode\Request($credentials);
    $printJob = new \PrintNode\PrintJob();
    
    // printerları çekip gelen printer var mı diye bakıyor
    $printers = $req->getPrinters((string)$printer);
     
    if (is_array($printers)) {
        foreach ($printers as $p) {
            if($p->id == $printer) {
                $targetPrinter = $p;
            }
        }
    }

    $printJob->printer = $targetPrinter;
    $printJob->contentType = 'pdf_base64';
    $printJob->content = base64_encode($pdf);
    $printJob->source = rand().' - '.date('Y-m-d H:i:s');
    $printJob->title = rand().' - '.date('Y-m-d H:i:s');
    $printJob->options = ['paper' => 'USER', 'copies' => 1];
    $response = $req->post($printJob);
    // The response returned from the post method is an instance of PrintNode\Response. 
    // It contains methods for retrieving the response headers, body and HTTP status-code and message.
    
    // Returns the HTTP status code.
    $statusCode = $response->getStatusCode();
        
    // Returns the HTTP status message.
    $statusMessage = $response->getStatusMessage();
        
    // Returns an array of HTTP headers.
    $headers = $response->getHeaders();
        
    // Return the response body.
    $content = $response->getContent();
   Eski PrintNode Client ***/

    
 function toPDF($html, $o = 'P') {
	$data = [
		"html" => base64_encode($html),
                "o" => $o
	];
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://pro.tedisyon.com/pdf/index.php',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => json_encode($data),
	  CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json'
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
 }

?>
