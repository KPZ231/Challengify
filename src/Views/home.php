<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challengify - Daily Micro-Challenges, Creativity, Wellness & Skills</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS - Production Version -->
    <link href="/assets/css/vendor/tailwind.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-trophy me-2"></i>Challengify
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#categories">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="#community">Community</a></li>
                    <li class="nav-item ms-2"><a class="btn btn-light text-primary fw-bold" href="/login">Login</a></li>
                    <li class="nav-item ms-2"><a class="btn btn-outline-light fw-bold" href="/register">Sign Up</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-primary text-white text-center py-5">
        <div class="container px-4">
            <div class="row gx-5 align-items-center">
                <div class="col-lg-6 text-lg-start">
                    <h1 class="display-4 fw-bold mb-3">Daily Micro-Challenges to Boost Your Creativity</h1>
                    <p class="lead mb-4">Engage in fun, quick activities that spark creativity, build healthy habits, and develop new skills - all in just 15-30 minutes a day!</p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-start">
                        <a href="/register" class="btn btn-light btn-lg px-4 fw-bold text-primary">Get Started</a>
                        <a href="#how-it-works" class="btn btn-outline-light btn-lg px-4">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="/public/assets/images/hero-image.svg" class="img-fluid" alt="Challengify - Daily Challenges">
                </div>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-bold mb-4">What is Challengify?</h2>
                    <p class="lead mb-4">Challengify is a platform that publishes short, creative challenges ("micro-challenges") daily or at intervals you choose in various categories.</p>
                    <p class="mb-4">Each day, you'll find one new micro-challenge in each category. You have 24 hours to complete the task and submit your response - whether it's a photo, description, short video, or link. After time's up, the community votes on the most interesting submissions, awarding badges and reputation points to creators.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Challenge Categories</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                                <i class="fas fa-pen-fancy"></i>
                            </div>
                            <h3 class="fw-bold">Creative Writing</h3>
                            <p class="text-muted">Write a story fragment in 100 words, create a haiku, or describe an emotion without naming it.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                                <i class="fas fa-camera"></i>
                            </div>
                            <h3 class="fw-bold">Mobile Photography</h3>
                            <p class="text-muted">Capture thematic photos: shadows, water reflections, or unexpected beauty in everyday objects.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                                <i class="fas fa-paint-brush"></i>
                            </div>
                            <h3 class="fw-bold">DIY & Crafts</h3>
                            <p class="text-muted">Create simple objects from recycled materials, try a new crafting technique, or upcycle something old.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3 class="fw-bold">Daily Healthy Habit</h3>
                            <p class="text-muted">Try 5 minutes of meditation, prepare a new healthy snack, or test a quick workout routine.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-3">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h3 class="fw-bold">Practical Skills</h3>
                            <p class="text-muted">Learn 3 phrases in a foreign language, make your own cosmetic, or master a new digital tool.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">How It Works</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-primary rounded-circle text-white d-inline-flex justify-content-center align-items-center mb-3" style="width: 60px; height: 60px;">
                            <h3 class="m-0">1</h3>
                        </div>
                        <h4>Choose a Challenge</h4>
                        <p class="text-muted">Browse daily challenges across different categories</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-primary rounded-circle text-white d-inline-flex justify-content-center align-items-center mb-3" style="width: 60px; height: 60px;">
                            <h3 class="m-0">2</h3>
                        </div>
                        <h4>Complete It</h4>
                        <p class="text-muted">Take 15-30 minutes to complete the micro-challenge</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-primary rounded-circle text-white d-inline-flex justify-content-center align-items-center mb-3" style="width: 60px; height: 60px;">
                            <h3 class="m-0">3</h3>
                        </div>
                        <h4>Share Your Result</h4>
                        <p class="text-muted">Upload your photo, text, or video response</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-primary rounded-circle text-white d-inline-flex justify-content-center align-items-center mb-3" style="width: 60px; height: 60px;">
                            <h3 class="m-0">4</h3>
                        </div>
                        <h4>Earn Points</h4>
                        <p class="text-muted">Get votes, earn badges, and build your reputation</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Community Section -->
    <section id="community" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Join Our Creative Community</h2>
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle me-3">
                                <i class="fas fa-award"></i>
                            </div>
                            <h4 class="mb-0">Points & Badges System</h4>
                        </div>
                        <p class="text-muted ps-5">Earn levels, unlock new challenge categories, and compete in weekly championships.</p>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle me-3">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h4 class="mb-0">Comments & Voting</h4>
                        </div>
                        <p class="text-muted ps-5">Interact with others, provide feedback, and vote for your favorite submissions.</p>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle me-3">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <h4 class="mb-0">User Profiles</h4>
                        </div>
                        <p class="text-muted ps-5">Showcase your achievements, track your progress, and build your reputation.</p>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="/public/assets/images/community.svg" class="img-fluid" alt="Challengify Community">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Ready to Challenge Yourself?</h2>
            <p class="lead mb-4">Join thousands of creative individuals who boost their skills and creativity with daily micro-challenges.</p>
            <a href="/register" class="btn btn-light btn-lg px-5 fw-bold text-primary">Join Challengify Today</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold mb-3">Challengify</h5>
                    <p>Daily micro-challenges to boost creativity, build healthy habits, and develop new skills.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">About</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">How It Works</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Testimonials</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Categories</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Creative Writing</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Photography</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">DIY & Crafts</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Healthy Habits</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Practical Skills</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Support</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Help Center</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Contact Us</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="fw-bold mb-3">Get Started</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="/register" class="text-white text-decoration-none">Sign Up</a></li>
                        <li class="mb-2"><a href="/login" class="text-white text-decoration-none">Login</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Today's Challenges</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small mb-0">© 2023 Challengify. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white text-decoration-none small me-3">Privacy</a>
                    <a href="#" class="text-white text-decoration-none small me-3">Terms</a>
                    <a href="#" class="text-white text-decoration-none small">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Script -->
    <script src="/assets/javascript/main.js"></script>

    <!-- Feature Icon Styling -->
    <style>
        .feature-icon {
            width: 50px;
            height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
    </style>
</body>
</html>