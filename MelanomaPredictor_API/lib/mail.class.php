<?php
/**
 * Class file to handle all mailing functionality for Moonshot Dashboard
 * @Author: Rajeev
 * @Package: STPM
 * @Date:20/07/2016
 * @LastModified: 29/07/2017
 * @ModificationDetails :
 * 				Added	:mailToGiveAlertForEmptyField
 * 				Deleted	:
 * 				Updated	:
 * Modified by : Rajeev
*/

require_once '/opt/lampp/htdocs/MelanomaPredictor_API/lib/class.phpmailer.php';

class Mail{

// 	const MAILING_TEST = "rajeev.kumar@cellworksgroup.com";
 	const MAILING_TEST = "";
	public static function sendMailer($arrToList,$arrCCList,$subject,$html_mail,$attachment,$changeCSS=false,$arrBCCList=array()) {
		$mail = new PHPMailer();
		if($attachment !=""){
			$mail->AddAttachment($attachment, basename($attachment));
		}
		$mail->Subject = $subject;
		$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional
		$style = file_get_contents("/opt/lampp/htdocs/MelanomaPredictor_API/css/mail_style.css");
		if($changeCSS){$changeStyle ='style="width:650px;text-align:left;"';}else{$changeStyle="";}
		$message ='<html>
					<head><title>
						'.$subject.'
					</title></head>
					<body style="bgcolor:#FFFFFF;">
						<style>
						'.$style.'
						</style>
						<table cellspacing="0px" cellpadding="0px" border="0" width="100%" id="mailBody" '.$changeStyle.'>
							<tr>
								<td style="color: #1A0033;font-family: Courier,sans-serif,Helvetica,Arial;font-size: 12px;margin: 0;padding-left:5px;padding-top: 10px;text-align: justify;">'.$html_mail.'</td>
							</tr>
						</table>
						<br clear="all" />
						<div class="warn">This is an automated message. Please DO NOT reply</div>
					</body></html>';

		unset($html_mail,$style);
		$mail->MsgHTML($message);
		if(self::MAILING_TEST !="") {
			$mail->AddAddress(self::MAILING_TEST);
		} else {
			foreach ($arrToList as $email) {
				$mail->FromName='Moonshot Dashboard';
				$mail->AddAddress($email);
			}
			foreach ($arrCCList as $email) {
				$mail->AddCC($email);
			}
			foreach ($arrBCCList as $email) {
				$mail->AddBCC($email);
			}
		}
		if($mail->Send()) {
			return 1;
		} else {
			//$fp=fopen('logs/.mailErrorLog',"a+");
			//fwrite($fp,"\n".date('d-m-Y H:i:s')."\tmail\t".$mail->ErrorInfo);
			//fclose($fp);
			return 0;
		}
	}

}
?>
