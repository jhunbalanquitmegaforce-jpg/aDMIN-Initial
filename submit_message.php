<?php
include 'config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php#contact");
    exit;
}
$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($fullname === '' || $email === '' || $subject === '' || $message === ''){
    die("Please complete all required fields. ");
}
$sql = "INSERT INTO contact_messages (fullname, email, subject, message)
    VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($con, $sql);
if (!$stmt) {
    die("PREPARE ERROR:" . mysqli_error($con));
}
mysqli_stmt_bind_param(
    $stmt,
    "ssss",
    $fullname,
    $email,
    $subject,
    $message
);
if (!mysqli_stmt_execute($stmt)) {
    die("INSERT ERROR: " . mysqli_stmt_error($stmt));
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Message Sent | MegaForce</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dst/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <style>
            body{
                margin: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #050714;
                color: #ffffff;
                font-family: arial, sans-serif;
            }
            .success-card{
                width: 90%;
                max-width: 600px;
                background: #0B1024;
                border: 1px solid #1e3a52;
                border-radius: 20px;
                padding: 50px 35px;
                text-align: center;
                box-shadow: 0 10px 35px rgba(0, 0, 0, 0.45);
            }
            .success-icon{
                width: 90px;
                height: 90px;
                background: #00FF38;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0 auto 25px;
                color: #050714;
                font-size: 40px;
            }
            h2{
                font-weight: 700;
                margin-bottom: 15px;
            }
            p{
                color: #b8c4d1;
                line-height: 1.7;
                border: none;
            }
            .btn-success {
                margin-top: 15px;
                background: #00b82e;
                border: none;
            }
            .btn-success:hover{
                background: #00ff38;
                color: #050714;
            }
        </style>
    </head>
    <body>
        <div class="success-card">
            <div class="success-icon">
                <i class="fa-solid fa-check"></i>
            </div>
            <h2>Message Sent!</h2>
            <p>
                Thank you,
                <strong style="color:#00ff38;">
                    <?php echo htmlspecialchars($fullname); ?>
                </strong>.
                <br><br>
                Your message has been successfully received.
                Our team will review it and get back to you if necessary.
            </p>
            <a href="index.php#contact" class="btn btn-success">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Back to Contact
            </a>
        </div>
    </body>
</html>