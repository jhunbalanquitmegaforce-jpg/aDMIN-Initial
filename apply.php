<?php
$selectedPosition = $_GET['position'] ?? '';

$pageTitle = "Job Application";
include 'includes/header.php';
include 'includes/navbar.php';
?>
 
<section class="py-5" style="background: #050714; min-height:100vh;">
    <div class="container">
        <div class="text-center mb-5">
            <i class="fa-solid fa-user-shield fa-4x" style="color:#00FF38;"></i>
        <h2 class="fw-bold mt-3" style="color: #FFFFFF;">Job Application</h2>
        <p style="color:#B8C4D1;">Apply now and join the MegaForce team.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0"
                style="background: #0B1024; border-radius:20px;">
                <div class="card-body p-5">
                    <form action="submit_application.php" method="POST">
                       <div class="mb-3">
                        <label class="form-label text-white">
                            Position Applying For
                        </label>
                        <select name="position" class="form-select" required>
                            <option value="">Select a position</option>
                            <option value="Security Guard" <?php echo ($selectedPosition === 'Security Guard') ? 'selected' : '';?>>
                                Security Guard
                            </option>
                            <option value="Security Officer" <?php echo ($selectedPosition === 'Security Officer') ? 'selected' : '';?>>
                                Security Officer</option>
                            <option value="Operation Manager" <?php echo ($selectedPosition === 'Operation Manager') ? 'selected' : '';?>>
                                Operation Manager</option>
                        </select>
                       </div>
                       <div class="mb-3">
                        <label class="form-label text-white">
                            Full Name
                        </label>
                        <input type="text" name="fullname" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label text-white">
                            Email Address
                        </label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label text-white">
                            Contact Number
                        </label>
                        <input type="text" name="phone" class="form-control" placeholder="Enter your contact number" required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label text-white">
                            Address
                        </label>
                        <input type="text" name="address" class="form-control" placeholder="Enter your address" required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label text-white">
                            Additional Information
                        </label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Tell us about yourself or your experience" required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                               <i class="fas fa-paper-plane me-2"></i> Submit Application
                            </button>
                        </div>

                    </form>
                </div>

                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>