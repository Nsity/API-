<?php
    // Replace with the real server API key from Google APIs
    $apiKey = "AIzaSyAM-A6ttQQmkqllswtVu5eHQc01QmVq9gs";

    // Replace with the real client registration IDs
    $registrationIDs = array( "ecL0Gf0ZKEQ:APA91bEqdFsHWGcPKEJDxqYbqyTVjJjMjrvB4ohVumFjahxsdUTwi-lpCJfnhGEQx-rEkbvWfaB6WRG5X5RPR9vSoLQQh9IEBAfwjeQTtZ7kYEoHY3MBsgYI4talWzRVXGYtlGGG5o14");

    // Message to be sent
    $message = "hi Shailesh";

    // Set POST variables
    $url = 'https://android.googleapis.com/gcm/send';

    $fields = array(
        'registration_ids' => $registrationIDs,
        'data' => array( "message" => $message ),
    );
    $headers = array(
        'Authorization: key=' . $apiKey,
        'Content-Type: application/json'
    );

    // Open connection
    $ch = curl_init();

    // Set the URL, number of POST vars, POST data
    curl_setopt( $ch, CURLOPT_URL, $url);
    curl_setopt( $ch, CURLOPT_POST, true);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields));

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_POST, true);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields));

    // Execute post
    $result = curl_exec($ch);

    // Close connection
    curl_close($ch);
    echo $result;
    //print_r($result);
    //var_dump($result);
?>
