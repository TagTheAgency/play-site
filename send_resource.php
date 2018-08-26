<?php

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      	$sender_email = stripslashes($_POST["email"]);

        if (empty($sender_email)) {
          echo '{"error": "Please specify an email address"}';
          http_response_code(400);
          exit;
        }
      	$response = $_POST["g-recaptcha-response"];

      	$url = 'https://www.google.com/recaptcha/api/siteverify';
      	$data = array(
      		'secret' => '6LdiPmsUAAAAAMxjK3Ln3nrfilUYapRzk_uWzKFJ',
      		'response' => $_POST["g-recaptcha-response"]
      	);
        $options = array(
        	'http' => array (
        		'method' => 'POST',
        		'content' => http_build_query($data)
        	)
        );
        $context  = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        $captcha_success=json_decode($verify);
        if ($captcha_success->success==false) {
          echo '{"error": "Failed reCaptcha"}';
          http_response_code(403);
          exit;
        } else if ($captcha_success->success==true) {

          //send email to client
          $subject = "TAG The Agency Clip Guide";
          $body = "Thanks for your interest.  Download the TAG The Agency Clip Guide from https://play.tagtheagency.com/resources/video_script_how_to_guide.pdf\n\n";
          $body .= "We hope it helps to tell your story.  The team at TAG The Agency are always happy to help tell your story in a unique and effective way.  Get in touch to talk to us.";
          $email_headers = "From: TAG The Agency <play@tagtheagency.com>";
          if (!mail($sender_email, $subject, $body, $email_headers)) {
            echo '{"error": "Failed to send email"}';
            http_response_code(500);
            exit;
          }


          //send email to us

          $subject = "TAG The Agency Clip Guide";
          $body = "New request for clip guide\n\n";
          $body .= "From: $sender_email";
          $email_headers = "From: TAG The Agency <colin@tagtheagency.com>";
          mail("colin@tagtheagency.com", $subject, $body, $email_headers);

          echo '{"status": "success"}';
          exit;
        }
      }
      echo '{"error": "Invalid submission"}';
      http_response_code(400);

?>
