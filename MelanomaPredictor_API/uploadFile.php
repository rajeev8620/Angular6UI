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

date_default_timezone_set('America/Los_Angeles');

$request = json_decode(file_get_contents("php://input"));

include_once '/opt/lampp/htdocs/MelanomaPredictor_API/config/config.php';
include_once '/opt/lampp/htdocs/MelanomaPredictor_API/lib/MelonomaPrediction.class.php';

$objMelonomaPrediction=new MelonomaPrediction();

$postedData=$_POST;
$filesData=$_FILES;
$total = count($filesData['file']['name']);
$consumerId=$postedData['consumerId'];
$currentDate=date('Y-m-d h:i:s');
if($consumerId!=''){
	$stats_arr=array();
	$stats_arr["Message"]=array();
	$stats_arr["Status"]=array();
	$stats_arr["ReportStatus"]=array();
	$tmpUploadFile=array();
	
	$insertData['ConsumerId']=$consumerId;
	$insertData['UploadedFileName']='';
	$insertData['UploadedOn']=$currentDate;
	$inserDataId=$objMelonomaPrediction->insertNewDataIntoTable($insertData,'OrderDetails');
	
	if($inserDataId!=''){		
		$ouputDir=DATAUPLOADFOLDER.$inserDataId;
		if (!is_dir($ouputDir)){
			exec("mkdir $ouputDir");
			exec("chmod 777 $ouputDir");
		}
		$ouputDir .='/input';
		if (!is_dir($ouputDir)){
			exec("mkdir $ouputDir");
			exec("chmod 777 $ouputDir");
		}
		
		for( $i=0 ; $i < $total ; $i++ ) {
			//file upload into a folder on server
			$inputfile=$filesData['file']['tmp_name'][$i];
			$fileName = $filesData['file']['name'][$i];
			$fileNameCmps = explode(".", $fileName);
			$dest_path = $ouputDir .'/'. $fileName;
			if(!in_array($fileName,$tmpUploadFile) && $fileName!=''){
				array_push($tmpUploadFile,$fileName);
				if(move_uploaded_file($inputfile, $dest_path)){
					exec("chmod 777 $dest_path");
				}
			}
		}
		
		//insert data into table
		$fileNameString=implode(', ', $tmpUploadFile);
		$updateData=array();
		$updateData['UploadedFileName']=$fileNameString;
		$updateFile=$objMelonomaPrediction->updateTableRecords($updateData,$inserDataId);
		//call binary for report generation
		//-i /APF/huzaifa/MELANOMA/10/input/ -d /data/cw_utils/tools/iCOG/bin/Melanoma_Relapse_Predictor/dependencies.zip
		$inputFilePath="/opt/lampp/htdocs/MelanomaPredictor_API/".$ouputDir;
		$dataBaseDetails=DATABASE_HOST_NAME.','.DATABASE_NAME.','.DATABASE_USER_NAME.','.base64_decode(DATABASE_PASSWORD);
		if(exec("$binaryPath -i $inputFilePath -d $binaryDependency -v $vcfDependency -db $dataBaseDetails")){
			//now get status of report generation
			$statusCheck="SELECT Status FROM OrderDetails WHERE OrderId='$inserDataId'";
			$statusDetails=$objMelonomaPrediction->getTableDetailsByQuery($statusCheck);
			$statusVal=isset($statusDetails[0]['Status'])?$statusDetails[0]['Status']:'';
			//remove uploaded file from destination
			array_push($stats_arr["Message"], 'File uploaded successfull!');
			array_push($stats_arr["Status"], 1);
			array_push($stats_arr["ReportStatus"], $statusVal);
			echo json_encode($stats_arr);
			
		}else{
			array_push($stats_arr["Message"], 'Error in report generation!');
			array_push($stats_arr["Status"], 2);
			array_push($stats_arr["ReportStatus"], '');
			echo json_encode($stats_arr);
		}
	}
	
}



?>