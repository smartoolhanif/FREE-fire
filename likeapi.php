<?php

// Check if required parameters are set
if (isset($_GET['uid']) && isset($_GET['server_name'])) {
    // Get the values from the URL parameters
    $uid = $_GET['uid'];
    $server_name = $_GET['server_name'];
    
    // Define the hardcoded token URL
    $token_url = "https://smartoolhanif.github.io/FREE-fire/ind_ind.json"; 

    // Validate the token URL
    if (filter_var($token_url, FILTER_VALIDATE_URL)) {
        // Fetch the content of the token_url
        $token_json = @file_get_contents($token_url);

        if ($token_json === FALSE) {
            echo json_encode(["error" => "Unable to fetch data from token_url"]);
            exit;
        }

        // Decode the JSON data from the token URL
        $token_data = json_decode($token_json, true);

        if ($token_data === NULL) {
            echo json_encode(["error" => "Invalid JSON data from token_url"]);
            exit;
        }

        // Prepare the API request URL
        $api_url = "https://likeapiff.thory.in/like?uid=$uid&server_name=$server_name&token_url=" . urlencode($token_url);
        
        // Use cURL to call the API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Check for successful response
        if ($http_code == 200) {
            // Return the API response
            echo $response;
        } else {
            echo json_encode(["error" => "API call failed with status code $http_code"]);
        }
    } else {
        echo json_encode(["error" => "Invalid token_url format"]);
    }
} else {
    // Missing parameters error
    echo json_encode(["error" => "Missing required parameters: uid or server_name"]);
}
?>
