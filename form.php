<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $image = $_FILES['image']['tmp_name'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $phoneno = $_POST['phoneno'] ?? '';
    $summary = $_POST['summary'] ?? '';
    $achieve_title = $_POST['achieve_title'] ?? '';
    $achieve_description = $_POST['achieve_description'] ?? '';
    $exp_title = $_POST['exp_title'] ?? '';
    $exp_organization = $_POST['exp_organization'] ?? '';
    $exp_location = $_POST['exp_location'] ?? '';
    $exp_start_date = $_POST['exp_start_date'] ?? '';
    $exp_end_date = $_POST['exp_end_date'] ?? '';
    $exp_description = $_POST['exp_description'] ?? '';
    $edu_institution = $_POST['edu_institution'] ?? '';
    $edu_degree = $_POST['edu_degree'] ?? '';
    $edu_start_date = $_POST['edu_start_date'] ?? '';
    $edu_end_date = $_POST['edu_end_date'] ?? '';
    $edu_location = $_POST['edu_location'] ?? '';

    require('fpdf186/fpdf.php');
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFillColor(0, 102, 204);

    // Add header
    $pdf->SetY(10);
    $pdf->SetX(10);
    $pdf->SetFillColor(0, 102, 204); // Dark Blue Header Background
    $pdf->Rect(10, 10, 190, 20, 'F'); // Draw header background
    $pdf->SetTextColor(255, 255, 255); // White text
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, "Curriculum Vitae", 0, 1, 'C', true);
    $pdf->SetTextColor(0, 0, 0); // Reset text color
    $pdf->Ln(10); // Add some space after header

    // Personal Details
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(0, 10, "Personal Details", 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Name: " . $firstname . " " . $middlename . " " . $lastname, 0, 1);
    $pdf->Cell(0, 10, "Designation: " . $designation, 0, 1);
    $pdf->Cell(0, 10, "Address: " . $address, 0, 1);
    $pdf->Cell(0, 10, "Email: " . $email, 0, 1);
    $pdf->Cell(0, 10, "Phone No: " . $phoneno, 0, 1);
    $pdf->Ln(5); // Add space before next section

    // Add separator line
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(5); // Space after separator

    // Summary
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(0, 10, "Summary", 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, $summary);
    $pdf->Ln(5);

    // Achievements
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(0, 10, "Achievements", 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Title: " . $achieve_title, 0, 1);
    $pdf->MultiCell(0, 10, "Description: " . $achieve_description);
    $pdf->Ln(5);

    // Experience (use table format)
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(0, 10, "Experience", 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Job Title: " . $exp_title, 0, 1);
    $pdf->Cell(0, 10, "Company: " . $exp_organization, 0, 1);
    $pdf->Cell(0, 10, "Location: " . $exp_location, 0, 1);
    $pdf->Cell(0, 10, "Duration: " . $exp_start_date . " - " . $exp_end_date, 0, 1);
    $pdf->MultiCell(0, 10, "Description: " . $exp_description);
    $pdf->Ln(5);

    // Education (use table format)
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(0, 10, "Education", 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "School: " . $edu_institution, 0, 1);
    $pdf->Cell(0, 10, "Degree: " . $edu_degree, 0, 1);
    $pdf->Cell(0, 10, "City: " . $edu_location, 0, 1);
    $pdf->Cell(0, 10, "Duration: " . $edu_start_date . " - " . $edu_end_date, 0, 1);
    $pdf->Ln(5);

    // Image Handling (Optional: Embed image in PDF)
    if (!empty($image) && is_uploaded_file($image)) {
        $image_path = "uploads/images/" . basename($_FILES['image']['name']);
        if (move_uploaded_file($image, $image_path)) {
            $pdf->Image($image_path, 150, 20, 50); // Adjust position and size as needed
        } else {
            echo "Image upload failed.";
            exit();
        }
    }

    // Save PDF
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $filePath = $uploadDir . 'resume_' . $user_id . '_' . time() . '.pdf';
    $pdf->Output($filePath, 'F');

    // Save file path to the database
    $stmt = $conn->prepare("INSERT INTO user_cv (user_id, cv_path) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $filePath);
    if ($stmt->execute()) {
        echo "Resume saved successfully!";
    } else {
        echo "Error saving CV to database: " . $stmt->error;
    }
}
?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar bg-white">
        <div class="container">
            <a href="index.php" class="navbar-brand">
                <img src="img/CV.png" alt="Brand Icon" class="navbar-brand-icon" style="width: 50px; height: auto;">
            </a>
        </div>
    </nav>

    <section class="section">
        <div class="container">
            <form action="form.php" method="post" enctype="multipart/form-data" class="cv-form" id="cv-form">
                <div class="cv-form-blk">
                    <div class="cv-form-row-title">
                        <h3>Personal Details</h3>
                    </div>
                    <div class="cv-form-row">
                        <div class="form-elem">
                            <label for="firstname" class="form-label">First Name</label>
                            <input name="firstname" type="text" class="form-control" id="firstname" placeholder="e.g. John" required>
                        </div>
                        <div class="form-elem">
                            <label for="middlename" class="form-label">Middle Name (Optional)</label>
                            <input name="middlename" type="text" class="form-control" id="middlename" placeholder="e.g. A." optional>
                        </div>
                        <div class="form-elem">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input name="lastname" type="text" class="form-control" id="lastname" placeholder="e.g. Doe" required>
                        </div>
                        <div class="form-elem">
                            <label for="image" class="form-label">Profile Image (Optional)</label>
                            <input name="image" type="file" class="form-control" id="image">
                        </div>
                        <div class="form-elem">
                            <label for="designation" class="form-label">Designation</label>
                            <input name="designation" type="text" class="form-control" id="designation" placeholder="e.g. Software Engineer" required>
                        </div>
                        <div class="form-elem">
                            <label for="address" class="form-label">Address</label>
                            <input name="address" type="text" class="form-control" id="address" placeholder="e.g. 123 Main St, City" required>
                        </div>
                        <div class="form-elem">
                            <label for="email" class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" id="email" placeholder="e.g. john.doe@example.com" required>
                        </div>
                        <div class="form-elem">
                            <label for="phoneno" class="form-label">Phone Number</label>
                            <input name="phoneno" type="text" class="form-control" id="phoneno" placeholder="e.g. (123) 456-7890" required>
                        </div>
                    </div>
                </div>

                <div class="cv-form-blk">
                    <div class="cv-form-row-title">
                        <h3>Summary</h3>
                    </div>
                    <div class="cv-form-row">
                        <div class="form-elem">
                            <label for="summary" class="form-label">Summary</label>
                            <textarea name="summary" class="form-control" id="summary" placeholder="A brief summary about yourself" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="cv-form-blk">
                    <div class="cv-form-row-title">
                        <h3>Achievements</h3>
                    </div>
                    <div class="cv-form-row">
                        <div class="form-elem">
                            <label for="achieve_title" class="form-label">Achievement Title</label>
                            <input name="achieve_title" type="text" class="form-control" id="achieve_title" placeholder="e.g. Award for Excellence" required>
                        </div>
                        <div class="form-elem">
                            <label for="achieve_description" class="form-label">Achievement Description</label>
                            <textarea name="achieve_description" class="form-control" id="achieve_description" placeholder="Description of the achievement" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="cv-form-blk">
                    <div class="cv-form-row-title">
                        <h3>Experience</h3>
                    </div>
                    <div class="cv-form-row" id="experience-container">
                        <div class="form-elem">
                            <label for="exp_title" class="form-label">Job Title</label>
                            <input name="exp_title" type="text" class="form-control" id="exp_title" placeholder="e.g. Senior Developer" required>
                        </div>
                        <div class="form-elem">
                            <label for="exp_organization" class="form-label">Organization</label>
                            <input name="exp_organization" type="text" class="form-control" id="exp_organization" placeholder="e.g. Tech Solutions" required>
                        </div>
                        <div class="form-elem">
                            <label for="exp_location" class="form-label">Location</label>
                            <input name="exp_location" type="text" class="form-control" id="exp_location" placeholder="e.g. New York, NY" required>
                        </div>
                        <div class="form-elem">
                            <label for="exp_start_date" class="form-label">Start Date</label>
                            <input name="exp_start_date" type="date" class="form-control" id="exp_start_date" required>
                        </div>
                        <div class="form-elem">
                            <label for="exp_end_date" class="form-label">End Date</label>
                            <input name="exp_end_date" type="date" class="form-control" id="exp_end_date" required>
                        </div>
                        <div class="form-elem">
                            <label for="exp_description" class="form-label">Job Description</label>
                            <textarea name="exp_description" class="form-control" id="exp_description" placeholder="Description of your responsibilities and achievements" required></textarea>
                        </div>
                    </div>
                    
                </div>

                <div class="cv-form-blk">
                    <div class="cv-form-row-title">
                        <h3>Education</h3>
                    </div>
                    <div class="cv-form-row" id="education-container">
                        <div class="form-elem">
                            <label for="edu_institution" class="form-label">Institution</label>
                            <input name="edu_institution" type="text" class="form-control" id="edu_institution" placeholder="e.g. University of Example" required>
                        </div>
                        <div class="form-elem">
                            <label for="edu_degree" class="form-label">Degree</label>
                            <input name="edu_degree" type="text" class="form-control" id="edu_degree" placeholder="e.g. B.Sc. Computer Science" required>
                        </div>
                        <div class="form-elem">
                            <label for="edu_start_date" class="form-label">Start Date</label>
                            <input name="edu_start_date" type="date" class="form-control" id="edu_start_date" required>
                        </div>
                        <div class="form-elem">
                            <label for="edu_end_date" class="form-label">End Date</label>
                            <input name="edu_end_date" type="date" class="form-control" id="edu_end_date" required>
                        </div>
                        <div class="form-elem">
                            <label for="edu_location" class="form-label">Location</label>
                            <input name="edu_location" type="text" class="form-control" id="edu_location" placeholder="e.g. Boston, MA" required>
                        </div>
                    </div>
                  
                </div>

                <div class="cv-form-blk">
                    <div class="cv-form-row-title">
                        <h3>Projects</h3>
                    </div>
                    <div class="cv-form-row" id="projects-container">
                        <div class="form-elem">
                            <label for="projects" class="form-label">Projects</label>
                            <textarea name="projects" class="form-control" id="projects" placeholder="List your projects here" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>
