<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
include('./includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Handle preflight requests
    exit;
}
if(isset($_REQUEST) && isset($_REQUEST['type'])) {
    if ($_REQUEST['type'] == 'load-comments') {
        $query = "Select * FROM comments where page_name = '" . $_REQUEST['page_name'] . "' and status = '1' order by id desc";
        $results = mysqli_query($con, $query);
        $html = '';
        if (mysqli_num_rows($results) > 0) {
            while ($row = mysqli_fetch_array($results)) {
                $html .= '<div class="user_comment">
                <div class="user_avatar">
                    <img class="profileImage" src="https://ui-avatars.com/api/?background=random&name=' . $row['name'] . '">
                </div>
                <div class="comment_body">
                    <p><strong>' . $row['name'] . '</strong> Says:</p>
                    <p><small>' . date('d M Y', strtotime($row['created_at'])) . ' at ' . date('H:i:s', strtotime($row['created_at'])) . '</small></p>
                    <p style="margin-top: 5px;">' . $row['comment'] . '</p>
                </div>
            </div>';
            }
        } else {
            $html = '<div class="user_comment">Sorry! No Comment Found.</div>';
        }

        echo json_encode(['success' => 'true', 'html' => $html]);
    }
    if ($_REQUEST['type'] == 'site_map_refresh') {
        generateSiteMap($lang_arr, $settings['site_index_follow'], $con);
        echo 'ok';
    }
    if ($_REQUEST['type'] == 'get_latest_results') {
        $date = date("Y-m-d");

        $query_cat_data = "SELECT * FROM categories ORDER BY id DESC";
        $result_cat_data = mysqli_query($con, $query_cat_data);
        $categories = [];
        while ($row_cat = mysqli_fetch_array($result_cat_data)) {
            $categories[$row_cat['id']] = $row_cat;
        }
        $query_results = "SELECT * FROM tbl_loterianacional WHERE result_date='" . $date . "' ORDER BY cat_id ASC";
        $result_results = mysqli_query($con, $query_results);
        if (mysqli_num_rows($result_results) == 0 && $date == date("Y-m-d")) {
            $query_results = "SELECT * FROM tbl_loterianacional ORDER BY result_date DESC LIMIT 5";
            $result_results = mysqli_query($con, $query_results);
        }
        $html = '';
        while ($row_data = mysqli_fetch_array($result_results)) {
            $result_numbers = json_decode($row_data['result_numbers'], 1);
            $html .= '<div class="game-block past">
                        <div class="game-info">
                            <div class="game-logo">
                                <img src="' . $site_url . '/images/cat_images/' . $categories[$row_data['cat_id']]['image'] . '" alt="' . $categories[$row_data['cat_id']]['name'] . '">
                            </div>
                            <div class="game-details">
                                <a class="game-title" href="' . setUrl($categories[$row_data['cat_id']]['slug']) . '"><span class="fas fa-chart-bar"> ' . $categories[$row_data['cat_id']]['name'] . '</span></a>' . _date($row_data['result_date']) . '<div class="game-scores ball-mode">';

            foreach ($result_numbers as $key => $result_number) {
                if ($key >= 5) {
                    continue;
                }
                $html .= '<span class="score" style="">' . $result_number . '</span>';
            }
            if ($row_data['result_multiplier'] == 'SI') {
                $html .= '<div class="circle-plicador" style="background: darkblue"><div class="plicador-text">MULTI<br>PLICADOR<br><i style="font-size: 15px;" class="fa fa-check"></i></div></div>';
            }
            if ($row_data['result_multiplier'] == 'NO') {
                $html .= '<div class="circle-plicador" style="background: red"><div class="plicador-text">MULTI<br>PLICADOR<br><i style="font-size: 15px;" class="fa fa-times"></i></div></div>';
            }
            $html .= '</div></div><div class="clearfix"></div><div class="game-footer"><a href="' . setUrl($categories[$row_data['cat_id']]['slug']) . '/' . urlDate($row_data['result_date']) . '" target="_blank"><span class="session-badge">#' . $row_data['result_code'] . '</span></a></div></div></div>';
        }
        echo json_encode(['success' => 'true', 'data' => $html]);
    }
}
function sendEmail() {
    // SMTP Configuration
    $host = 'sandbox.smtp.mailtrap.io';
    $port = 587;
    $username = '357750cdfe945e'; // Replace with your Mailtrap username
    $password = 'dfc89730b2a1be';  // Replace with your Mailtrap password
    $from = 'noreply@cronjob.com'; // Replace with a valid 'From' email
    $to = 'babar.afzalmalik@gmail.com'; // Replace with your email address
    $subject = 'Cron Job Email Test';
    $message = "This is a test email to confirm the cron job is running successfully.\r\n";

    // Create email headers
    $headers = "From: <$from>\r\n";
    $headers .= "To: <$to>\r\n";
    $headers .= "Subject: $subject\r\n";
    $headers .= "Content-Type: text/plain\r\n";

    // Connect to SMTP server
    $socket = fsockopen($host, $port, $errno, $errstr, 10);
    if (!$socket) {
        echo "Could not connect to SMTP server. Error: $errstr ($errno)";
        return false;
    }

    // Read SMTP server response
    function readSmtpResponse($socket) {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') break;
        }
        return $response;
    }

    // Send SMTP commands
    fwrite($socket, "EHLO $host\r\n");
    readSmtpResponse($socket);

    fwrite($socket, "AUTH LOGIN\r\n");
    readSmtpResponse($socket);

    fwrite($socket, base64_encode($username) . "\r\n");
    readSmtpResponse($socket);

    fwrite($socket, base64_encode($password) . "\r\n");
    readSmtpResponse($socket);

    fwrite($socket, "MAIL FROM: <$from>\r\n");
    readSmtpResponse($socket);

    fwrite($socket, "RCPT TO: <$to>\r\n");
    readSmtpResponse($socket);

    fwrite($socket, "DATA\r\n");
    readSmtpResponse($socket);

    fwrite($socket, "$headers\r\n$message\r\n.\r\n");
    readSmtpResponse($socket);

    fwrite($socket, "QUIT\r\n");
    fclose($socket);

    echo "Test email sent successfully.";
    return true;
}
?>