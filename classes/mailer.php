<?php

// require_once 'Mail.php';
// require_once 'Mail/mime.php';
require(SITE_PATH.'classes'.DIRSEP.'phpmailer'.DIRSEP.'class.phpmailer.php');

class mailer
{
	//PHPMailer mail
	private $php_mailer;

	function mailer()
	{
		
	}
	

	
	public function mail($from_mail, $from_name, $to_mail, $subject, $text){
		$this->php_mailer=new PHPMailer();
		$this->php_mailer->CharSet = "utf-8";
		
		$this->php_mailer->IsSendmail();
		
		//From Setting
		$this->php_mailer->From = $from_mail;
		$this->php_mailer->FromName = $from_name;
		//To Setting
		$this->php_mailer->AddAddress($to_mail);
		
		//Email Setting
		$this->php_mailer->Subject = $subject;
		$this->php_mailer->Body = $text;
		
		$this->php_mailer->IsHTML(true);
		
		if(!$this->php_mailer->Send()){
			return false;
		}
		else{
			return true;
		}
	}
	
	public function HTML_mail($from_mail, $from_name, $to_mail, $subject, $text, $flag_nl2br=true, $flag_striptag=false){
		
		$this->php_mailer=new PHPMailer();
		$this->php_mailer->CharSet = "utf-8";
		
		$this->php_mailer->IsSendmail();
		
		//From Setting
		$this->php_mailer->From = $from_mail;
		$this->php_mailer->FromName = $from_name;
		//To Setting
		$this->php_mailer->AddAddress($to_mail);
		
		//Email Setting
		$this->php_mailer->Subject = $subject;
		$this->php_mailer->Body = $flag_nl2br?nl2br($text):$text;
		$this->php_mailer->AltBody = $flag_striptag?strip_tags($text):$text;
		
		$this->php_mailer->IsHTML(true);
		
		if(!$this->php_mailer->Send()){
			return false;
		}
		else{
			return true;
		}
		
	}
	
	public function HTML_mail_smtp($from_mail, $from_name, $to_mail, $subject, $text, $flag_nl2br=true, $flag_striptag=false){
		
		$this->php_mailer=new PHPMailer();
		$this->php_mailer->CharSet = "utf-8";
		
		$this->php_mailer->IsSMTP();
		
		//From Setting
		$this->php_mailer->From = $from_mail;
		$this->php_mailer->FromName = $from_name;
		//To Setting
		$this->php_mailer->AddAddress($to_mail);
		
		//Email Setting
		$this->php_mailer->Subject = $subject;
		$this->php_mailer->Body = $flag_nl2br?nl2br($text):$text;
		$this->php_mailer->AltBody = $flag_striptag?strip_tags($text):$text;
		
		$this->php_mailer->IsHTML(true);
		
		if(!$this->php_mailer->Send()){
			return false;
		}
		else{
			return true;
		}
		
	}
	
	public function mail_with_attachment($from_mail, $from_name, $to_mail, $subject, $text, $attachment_path, $attachment_name){
		$this->php_mailer=new PHPMailer();
		$this->php_mailer->CharSet = "utf-8";
		
		$this->php_mailer->IsSendmail();
		
		//From Setting
		$this->php_mailer->From = $from_mail;
		$this->php_mailer->FromName = $from_name;
		//To Setting
		$this->php_mailer->AddAddress($to_mail);
		
		//Email Setting
		$this->php_mailer->Subject = $subject;
		$this->php_mailer->Body = $text;
		
		//Attachment
		$this->php_mailer->AddAttachment($attachment_path,$attachment_name);
		
		$this->php_mailer->IsHTML(true);
		
		if(!$this->php_mailer->Send()){
			return false;
		}
		else{
			return true;
		}
	}
	
	public function HTML_mail_with_attachment($from_mail, $from_name, $to_mail, $subject, $text, $attachment_path, $attachment_name, $flag_nl2br=true, $flag_striptag=false)
	{
		$this->php_mailer=new PHPMailer();
		$this->php_mailer->CharSet = "utf-8";
		
		$this->php_mailer->IsSendmail();
		
		//From Setting
		$this->php_mailer->From = $from_mail;
		$this->php_mailer->FromName =$from_name;
		//To Setting
		$this->php_mailer->AddAddress($to_mail);
		
		//Email Setting
		$this->php_mailer->Subject = $subject;
		$this->php_mailer->Body = ($flag_nl2br)?nl2br($text):$text;
		$this->php_mailer->AltBody = ($flag_striptag)?strip_tags($text):$text;
		
		$this->php_mailer->IsHTML(true);
		
		//Attachment
		for($i=0; $i<count($attachment_path); $i++){
			$this->php_mailer->AddAttachment($attachment_path[$i],$attachment_name[$i]);
		}
		
		if(!$this->php_mailer->Send()){
			return false;
		}
		else{
			return true;
		}
	}
	public function HTML_mail_with_attachment_smtp($host, $port, $username, $password, $auth, $from_mail, $from_name, $to_mail, $subject, $text, $attachment_path, $attachment_name, $flag_nl2br=true, $flag_striptag=false)
	{
		$this->php_mailer=new PHPMailer();
		$this->php_mailer->CharSet = "utf-8";
		
		$this->php_mailer->IsSMTP();
		
		//From Setting
			$this->php_mailer->Host = $host;
			$this->php_mailer->Username = $username;
			$this->php_mailer->Password = $password;
			$this->php_mailer->SMTPAuth = $auth;
			$this->php_mailer->Port = $port;
		
			$this->php_mailer->From = $from_mail;
			$this->php_mailer->FromName = $from_name;
			
		//To Setting
			$this->php_mailer->AddAddress($to_mail);
		
		//Email Setting
			$this->php_mailer->Subject = $subject;
			$this->php_mailer->Body = ($flag_nl2br)?nl2br($text):$text;
			$this->php_mailer->AltBody = ($flag_striptag)?strip_tags($text):$text;
		
			$this->php_mailer->IsHTML(true);
		
		//Attachment
			for($i=0; $i<count($attachment_path); $i++)
			{
				$this->php_mailer->AddAttachment($attachment_path[$i],$attachment_name[$i]);
			}
		
		//Send
			if(!$this->php_mailer->Send())
			{
				return false;
			}
			else
			{
				return true;
			}
	}
}
?>