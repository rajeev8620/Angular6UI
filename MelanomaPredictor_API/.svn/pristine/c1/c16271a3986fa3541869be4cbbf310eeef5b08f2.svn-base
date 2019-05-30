<?php
/**
 * Handling the database connections and all database related operations
 * Class Db_Manager
 * @package CWHRMS
 * @version 2
 * @Author : Shivanand
 * @Date : 05/05/2010
 * @LastModified : 10/08/2012
 * @Modification Details : Handling special character through MYSQL.
 * @Modified By: Shivanand
 */
class Db_Manager
{

	const DB_HOST_NAME = DATABASE_HOST_NAME;

	const DB_USER_NAME = DATABASE_USER_NAME;

	const DB_PASSWORD = DATABASE_PASSWORD;

	const DB_NAME = DATABASE_NAME;

	const FETCHMODE_NUM = 1;

	const FETCHMODE_ASSOC = 2;

	const FETCHMODE_OBJECT = 3;

	protected $dbhLink;
	protected $strSql;
	protected $resResult;
	protected $intRowsAffected;
	protected $arrRows = array();

	protected $objChildClass;


	public function __construct($dbname=self::DB_NAME,$dbuser=self::DB_USER_NAME,$dbpass=self::DB_PASSWORD,$dbhost=self::DB_HOST_NAME)
	{
		$this->dbhLink = mysql_connect($dbhost, $dbuser, base64_decode($dbpass)) or die ("Class ".get_class().": Error while connecting to DB (link)");
		mysql_set_charset('utf8',$this->dbhLink);
		@mysql_selectdb($dbname , $this->dbhLink) or die (print "Class ".get_class()." Error while selecting DB");
	}
	
	/**
	 * Begin the databse transation
	 *
	 */
	public function beginTransaction()
	{
		@mysql_query("BEGIN");
	}

	/**
	 * Commiting the started transaction
	 *
	 */
	public function commitTransaction()
	{
		@mysql_query("COMMIT");
	}

	/**
	 * Rollbacking the current transaction
	 *
	 */
	public function rollbackTransaction()
	{
		@mysql_query("ROLLBACK");
	}

	public function __destruct()
	{
		@mysql_close($this->dbhLink);
	}

	/**
	 * Delete by primary key
	 *
	 * @param unknown $unkChildClass Class name
	 * @param int $intPKeyValue Primary key
	 * @return boolean If delete success that retuns true
	 */
	public function deleteByPKey($unkChildClass, $intPKeyValue)
	{
		$this->objChildClass =  new $unkChildClass;
		try {

			$this->createDeleteQueryByPKey($intPKeyValue);
			$this->_Query();

			return true;
		}
		catch (Exception $objExe) {

			throw $objExe;
		}
	}

	/**
	 * Select the record set by primary key of the table
	 *
	 * @param unknown $unkChildClass Class name
	 * @param int $intPKeyValue Primary key of the table
	 * @return array Array contain selected records from the table.
	 */
	public function selectByPKey($unkChildClass, $intPKeyValue)
	{
		try {
			$this->objChildClass =  new $unkChildClass;
			$this->createSelectQueryByPKey($intPKeyValue);
			$this->executeSelectQuery($this->strSql);
			$this->fetchResultSet();
			return $this->arrRows;
		}
		catch (Exception $objExe) {
			throw $objExe;
		}
	}

	/**
	 * Create delete query by primary key
	 *
	 * @param int $intPKeyValue Primary key of the table
	 */
	private function createDeleteQueryByPKey($intPKeyValue = null)
	{
		$this->strSql = null;
		if($intPKeyValue != null ) {
			$this->strSql = " DELETE FROM ";
			$this->strSql .= self::DB_NAME.'.'.$this->objChildClass->strTableName;
			$this->strSql .= " WHERE ";
			$this->strSql .= $this->objChildClass->strTableName.".".$this->objChildClass->strPrimaryKey." = ".$intPKeyValue;
		}
		else {
			throw new Exception("Invalid primary key value");
		}
	}

