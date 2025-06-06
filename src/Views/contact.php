<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Challengify | Daily Creative Challenges & Activities</title>

    <!-- Robots -->
    <meta name="robots" content="index, follow">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/images/chellengify-logo.png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="/assets/css/vendor/tailwind.min.css">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-700 to-indigo-800 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center transition transform hover:scale-105">
                        <img class="h-10 w-10 mr-2 rounded-full shadow-sm" src="/assets/images/chellengify-logo.png" alt="Challengify Logo">
                        <span class="text-white font-bold text-xl">Challengify</span>
                    </a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="/" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-600 hover:bg-opacity-50 transition duration-300">Home</a>
                        <a href="/challenges" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-600 hover:bg-opacity-50 transition duration-300">Challenges</a>
                        <a href="/about" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-600 hover:bg-opacity-50 transition duration-300">About</a>
                        <a href="/contact" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-600 hover:bg-opacity-50 transition duration-300">Contact</a>
                    </div>
                </div>

                <?php
                // Check if user is logged in
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                if (isset($_SESSION['user_id'])):
                ?>
                    <!-- User profile dropdown for logged in users -->
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="relative">
                            <button type="button" class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true" onclick="document.getElementById('user-dropdown').classList.toggle('hidden')">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                </div>
                                <span class="ml-2 text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                <svg class="ml-1 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <a href="/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Dashboard</a>
                                <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                                <div class="border-t border-gray-100"></div>
                                <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Logout</a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Login/Register buttons for desktop -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="/login" class="text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-600 hover:bg-opacity-50 transition duration-300 border border-white">Login</a>
                        <a href="/register" class="text-indigo-900 bg-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-100 transition duration-300 shadow-md">Register</a>
                    </div>
                <?php endif; ?>

                <div class="md:hidden">
                    <!-- Mobile menu button -->
                    <button type="button" class="text-white hover:text-blue-200 focus:outline-none focus:text-blue-200" aria-label="Open menu" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-blue-800 rounded-b-lg">
                <a href="/" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300">Home</a>
                <a href="/challenges" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300">Challenges</a>
                <a href="/about" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300">About</a>
                <a href="/contact" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300">Contact</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- User profile for mobile when logged in -->
                    <div class="flex items-center px-3 py-2">
                        <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </div>
                        <span class="ml-2 text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </div>
                    <a href="/dashboard" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300">Dashboard</a>
                    <a href="/settings" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300">Settings</a>
                    <a href="/logout" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300">Logout</a>
                <?php else: ?>
                    <!-- Login/Register for mobile -->
                    <div class="flex space-x-2 mt-3 pb-1">
                        <a href="/login" class="flex-1 text-center text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300 border border-white">Login</a>
                        <a href="/register" class="flex-1 text-center text-indigo-900 bg-white px-3 py-2 rounded-md text-base font-medium hover:bg-gray-100 transition duration-300">Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Contact Section -->
    <section class="bg-gray-50 py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4" data-aos="fade-up">Get in Touch</h1>
                <p class="text-lg text-gray-600" data-aos="fade-up" data-aos-delay="100">Have a question or feedback? We'd love to hear from you.</p>
            </div>

            <?php
            // Start session if it hasn't been started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            // Display success message if set
            if (isset($_SESSION['contact_success']) && $_SESSION['contact_success']) {
                echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert" data-aos="fade-up" data-aos-delay="150">';
                echo '<strong class="font-bold">Success! </strong>';
                echo '<span class="block sm:inline">' . htmlspecialchars($_SESSION['contact_message']) . '</span>';
                echo '<button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display=\'none\';">';
                echo '<span class="sr-only">Close</span>';
                echo '<svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>';
                echo '</button>';
                echo '</div>';
                
                // Clear the session variables
                unset($_SESSION['contact_success']);
                unset($_SESSION['contact_message']);
            }
            
            // Get any stored form data for redisplay
            $formData = $_SESSION['contact_form_data'] ?? [];
            $errors = $_SESSION['contact_errors'] ?? [];
            
            // Clear stored form data and errors
            unset($_SESSION['contact_form_data']);
            unset($_SESSION['contact_errors']);
            
            // Generate a CSRF token if not already set
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            ?>

            <div class="bg-white rounded-lg shadow-lg p-8" data-aos="fade-up" data-aos-delay="200">
                <form action="/contact/submit" method="POST" class="space-y-6" novalidate>
                    <!-- CSRF Protection -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <!-- Honeypot field (hidden, for anti-spam) -->
                    <div class="hidden">
                        <label for="website">Website</label>
                        <input type="text" name="website" id="website" autocomplete="off">
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="name" id="name" required 
                                class="pl-10 mt-1 block w-full rounded-md <?php echo isset($errors['name']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500'; ?> shadow-sm input-focus-effect"
                                value="<?php echo htmlspecialchars($formData['name'] ?? ''); ?>">
                        </div>
                        <?php if (isset($errors['name'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo htmlspecialchars($errors['name']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" name="email" id="email" required
                                class="pl-10 mt-1 block w-full rounded-md <?php echo isset($errors['email']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500'; ?> shadow-sm input-focus-effect"
                                value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                        </div>
                        <?php if (isset($errors['email'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo htmlspecialchars($errors['email']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                            <input type="text" name="subject" id="subject" required
                                class="pl-10 mt-1 block w-full rounded-md <?php echo isset($errors['subject']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500'; ?> shadow-sm input-focus-effect"
                                value="<?php echo htmlspecialchars($formData['subject'] ?? ''); ?>">
                        </div>
                        <?php if (isset($errors['subject'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo htmlspecialchars($errors['subject']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <textarea name="message" id="message" rows="6" required
                                class="mt-1 block w-full rounded-md <?php echo isset($errors['message']) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500'; ?> shadow-sm input-focus-effect"><?php echo htmlspecialchars($formData['message'] ?? ''); ?></textarea>
                        </div>
                        <?php if (isset($errors['message'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo htmlspecialchars($errors['message']); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Add reCAPTCHA here if needed -->
                    <!-- <div class="flex justify-center my-4">
                        <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>
                        <?php if (isset($errors['recaptcha'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo htmlspecialchars($errors['recaptcha']); ?></p>
                        <?php endif; ?>
                    </div> -->

                    <div class="flex justify-center">
                        <button type="submit" 
                            class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 transition duration-300 shadow-md relative overflow-hidden group">
                            <span class="relative z-10">Send Message</span>
                            <div class="absolute inset-0 bg-blue-700 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                        </button>
                    </div>
                    
                    <div class="text-center text-sm text-gray-500 mt-4">
                        <p>We respect your privacy and will never share your information.</p>
                    </div>
                </form>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 text-center" data-aos="fade-up" data-aos-delay="300">
                <div>
                    <div class="flex justify-center mb-4">
                        <i class="fas fa-map-marker-alt text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Address</h3>
                    <p class="mt-2 text-gray-600">123 Innovation Street<br>Tech Valley, CA 94043</p>
                </div>

                <div>
                    <div class="flex justify-center mb-4">
                        <i class="fas fa-envelope text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Email</h3>
                    <p class="mt-2 text-gray-600">contact@challengify.com<br>support@challengify.com</p>
                </div>

                <div>
                    <div class="flex justify-center mb-4">
                        <i class="fas fa-phone text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Phone</h3>
                    <p class="mt-2 text-gray-600">+1 (555) 123-4567<br>+1 (555) 987-6543</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <img class="h-10 w-10 mr-2 rounded-full" src="/assets/images/chellengify-logo.png" alt="Challengify Logo">
                        <span class="font-bold text-xl">Challengify</span>
                    </div>
                    <p class="text-gray-400">Your daily dose of creative micro-challenges.</p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Explore</h3>
                    <ul class="space-y-2">
                        <li><a href="/challenges" class="text-gray-400 hover:text-white transition">Today's Challenges</a></li>
                        <li><a href="/gallery" class="text-gray-400 hover:text-white transition">User Gallery</a></li>
                        <li><a href="/leaderboard" class="text-gray-400 hover:text-white transition">Leaderboard</a></li>
                        <li><a href="/themes" class="text-gray-400 hover:text-white transition">Themed Weeks</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Categories</h3>
                    <ul class="space-y-2">
                        <li><a href="/category/writing" class="text-gray-400 hover:text-white transition">Creative Writing</a></li>
                        <li><a href="/category/photography" class="text-gray-400 hover:text-white transition">Mobile Photography</a></li>
                        <li><a href="/category/diy" class="text-gray-400 hover:text-white transition">DIY & Crafts</a></li>
                        <li><a href="/category/health" class="text-gray-400 hover:text-white transition">Healthy Habits</a></li>
                        <li><a href="/category/skills" class="text-gray-400 hover:text-white transition">Practical Skills</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Company</h3>
                    <ul class="space-y-2">
                        <li><a href="/about" class="text-gray-400 hover:text-white transition">About Us</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-white transition">Contact</a></li>
                        <li><a href="/terms" class="text-gray-400 hover:text-white transition">Terms of Service</a></li>
                        <li><a href="/privacy" class="text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Challengify. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Debug Hand -->
    <?php include_once __DIR__ . '/../Components/DebugHand.php'; ?>

    <!-- JavaScript -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS animation library
        AOS.init({
            once: true,
            offset: 100,
            duration: 800
        });
    </script>
    
    <!-- Add validation script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                let hasErrors = false;
                
                // Basic client-side validation
                const name = document.getElementById('name');
                const email = document.getElementById('email');
                const subject = document.getElementById('subject');
                const message = document.getElementById('message');
                
                // Reset previous error states
                [name, email, subject, message].forEach(field => {
                    field.classList.remove('border-red-500');
                    const errorEl = field.parentElement.parentElement.querySelector('.text-red-600');
                    if (errorEl) errorEl.remove();
                });
                
                // Validate name
                if (!name.value || name.value.length < 2) {
                    addError(name, 'Please enter a valid name (at least 2 characters)');
                    hasErrors = true;
                }
                
                // Validate email
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!email.value || !emailPattern.test(email.value)) {
                    addError(email, 'Please enter a valid email address');
                    hasErrors = true;
                }
                
                // Validate subject
                if (!subject.value || subject.value.length < 3) {
                    addError(subject, 'Please enter a valid subject (at least 3 characters)');
                    hasErrors = true;
                }
                
                // Validate message
                if (!message.value || message.value.length < 10) {
                    addError(message, 'Please enter a message (at least 10 characters)');
                    hasErrors = true;
                }
                
                if (hasErrors) {
                    event.preventDefault();
                }
            });
            
            function addError(field, message) {
                field.classList.add('border-red-500');
                const errorEl = document.createElement('p');
                errorEl.className = 'mt-1 text-sm text-red-600';
                errorEl.textContent = message;
                field.parentElement.parentElement.appendChild(errorEl);
            }
            
            // Auto-close alerts after 5 seconds
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 1s';
                    setTimeout(() => alert.style.display = 'none', 1000);
                }, 5000);
            });
        });
    </script>
</body>

</html>