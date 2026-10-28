<?php
session_start();
require_once(__DIR__ . '/config/config.php');


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Get lecture ID from URL
$lecture_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// In a real app, you would fetch lecture details from database
// For now, we'll use sample data
$lecture = [
    'id' => $lecture_id,
    'title' => 'Introduction to Algebra',
    'teacher' => 'Mr. Johnson',
    'subject' => 'Mathematics',
    'description' => 'This lecture covers the fundamentals of algebra including variables, expressions, equations, and basic problem-solving techniques. Perfect for beginners starting their algebra journey.',
    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
    'duration' => '45 minutes',
    'date' => 'March 15, 2024',
    'views' => 150,
    'watched' => 75,
    'notes' => 'Algebra is a branch of mathematics dealing with symbols and the rules for manipulating those symbols. In elementary algebra, those symbols represent quantities without fixed values, known as variables.'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lecture['title']; ?> - Lecture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .video-container {
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .lecture-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
        }
        .related-lecture {
            transition: transform 0.2s;
        }
        .related-lecture:hover {
            transform: translateX(5px);
        }
        .note-card {
            border-left: 4px solid #0d6efd;
        }
        .action-btn {
            transition: all 0.3s;
        }
        .action-btn:hover {
            transform: scale(1.05);
        }
        .progress-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            color: white;
            background: conic-gradient(#0d6efd <?php echo $lecture['watched'] * 3.6; ?>deg, #e9ecef 0deg);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include_once 'includes/header.php'; ?>

    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="student_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="primary8.php">Primary 8</a></li>
                <li class="breadcrumb-item"><a href="lectures.php">Lectures</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo substr($lecture['title'], 0, 30); ?>...</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Video Player -->
                <div class="video-container">
                    <div class="ratio ratio-16x9">
                        <iframe src="<?php echo $lecture['video_url']; ?>" 
                                title="<?php echo $lecture['title']; ?>"
                                allowfullscreen></iframe>
                    </div>
                </div>

                <!-- Lecture Info -->
                <div class="lecture-info mb-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 class="h2 mb-2"><?php echo $lecture['title']; ?></h1>
                            <div class="mb-3">
                                <span class="badge bg-primary me-2"><?php echo $lecture['subject']; ?></span>
                                <span class="badge bg-secondary me-2">
                                    <i class="fas fa-clock"></i> <?php echo $lecture['duration']; ?>
                                </span>
                                <span class="badge bg-success">
                                    <i class="fas fa-eye"></i> <?php echo $lecture['views']; ?> views
                                </span>
                            </div>
                            <p class="mb-0"><?php echo $lecture['description']; ?></p>
                        </div>
                        <div class="text-center">
                            <div class="progress-circle mb-2">
                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 70px; height: 70px;">
                                    <span class="text-primary fw-bold"><?php echo $lecture['watched']; ?>%</span>
                                </div>
                            </div>
                            <small>Completed</small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-2">
                        <button class="btn btn-outline-primary w-100 action-btn">
                            <i class="fas fa-thumbs-up"></i> Like
                        </button>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button class="btn btn-outline-success w-100 action-btn">
                            <i class="fas fa-download"></i> Notes
                        </button>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button class="btn btn-outline-info w-100 action-btn">
                            <i class="fas fa-share"></i> Share
                        </button>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button class="btn btn-outline-warning w-100 action-btn">
                            <i class="fas fa-flag"></i> Report
                        </button>
                    </div>
                </div>

                <!-- Lecture Notes -->
                <div class="card note-card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="fas fa-file-alt"></i> Lecture Notes
                        </h4>
                        <p><?php echo $lecture['notes']; ?></p>
                        <h5>Key Topics Covered:</h5>
                        <ul>
                            <li>Introduction to Variables and Constants</li>
                            <li>Algebraic Expressions and Terms</li>
                            <li>Solving Basic Equations</li>
                            <li>Word Problems in Algebra</li>
                            <li>Practice Exercises</li>
                        </ul>
                        <h5>Homework Assignment:</h5>
                        <p>Complete exercises 1-10 from Chapter 5 of your textbook. Submit by Friday.</p>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="fas fa-comments"></i> Discussion & Questions
                        </h4>
                        <div class="mb-3">
                            <textarea class="form-control" rows="3" placeholder="Ask a question or add a comment..."></textarea>
                            <button class="btn btn-primary mt-2">Post Comment</button>
                        </div>
                        
                        <div class="mt-4">
                            <div class="d-flex mb-3">
                                <img src="https://ui-avatars.com/api/?name=John+Doe&background=random&size=40" 
                                     class="rounded-circle me-3" alt="User">
                                <div>
                                    <h6 class="mb-1">John Doe <small class="text-muted">- 2 hours ago</small></h6>
                                    <p>Great explanation! Can you explain more about solving equations with fractions?</p>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-3">
                                <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=random&size=40" 
                                     class="rounded-circle me-3" alt="User">
                                <div>
                                    <h6 class="mb-1">Jane Smith <small class="text-muted">- 1 day ago</small></h6>
                                    <p>The examples were very helpful. Thank you!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Teacher Info -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="https://ui-avatars.com/api/?name=John+Johnson&background=0D8ABC&color=fff&size=100" 
                             class="rounded-circle mb-3" alt="Teacher">
                        <h5 class="card-title"><?php echo $lecture['teacher']; ?></h5>
                        <p class="text-muted">Mathematics Teacher</p>
                        <p class="card-text">Specializes in Algebra and Geometry with 10+ years of teaching experience.</p>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope"></i> Contact Teacher
                        </button>
                    </div>
                </div>

                <!-- Related Lectures -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-play-circle"></i> Related Lectures
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action related-lecture">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Algebra: Advanced Equations</h6>
                                    <small class="text-muted">35 min</small>
                                </div>
                                <small class="text-muted">Mr. Johnson</small>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action related-lecture">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Geometry Basics</h6>
                                    <small class="text-muted">40 min</small>
                                </div>
                                <small class="text-muted">Mr. Johnson</small>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action related-lecture">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Word Problems in Algebra</h6>
                                    <small class="text-muted">30 min</small>
                                </div>
                                <small class="text-muted">Mr. Johnson</small>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action related-lecture">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Fractions in Algebra</h6>
                                    <small class="text-muted">38 min</small>
                                </div>
                                <small class="text-muted">Mr. Johnson</small>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Download Materials -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-download"></i> Download Materials
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-pdf text-danger"></i> Lecture Slides
                                    <small class="d-block text-muted">PDF - 2.5 MB</small>
                                </div>
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-word text-primary"></i> Practice Problems
                                    <small class="d-block text-muted">DOC - 1.8 MB</small>
                                </div>
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-excel text-success"></i> Worksheet
                                    <small class="d-block text-muted">XLS - 3.2 MB</small>
                                </div>
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update watch progress
        let watchedPercentage = <?php echo $lecture['watched']; ?>;
        const video = document.querySelector('iframe');
        
        // Simulate watching progress (in real app, this would track actual video time)
        setInterval(() => {
            if (watchedPercentage < 100) {
                watchedPercentage += 5;
                updateProgressCircle(watchedPercentage);
            }
        }, 30000); // Every 30 seconds
        
        function updateProgressCircle(percentage) {
            const circle = document.querySelector('.progress-circle');
            const text = circle.querySelector('span');
            circle.style.background = `conic-gradient(#0d6efd ${percentage * 3.6}deg, #e9ecef 0deg)`;
            text.textContent = percentage + '%';
        }
        
        // Like button functionality
        document.querySelector('.btn-outline-primary').addEventListener('click', function() {
            this.classList.toggle('btn-primary');
            this.classList.toggle('btn-outline-primary');
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-thumbs-up')) {
                icon.classList.replace('fa-thumbs-up', 'fa-thumbs-up');
            }
        });
    </script>
</body>
</html>