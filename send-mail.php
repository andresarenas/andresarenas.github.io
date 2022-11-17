<?php
/*
Name: 			Contact Form
Written by: 	Okler Themes - (http://www.okler.net)
Version: 		3.8.0
*/

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));

header('Content-type: application/json');

// Step 1 - Enter your email address below.
$to = 'andres.arenas@arenait.co';


// Step 2 - Enable if the server requires SMTP authentication. (true/false)
$enablePHPMailer = false;

$subject = 'Contacto ARENA IT';

if(isset($_POST['Email'])) {

	$Nombre 	 = $_POST['Nombre'];
	$Email 	 = $_POST['Email'];
	$Teléfono  = $_POST['Teléfono'];
	$website  = $_POST['website'];
	$Comentarios = $_POST['Comentarios'];

	$fields = array(
		0 => array(
			'text' => 'Name',
			'val' => $_POST['Nombre']
		),
		1 => array(
			'text' => 'Email address',
			'val' => $_POST['Email']
		),
		2 => array(
			'text' => 'Message',
			'val' => $_POST['Comentarios']
		),
		4 => array(
			'text' => 'Website',
			'val' => $website
		),
		5 => array(
			'text' => 'Remote Address',
			'val' => $_SERVER['REMOTE_ADDR']
		),
		6 => array(
			'text' => 'Forwarded For',
			'val' => $_SERVER['HTTP_X_FORWARDED_FOR']
		)
	);

	$message = "";

	foreach($fields as $field) {
		$message .= $field['text'].": " . htmlspecialchars($field['val'], ENT_QUOTES) . "<br>\n";
	}

	// Simple Mail
	if(!$enablePHPMailer) {

		$headers = '';
		$headers .= 'From: ' . $name . ' <' . $email . '>' . "\r\n";
		$headers .= "Reply-To: " .  $email . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

		if (mail($to, $subject, $message, $headers)){
			$arrResult = array ('response'=>'success');
		} else{
			$arrResult = array ('response'=>'error');
		}

	// PHP Mailer Library - Docs: https://github.com/PHPMailer/PHPMailer
	} else {

		include("php-mailer/PHPMailerAutoload.php");

		$mail = new PHPMailer;

		$mail->IsSMTP();                                      // Set mailer to use SMTP
		$mail->SMTPDebug = 0;                                 // Debug Mode

		// Step 3 - If you don't receive the email, try to configure the parameters below:

		//$mail->Host = 'mail.yourserver.com';				  // Specify main and backup server
		//$mail->SMTPAuth = true;                             // Enable SMTP authentication
		//$mail->Username = 'username';             		  // SMTP username
		//$mail->Password = 'secret';                         // SMTP password
		//$mail->SMTPSecure = 'tls';                          // Enable encryption, 'ssl' also accepted

		$mail->From = $email;
		$mail->FromName = $_POST['name'];
		$mail->AddAddress($to);								  // Add a recipient
		$mail->AddReplyTo($email, $name);

		$mail->IsHTML(true);                                  // Set email format to HTML

		$mail->CharSet = 'UTF-8';

		$mail->Subject = $subject;
		$mail->Body    = $message;

		if(!$mail->Send()) {
		   $arrResult = array ('response'=>'error');
		}

		$arrResult = array ('response'=>'success');

	}

	echo json_encode($arrResult);
	header( 'Location: http://arenait.co/index.html' ) ;

} else {

	$arrResult = array ('response'=>'error');
	echo json_encode($arrResult);
	header( 'Location: http://arenait.co/index.html' ) ;
}
?>
