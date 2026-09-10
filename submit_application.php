<?php
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: apply.php");
    exit;
}

    $position = trim($_POST['position'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if($position === '' || $fullname === '' || $email === ''){
        die("Please complete all required fields.");
    }

    // Prepare and execute the SQL statement to insert the application data into the database
    $sql ="INSERT INTO job_applications (position, fullname, email, phone, address, message) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        die("Database error: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "ssssss", $position, $fullname, $email, $phone, $address, $message);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Application Submitted</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        <style>
            *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Poppins', sans-serif;
            }
            body{
                min-height: 100vh;
                background: #050714;
                display: flex;
                justify-content: center;
                align-items: center;
                color: #FFFFFF;
            }
            .success-card{
                width: 90%;
                max-width: 520px;
                background: #0B1024;
                border: 1px solid rgba(0, 255, 56, 0.25);
                border-radius: 20px;
                padding: 45px 35px;
                text-align: center;
                box-shadow: 0 10px 35px rgba(0, 0, 0, 0.45);
            }
            .success-icon{
                width: 90px;
                height: 90px;
                background: rgba(0, 255, 56, 0.12);
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0 auto 25px;
                color: #00FF38;
                font-size: 42px;
            }

            .success-card h2{
                font-weight: 700;
                color: #FFFFFF;
                margin-bottom: 15px;
            }
            .success-card .p{
                color: #B8C4D1;
                margin-bottom: 30px;
                line-height: 1.7;
            }
            .btn-success{
                background-color: #00B82E !important;
                border-color: #00B82E !important;
                color: white !important;
                font-weight: 600;
                padding: 12px 25px;
                border-radius: 10px;
                transition: all 0.3s ease;
            }
            .btn-success:hover{
                background-color: #00FF38 !important;
                border-color: #00FF38 !important;
                color: #050714 !important;
            }
            </style>
    </head>

    <body>
        <div class="success-card">
            <div class="success-icon">
                <i class="fa-solid fa-check"></i>
        </div>
        <h2>Application Submitted</h2>
        <p>
            Thank you for applying to MegaForce, <strong style="color: #00FF38;"> 
                <?php echo htmlspecialchars($fullname); ?>
            </strong>.
             <br><br>
             Your application has been successfully received. Our team will review your application.
        </p>
        <a href="index.php#carrers" class="btn btn-success">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Careers</a>
        </div>
    </body>
</html>
<?php
    } else {
        echo "Error submitting application: " . mysqli_stmt_error($stmt);
          mysqli_stmt_close($stmt);
    }
  
?>