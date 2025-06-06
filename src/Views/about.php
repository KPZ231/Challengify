<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


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

    <title>About Challengify | Daily Creative Challenges & Activities</title>
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

    <!-- About Section -->
    <main class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">About Challengify</h1>
                <p class="text-xl text-gray-600">Your daily dose of creative micro-challenges</p>
            </div>

            <!-- What is Challengify -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
                <h2 class="text-3xl font-semibold text-gray-900 mb-6">What is Challengify?</h2>
                <p class="text-gray-600 mb-6">Challengify is a platform where short, creative "micro-challenges" are published daily (or at user-selected intervals) across various categories. Each day, one micro-challenge appears in each category. Users have 24 hours to complete the task and submit their response (photo, description, short video, or link). After the time is up, the community votes for the most interesting entries, awarding badges and reputation points to the authors.</p>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Challenge Categories</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-indigo-600 mb-2">Creative Writing</h4>
                        <p class="text-gray-600">Write a 100-word story fragment</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-indigo-600 mb-2">Mobile Photography</h4>
                        <p class="text-gray-600">Take themed photos: shadows, reflections, and more</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-indigo-600 mb-2">DIY & Crafts</h4>
                        <p class="text-gray-600">Create simple items from recycled materials</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-indigo-600 mb-2">Healthy Habits</h4>
                        <p class="text-gray-600">Daily wellness activities and healthy living challenges</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h4 class="font-semibold text-indigo-600 mb-2">Practical Skills</h4>
                        <p class="text-gray-600">Learn new phrases, skills, and practical knowledge</p>
                    </div>
                </div>
            </div>

            <!-- Why Challengify -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-semibold text-gray-900 mb-6">Why Choose Challengify?</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-xl font-semibold text-indigo-600 mb-4">A Daily Dose of Inspiration</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Simple but creative ideas for small actions</li>
                            <li>Short challenges taking only 15-30 minutes</li>
                            <li>Perfect for students, working people, and parents</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-semibold text-indigo-600 mb-4">Community and Gamification</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Points and badges system</li>
                            <li>Weekly championships</li>
                            <li>Interactive community voting and comments</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-semibold text-indigo-600 mb-4">Variety of Categories</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Daily changing topics</li>
                            <li>Custom themed weeks</li>
                            <li>Community-proposed challenges</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-semibold text-indigo-600 mb-4">User-generated Content</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Content created by the community</li>
                            <li>Share your achievements</li>
                            <li>Community-driven moderation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>


                    
    
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
</body>

</html>