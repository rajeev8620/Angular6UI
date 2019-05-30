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
$function=$request->function;
if($function=="userRegistration"){
	$stats_arr=array();
	$stats_arr["Message"]=array();
	$stats_arr["Status"]=array();
	$firstName=$request->FirstName;
	$lastName=$request->LastName;
	$email=$request->Email;
	$password=$request->Password;
	$isEmailExist=0;
	//check is emailId already used or not
	$emailCheck="SELECT Email FROM ConsumerDetails WHERE Email='$email' AND Status IN(1,2)";
	$emailDetails=$objMelonomaPrediction->getTableDetailsByQuery($emailCheck);
	if(count($emailDetails)>0){
		array_push($stats_arr["Message"], 'Email already registred with us!');
		array_push($stats_arr["Status"], 2);
		echo json_encode($stats_arr);
	}else{
		//insert new data into table
		$regData=array();
		$regData['FirstName']=isset($firstName)?$firstName:'';
		$regData['LastName']=isset($lastName)?$lastName:'';
		$regData['Email']=isset($email)?$email:'';
		$regData['Password']=isset($password)?md5($password):'';
		$regData['UserType']=1;
		$regData['Status']=1;
		$inserData=$objMelonomaPrediction->insertNewDataIntoTable($regData,'ConsumerDetails');
		array_push($stats_arr["Message"], 'Registration successfull!');
		array_push($stats_arr["Status"], 1);
		echo json_encode($stats_arr);
	}
	
}
if($function=="userLogin"){
	$stats_arr=array();
	$stats_arr["Message"]=array();
	$stats_arr["Status"]=array();
	$email=$request->Email;
	$password=$request->Password;
	$emailCheck="SELECT Email,Status FROM ConsumerDetails WHERE Email='$email'";
	$emailDetails=$objMelonomaPrediction->getTableDetailsByQuery($emailCheck);
	$emailData=isset($emailDetails[0]['Email']) ?$emailDetails[0]['Email']:'';
	$StatusData=isset($emailDetails[0]['Status']) ?$emailDetails[0]['Status']:'';
	if($emailData!=''){
		if($StatusData==2){
			//check login
			$myloginData=$objMelonomaPrediction->checkLogin($email,$password);
			$fname=isset($myloginData[0]['FirstName']) ?$myloginData[0]['FirstName']:'';
			$lname=isset($myloginData[0]['LastName']) ?$myloginData[0]['LastName']:'';
			$consumerId=isset($myloginData[0]['ConsumerId']) ?$myloginData[0]['ConsumerId']:'';
			$emailVal=isset($myloginData[0]['Email']) ?$myloginData[0]['Email']:'';
			$userType=isset($myloginData[0]['UserType']) ?$myloginData[0]['UserType']:'';
			if($emailVal!=''){
				$userData=$consumerId."###".$fname."###".$lname."###".$emailVal."###".$userType;
				array_push($stats_arr["Message"], $userData);
				array_push($stats_arr["Status"], 1);
				echo json_encode($stats_arr);
			}else{
				array_push($stats_arr["Message"], 'Invalid login credentials!');
				array_push($stats_arr["Status"], 2);
				echo json_encode($stats_arr);
			}
			
		}else if($StatusData==1){
			array_push($stats_arr["Message"], 'Registration approval is pending!');
			array_push($stats_arr["Status"], 4);
			echo json_encode($stats_arr);			
		}
	}else{
		array_push($stats_arr["Message"], 'You are not registered with us!');
		array_push($stats_arr["Status"], 3);
		echo json_encode($stats_arr);
	}
}
?>