<?php
/** Class to handle all date related queries & functionalities
 * @Author : Shivanand,Rajeev,Harish
 * @package STPM
 * @Date : 31/07/2015
 * @LastModified : 07/03/2016
 * @Modification Details :
 * 				Added 	: Added getTimeDiffInSecond.
 * 				Removed :
 * 				Updated :
 * @Modified By :Rajeev
 */
require_once "DbManager.class.php";

class Date {

	/**
	 * Function to get the difference between two date time given
	 * @Author : Shivanand,Rajeev,Harish
	 * @Date : 31/07/2015
	 * @LastModified : 31/07/2015
	 * @ModificationDetails : Newly Added
	 * @ModifiedBy :Rajeev
	 */
	public function getTimeDiffInHours($DateTime1,$DateTime2) {
		$query="SELECT ceil(time_to_sec(TimeDiff('$DateTime1','$DateTime2'))/3600) As Diff FROM sbDummy";
		unset($objDbManager);
		$objDbManager= new Db_Manager();
		$result=$objDbManager->executeSelectQuery($query);
		return isset($result[0]['Diff'])?$result[0]['Diff']:0;
	}

	/**
	 * Function to get the difference between two date time given
	 * @Author : Rajeev
	 * @Date : 07/03/2016
	 * @LastModified : 07/03/2016
	 * @ModificationDetails : Newly Added
	 * @ModifiedBy :Rajeev
	 */
	public function getTimeDiffInSeconds($DateTime1,$DateTime2) {
		return $totalQcTimeDSS=strtotime($DateTime1)-strtotime($DateTime2);
		$query="SELECT time_to_sec(TimeDiff('$DateTime1','$DateTime2')) As Diff FROM sbDummy";
		unset($objDbManager);
		$objDbManager= new Db_Manager();
		$result=$objDbManager->executeSelectQuery($query);
		return isset($result[0]['Diff'])?$result[0]['Diff']:0;
	}
}?>
