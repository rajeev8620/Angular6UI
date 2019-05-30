<?php
/**
 * Configuration file for Melanoma prediction
 * @Author:  Rajeev Kumar
 * @Date :29/05/2019
 * @LastModified: 29/05/2019
 * @Modification Details :Added Newly.
 * @Modified By : Rajeev
 */
session_start();
error_reporting(0);
//error_reporting(E_ALL & ~E_DEPRECATED);
date_default_timezone_set('Asia/Kolkata');
// define('TIMEZONE', 'America/Los_Angeles');
// date_default_timezone_set(TIMEZONE);
// date_default_timezone_set('America/Los_Angeles');

ini_set('memory_limit', '-1');
//local
define('DATABASE_HOST_NAME', 'cs209.cwgblr.com');
define('DATABASE_PASSWORD', 'Y2VsbHdvcmtz');//live
define('DATABASE_NAME', 'MelanomaPredictor');
define('DATABASE_USER_NAME', 'root'); 

//aws database
// define('DATABASE_HOST_NAME', 'cellworksdb.c1l5iracffd0.us-east-1.rds.amazonaws.com');
// define('DATABASE_PASSWORD', 'Y2VsbHdvcmtzMTIz');//live
// define('DATABASE_NAME', 'MelanomaPredictor');
// define('DATABASE_USER_NAME', 'melanomauser'); 

define('DATAUPLOADFOLDER', 'MELANOMA_DATA/');

$xlsSampleFilePath="/opt/lampp/htdocs/MelanomaPredictor_API/dependencies/sampleFile/sample.xls";
$vcfSampleFilePath="/opt/lampp/htdocs/MelanomaPredictor_API/dependencies/sampleFile/sample.vcf";

$binaryPath="/opt/lampp/htdocs/MelanomaPredictor_API/dependencies/melanomaRelapse_pred";
$binaryDependency="/opt/lampp/htdocs/MelanomaPredictor_API/dependencies/dependencies.zip";
$vcfDependency="/opt/lampp/htdocs/MelanomaPredictor_API/dependencies/vcf_parser.sh";

$defaultDB=mysql_connect(DATABASE_HOST_NAME, DATABASE_USER_NAME, base64_decode(DATABASE_PASSWORD)) or die ("Class ".get_class().": Error while connecting to DB (link)");
mysql_selectdb(DATABASE_NAME , $defaultDB) or die (print "Class ".get_class()." Error while selecting DB");

?>
