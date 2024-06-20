<?php
require '../config/database.class.php';
global $conn;

// message that will be displayed when everything is OK :)
$okMessage = 'Contact form successfully submitted. Thank you, I will get back to you soon!';

// If you are already send your message
$alreadySendMessage = 'You just send you message I will get back to you soon!';

// If something goes wrong, we will display this message.
$errorMessage = 'There was an error while submitting the form. Please try again later';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_SPECIAL_CHARS);
    $phone = filter_var($_POST['phone'], FILTER_VALIDATE_INT);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_SPECIAL_CHARS);

    $query = $conn->prepare("SELECT * FROM contact WHERE email=?");
    $result = $query->execute([$email]);
    $result = $query->fetch();

    if (!$result){
        try {
            $insertQuery = $conn->prepare("INSERT INTO contact (name,email, subject, phone, message) VALUES (?,?,?,?,?)");
            $insertResult = $insertQuery->execute([$name, $email, $subject, $phone, $message]);
            $responseArray = array('type' => 'success', 'message' => $okMessage);
        } catch (Exception $e) {
//            echo $e->getMessage();
            $responseArray = array('type' => 'danger', 'message' => $errorMessage);
        }
    }else{
        $responseArray = array('type' => 'danger', 'message' => $alreadySendMessage);
    }


// if requested by AJAX request return JSON response
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $encoded = json_encode($responseArray);
        header('Content-Type: application/json');
        echo $encoded;
    } // else just display the message
    else {
        echo $responseArray['message'];
    }
}