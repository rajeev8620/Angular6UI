<?php
/** Class to handle all Melonoma Prediction related queries & functionalities
 * @Author : Rajeev Kumar
 * @package MelonomaPrediction
 * @version 1.0
 * @Date : 22/05/2019
 * @LastModified : 22/05/2019
 * @Modification Details :Added Newly
 * @Modified By :Rajeev
 */
require_once '/opt/lampp/htdocs/MelanomaPredictor_API/config/config.php';
require_once '/opt/lampp/htdocs/MelanomaPredictor_API/lib/DbManager.class.php';

class MelonomaPrediction{
	
	/********************************************************************************/
	/*							SELECT QUERY										*/
	/********************************************************************************/
	
	/**
	 * Function to get login details
	 * @Author 			: Rajeev
	 * @Date 			: 22/05/2019
	 * @Modified Date 	: 22/05/2019
	 * @Modification Details : Added Newly
	 * @Modified By 	: Rajeev
	 */
	public function checkLogin($login,$password){
		$query = "SELECT ConsumerId,FirstName,LastName,Email,UserType FROM ConsumerDetails WHERE (Email='$login' AND Password=md5('$password')) AND Status IN (2)";
		unset($objDbManager);
		$objDbManager = new Db_Manager();
		return $objDbManager->executeSelectQuery($query);
	}
	
	/**
	 * Function to fetch table column for given condition
	 * @Author 			: Rajeev Kumar
	 * @Date 			: 22/05/2019
	 * @Modified Date 	: 22/05/2019
	 * @Modification Details : Added Newly.
	 * @Modified By 	: Rajeev
	 */
	public function getTableDetailsByQuery($Myquery) {
		$query=" $Myquery ";
		unset($objDbManager);
		$objDbManager= new Db_Manager();
		return $objDbManager->executeSelectQuery($query);
	}
	
	/********************************************************************************/
	/*							INSERT QUERY										*/
	/********************************************************************************/
	/**
	 * Function to insert the newly data for given table
	 * @Author : Rajeev Kumar
	 * @Date : 22/05/2019
	 * @LastModified :22/05/2019
	 * @LastModificationDetails : Added Newly.
	 * @LastModifiedBy : Rajeev
	 */
	public function insertNewDataIntoTable($param,$table) {
		unset($objDbManager);
		$objDbManager= new Db_Manager();
		$autoIncId="";
		if($table=="ConsumerDetails"){
			$autoIncId="ConsumerId=NULL,";
		}else if($table=="OrderDetails"){
			$autoIncId="OrderId=NULL,";
		}
		$query="INSERT INTO $table SET $autoIncId";
		foreach($param as $key=>$value) {
			$query .= $key."='".$objDbManager->escape_String($value)."',";
		}
		$query .= "LastModified=NOW()";
		$objDbManager->executeQuery($query);
		return $objDbManager->getLastInsertId();
	}
	
	/********************************************************************************/
	/*							UPDATE QUERY										*/
	/********************************************************************************/
	
	
	/**
	 * Function to update table based on given condition query
	 * @Author : Rajeev Kumar.
	 * @Date : 22/05/2019
	 * @LastModificationDate :22/05/2019
	 * @LastModificationDetails : Added Newly.
	 * @LastModifiedBy :Rajeev Kumar.
	 */
	
	public function updateTableByQuery($query) {
		unset($objDbManager);
		$objDbManager= new Db_Manager();
		return $objDbManager->executeQuery($query);
	}
	
	/**
	 * Function to update data table.
	 * @Author : Rajeev Kumar.
	 * @Date : 22/05/2019
	 * @LastModificationDate :22/05/2019
	 * @LastModificationDetails : Added Newly.
	 * @LastModifiedBy :Rajeev Kumar.
	 */
	
	public function updateTableRecords($param,$condVal,$condCol="OrderId",$table="OrderDetails") {
		unset($objDbManager);
		$objDbManager= new Db_Manager();
		$query="UPDATE $table SET ";
		foreach($param as $key=>$value) {
			$query .= $key."='".$objDbManager->escape_String($value)."',";
		}
		$query=rtrim($query,',');
		$query .=" WHERE $condCol='".$condVal."'";
		return $objDbManager->executeQuery($query);
	}
	
	/********************************************************************************/
	/*							DELETE QUERY										*/
	/********************************************************************************/
	/**
	 * Delete Table records
	 * @Author : Rajeev Kumar
	 * Start Date : 22/05/2019
	 * Modification Date :22/05/2019
	 * Modification Details : Added Newly
	 * MOdified By :Rajeev
	 */
	public function deleteTableDetails($condVal,$condCol="OrderId",$table="OrderDetails"){
		unset($objDbManager);
		$objDbManager = new Db_Manager();
		$query="DELETE FROM $table WHERE $condCol='".$condVal."'";
		return $objDbManager->executeQuery($query);
	}
	
	
}?>
