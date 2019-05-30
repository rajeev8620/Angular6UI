<?php 
/**
 * Page to handle user registration
 * @Author : Rajeev Kumar
 * @Date : 22/05/2019
 * @LastModified : 22/05/2019
 * @LastModificationDetails : Added Newly
 * @LastModifiedBy : Rajeev
 */
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$request = json_decode(file_get_contents("php://input"));

include_once '/opt/lampp/htdocs/MelanomaPredictor_API/config/config.php';
include_once '/opt/lampp/htdocs/MelanomaPredictor_API/lib/MelonomaPrediction.class.php';

$objMelonomaPrediction=new MelonomaPrediction();

$currentDate=date('Y-m-d h:i:s');

$fileType=$request->fileType;
$reportFileName='';
$summaryPath='';
if($fileType=='vcf'){
	$filePath=$vcfSampleFilePath;
	$filename='sample.vcf';
}else if($fileType=='xls'){
	$filename="sample.xls";
	$filePath=$xlsSampleFilePath;
}
if(file_exists($filePath)) {
	if($fileType=="vcf"){		
		$path = $filePath;
		header('Content-Type: text/x-vcard;charset=utf-8;');
		header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
		header('Accept-Ranges: bytes');  // For download resume
		header('Content-Length: ' . filesize($path));  // File size
		header('Content-Encoding: none');
		header('Content-Disposition: attachment; filename=' . $filename);  // Make the browser display the Save As dialog
		readfile($path);  //this is necessary in order to get it to actually download the file, otherwise it will be 0Kb
	}
	if($fileType=="xls"){
		$path = $filePath;
		header('Content-Type: application/vnd.ms-excel');  // For Gecko browsers mainly
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
		header('Accept-Ranges: bytes');  // For download resume
		header('Content-Length: ' . filesize($path));  // File size
		header('Content-Encoding: none');
		header('Content-Disposition: attachment; filename=' . $filename);  // Make the browser display the Save As dialog
		readfile($path);  //this is necessary in order to get it to actually download the file, otherwise it will be 0Kb
	}
}