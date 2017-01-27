<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Your Voice Counts Answer Received</title>
	<link rel="stylesheet" type="text/css" href="default.css">
</head>
<body>
	<div id="responseText">
	<?php 
			function died($error) {
				// your error code can go here
				echo "<p>There was a problem with the information you entered. Please try again.</p>";
				echo "<p>". $error ."</p>";
				die();
			}
			
			function clean_string($string)
			{
				$bad = array(
					"content-type",
					"href",
					"<",
					"http"
				);
				return str_replace($bad, "", $string);
			}
			
			// validation expected data exists
			if(!isset($_POST['name'])) {
				died('There appears to be a problem with the information you submitted.');       
			}
			
			$postTime = (date("d h:i:s")); 
			$name = $_POST['name']; // required
			$email_from = $_POST['email']; // required
			$phone = $_POST['phone']; // not required
			$comment = $_POST['timestamp']; //  
			$audioRecodingFileName = "";
			$error_message = "";

			if($name == ""){
				$error_message .= "Please enter your name.<br />";
			}

			$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
			if(!preg_match($email_exp,$email_from)) {
				$error_message .= 'The e-mail address you entered "'. $email_from .'" is not valid.<br />';
			}
			
			if(strlen($error_message) > 0) {
				died($error_message);
			}
			
			$storageFolder = "yt3/";
			$semi_rand = substr(md5(time()), 0, 4);
			
			if ($_FILES['audioRecording']['size'] > 0) {
				$audioRecodingFileName = $semi_rand . ".mp3";

				$uploadDirectory = "/var/www/html/hackathon/" . $storageFolder .$audioRecodingFileName;
				
				shell_exec('avconv -i ' . $_FILES['audioRecording']['tmp_name'] . ' ' . $uploadDirectory . ' &');
			} 
			//else {
	//print_r($_FILES);	
			//}
			
			$answer_message =  "\"" . clean_string($semi_rand)."\", ";
			$answer_message .= "\"" . clean_string($name)."\", ";
			$answer_message .= "\"" . clean_string($email_from)."\", ";
			$answer_message .= "\"" . clean_string($phone)."\", ";
			$answer_message .= "\"" . clean_string($comment)."\", ";
			
			$answer_message .= "\"" . $postTime ."\", ";
			
			$answer_message .= "<a href=\"http://www.geoiptool.com/?IP=". $_SERVER['REMOTE_ADDR']."\" target=\"_blank\">Location</a>, ";
			
			if($audioRecodingFileName != ""){
				$answer_message .= "<audio controls><source src=\"https://question.maf.org/hackathon/" . $storageFolder . $audioRecodingFileName . "\"></audio>";
			}else{
				$answer_message .= "No Audio";
			}
			
			$myfile = fopen("/var/www/html/hackathon/" . $storageFolder . "answerLog.txt", "a") or die("Unable to open file!");
			fwrite($myfile, "\n". $answer_message);
			fclose($myfile);
			
			// Start sending e-mail
			$error_message = "";
			$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
			
			if(!preg_match($email_exp,$email_from)) {
				died('The e-mail address you entered "'. $email_from .'" is not valid.<br />');
			}
			
			$email_to = clean_string($email_from);
			$email_subject = "MAF \"Your Voice Counts\" Submission";
			 
			$email_message = "Dear " . clean_string($name). ", \n\n";
			$email_message .= "Thanks for being part of the conversation! It is exciting to see what the next generation of believers will accomplish for God's Kingdom. ";
			
			// If not subscribe to newsletter
			// if(clean_string($bpSub) != "yes") {
				// $email_message .= " If you would like to find out more about how Mission Aviation Fellowship uses aviation and technology in fulfilling the Great Commission, visit https://maf.org/ or sign up to receive the Boarding Pass eNewsletter (https://www.maf.org/get-involved/subscribe), a monthly email with stories, pics, and videos from around the world.\n\n";
			// } else {
				// $email_message .= "We will add your subscription for the Boarding Pass eNewsletter. Look for it in your e-mail soon.\n\n";
			// }
			
			$email_message .= "Let's keep the conversation going about how you can use your unique talents to join in the work God is doing around the world. \n\n";
			//$email_message .= "Thank you! \n\n";
			
			$email_message .= "Your answer reference code is " . $semi_rand . ".\n";

			// create email headers
			$headers = 'From: Mission Aviation Fellowship Recruiting <recruiting@maf.org>' . "\r\n".
			'Reply-To: Mission Aviation Fellowship Recruiting <recruiting@maf.org>' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
			//@mail($email_to, $email_subject, $email_message, $headers);  
		?>
		<p>Thanks for being part of the conversation! It is exciting to see what the next generation of believers will accomplish for God's Kingdom.</p>

		<p>Let's keep the conversation going about how you can use your unique talents to join in the work God is doing around the world. MAF will be contacting you soon.</p>

		<p>Your answer reference code is <span class="refCode">#<?php echo $semi_rand ?></span>.</p>
	</div>
</body>
</html>