	/**
	 * Create select query by prymary key
	 *
	 * @param int $intPKeyValue Prymary key of the table
	 */
	private function createSelectQueryByPKey($intPKeyValue = null)
	{
		$this->strSql = null;
		if($intPKeyValue != null ) {
			$this->strSql = "SELECT * FROM ";
			$this->strSql .= self::DB_NAME.'.'.$this->objChildClass->strTableName;
			$this->strSql .= " WHERE ";
			$this->strSql .= $this->objChildClass->strTableName.".".$this->objChildClass->strPrimaryKey." = ".$intPKeyValue;
		}
		else {
			throw new Exception("Invalid primary key value");
		}
	}

	/**
	 * Function executes the current query
	 *
	 */
	private function _Query()
	{
		try {
			$this->resResult = mysql_query($this->strSql, $this->dbhLink);
			if(!$this->resResult) {
				echo "query!<br />".$this->strSql."<br />";
				throw new Exception("Invalid query !");
			}
			$this->intRowsAffected = mysql_affected_rows($this->dbhLink);
			$this->strSql = null;
		}
		catch (Exception $objExe) {
			throw $objExe;
		}
	}

	/**
	 * Fetches the record set the current result set
	 *
	 */
	private function fetchResultSet()
	{
		try {
			$this->arrRows = array();
			while ( $arrRow = mysql_fetch_array($this->resResult, MYSQL_ASSOC)) {
				$this->arrRows[] = $arrRow;
			}
		}
		catch (Exception $objExc) {
			throw $objExc;
		}
	}

	/**
	 * Execute a query 
	 *
	 * @param string $strSql Sql query for execution
	 * @return boolean if the query is success returns true
	 */
	public function executeQuery($strSql)
	{
		$this->strSql = $strSql;
		try {
			$this->_Query();
		}
		catch (Exception $objExe) {
			throw $objExe;
		}
		return true;
	}
	
	/** Execute a set of queries with in a transaction block
	 *
	 * @param string $arrSql Sql queries for execution
	 * @return boolean if the transaction is success returns true
	 */
	public function executeTransactQueries($arrSql)
	{
		try {
			$this->beginTransaction();
			foreach ($arrSql as $strSql) {
				$this->strSql = $strSql;
				$this->_Query();
			}
			$this->commitTransaction();
		}
		catch (Exception $objExe) {
			$this->rollbackTransaction();
			return false;
		}
		return true;
	}

	/**
	 * Execute select query
	 *
	 * @param string $strSql Sql query for execution
	 * @return array Array contain selected record sets
	 */
	public function executeSelectQuery($strSql = null)
	{
		try {
			if($strSql != null) {
				$this->strSql = $strSql;
				$this->_Query();
				$this->fetchResultSet();
				return $this->arrRows;
			}
			else {
				throw new Exception('Invalid query string');
			}
		}
		catch (Exception $objExe) {
			throw $objExe;
		}

	}

	/**
	 * Insert a new record into the database table
	 *
	 * @param string $strSql Sql query
	 * @return int Last inserted id
	 */
	public function insert($strSql = null)
	{
		if($strSql != null) {
			$this->strSql = $strSql;
			$this->_Query();
			return $this->getLastInsertId();
		}
		else {
			throw new Exception("Invalid sql query");
		}
	}

	/**
	 * Get last inserted id.
	 *
	 * @return unknown
	 */
	public function getLastInsertId()
	{
		try {
			return mysql_insert_id($this->dbhLink);
		}
		catch (Exception $objExe) {
			throw $objExe;
		}
	}

	/**
	 * Get the affected rows
	 *
	 * @return int Affected rows count
	 */
	public function getAffectedRows()
	{
        try {
			return $this->intRowsAffected;
		}
		catch (Exception $objExe) {
			throw $objExe;
		}
    }

	/**
	 * Escape the input with mysql_real_escape_string function
	 *
	 * @param string $strInput Input string
	 * @return string Escaped string.
	 */
	public function escape_String($strInput)
	{
			return mysql_real_escape_string($strInput,$this->dbhLink);
	}
	
	/**
	 * Get Resource Id fro sql query
	 * @Author : Homa Parween
	 * Start Date : 16/01/2012
	 * Modification Date : 
	 * Modified By : 
	 * Modification Details : Newly Added
	 */
	
	public function getQueryResource($query){
		try{
			$this->resResult=mysql_query($query,$this->dbhLink);
			if(!$this->resResult){
				echo "query!<br />".$this->strSql."<br />";
				throw new Exception("Invalid query !");
			}
			return $this->resResult;
		}
		catch (Exception $objExe) {
			throw $objExe;
		}
	}
}
