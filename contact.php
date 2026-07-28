<?php
$pageTitle = "Services";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<section class="bg-dark text-white text-center py-5">
    <div class="container">
        <h1 class="fw-bold">Contact Us</h1>
        <p>We're here to help you 24/7</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row">

        <!-- Contact Information -->
         <div class="col-lg-5 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <h3 class="mb-4">Get in Touch</h3>
                    <p>
                        <i class="fa-solid fa-location-dot text-warning"></i>
                        <strong>Address:</strong><br>
                        4 Albany Street, Quezon City, Philippines
                    </p>
                    <p>
                        <i class="fa-solid fa-envelope text-warning"></i>
                        <strong>Email:</strong><br>
                        megaforce@gmail.com
                    </p>
                     <p>
                        <i class="fa-solid fa-clock text-warning"></i>
                        <strong>Office Hours:</strong><br>
                        Monday - friday <br>
                        8:00 AM - 4:00 PM
                    </p>
                </div>
            </div>
         </div>


         <!-- Contact Form -->
            <div class="col-lg-7">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h3 class="mb-4">Send Us a Message</h3>
                    
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea  class="form-control" rows="5"></textarea>
                        </div>
                            <button class="btn btn-warning">
                                Send Message
                            </button>
                    </form>
                </div>
            </div>

            </div>

        </div>
    </div>
</section>

<!-- Google Map Placeholder -->
 <section class="pb-5">
    <div class="container">
        <h3 class="text-center mb-4">Our Location</h3>
        <div class="ratio ratio-16x9 shadow">

        <iframe src="https://www.google.com/maps?q=Quezon+City&output=embed" loading="lazy" allowfullscreen></iframe>
        </div>
    </div>
 </section>
 <?php include 'includes/footer.php'; ?>