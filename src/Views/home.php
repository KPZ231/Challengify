<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Challengify: Daily Creative Micro-Challenges for Personal Growth</title>
    
    <!-- Meta Description -->   
    <meta name="description" content="Join Challengify for daily creative micro-challenges that take just 15-30 minutes. Build new habits, earn rewards, and connect with a supportive community!">
    
    <!-- Meta Keywords -->
    <meta name="keywords" content="creative challenges, micro-challenges, daily challenges, personal growth, creative writing, photography, DIY, healthy habits, practical skills">

    <!-- Meta Author -->
    <meta name="author" content="Kpzsproductions">

    <!-- Open Graph -->
    <meta property="og:title" content="Home | Challengify">

    <meta property="og:description" content="Your daily dose of creativity and inspiration with micro-challenges that take just 15-30 minutes to complete.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="/">
    <meta property="og:image" content="/assets/images/chellengify-logo.png">

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


<body class="bg-gray-50">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
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
    
    <!-- Hero Section -->
    <header class="bg-gradient-to-b from-indigo-900 to-blue-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-8 md:mb-0" data-aos="fade-right" data-aos-duration="1000">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">Your Daily Dose of Inspiration</h1>
                    <p class="text-lg md:text-xl text-blue-100 mb-8">Discover creative micro-challenges that take just 15-30 minutes. Complete tasks, earn rewards, and join a community of creative minds!</p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="/challenges" class="bg-white text-indigo-800 hover:bg-gray-100 transition duration-300 font-bold rounded-lg px-6 py-3 text-center shadow-lg transform hover:scale-105">
                            Today's Challenges
                        </a>
                        <a href="/register" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-indigo-800 transition duration-300 font-bold rounded-lg px-6 py-3 text-center transform hover:scale-105">
                            Join Now
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center" data-aos="fade-left" data-aos-duration="1000">
                    <img src="https://placehold.co/600x400/indigo/white?text=Daily+Challenges" alt="Daily Creative Challenges" class="rounded-xl shadow-2xl max-w-full h-auto">
                </div>
            </div>
        </div>
    </header>
    
    <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Challenge Categories</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Explore our diverse range of daily micro-challenges across five exciting categories</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-8">
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-xl transition duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-purple-600 mb-4">
                        <i class="fas fa-pen-fancy text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Creative Writing</h3>
                    <p class="text-gray-600">Write a 100-word story fragment, create a haiku, or craft dialogue between characters.</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-xl transition duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-camera text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Mobile Photography</h3>
                    <p class="text-gray-600">Capture themed photos like shadows, reflections in water, or street scenes with your smartphone.</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-xl transition duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-green-600 mb-4">
                        <i class="fas fa-paint-brush text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">DIY & Crafts</h3>
                    <p class="text-gray-600">Create simple items from recycled materials, try quick arts and crafts projects for your home.</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-xl transition duration-300" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-red-600 mb-4">
                        <i class="fas fa-heart text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Healthy Habits</h3>
                    <p class="text-gray-600">Try 5 minutes of meditation, experiment with a new healthy snack, or quick fitness challenges.</p>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl shadow-md hover:shadow-xl transition duration-300" data-aos="fade-up" data-aos-delay="500">
                    <div class="text-yellow-600 mb-4">
                        <i class="fas fa-brain text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Practical Skills</h3>
                    <p class="text-gray-600">Learn 3 phrases in a foreign language, try a productivity hack, or make your own cosmetic.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- How It Works -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">How Challengify Works</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Simple, fun, and engaging daily challenges to inspire your creativity</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-indigo-600 text-center mb-4">
                        <span class="inline-block w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-center text-gray-900 mb-3">Discover</h3>
                    <p class="text-gray-600 text-center">Each day, find new micro-challenges across five different categories</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-indigo-600 text-center mb-4">
                        <span class="inline-block w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-center text-gray-900 mb-3">Create</h3>
                    <p class="text-gray-600 text-center">Complete the challenge in 15-30 minutes and submit your response</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-indigo-600 text-center mb-4">
                        <span class="inline-block w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-center text-gray-900 mb-3">Share</h3>
                    <p class="text-gray-600 text-center">Submit your photo, description, short video, or link within 24 hours</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-indigo-600 text-center mb-4">
                        <span class="inline-block w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold">4</span>
                    </div>
                    <h3 class="text-xl font-semibold text-center text-gray-900 mb-3">Earn</h3>
                    <p class="text-gray-600 text-center">Get votes from the community to earn badges and reputation points</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose Challengify?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Our platform offers engaging experiences to boost your creativity and build positive habits</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-xl shadow-md hover:shadow-xl transition duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-bolt text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">A Daily Dose of Inspiration</h3>
                    <p class="text-gray-600">Simple but creative ideas for small actions that take just 15-30 minutes, perfect for students, working people, and parents.</p>
                </div>
                
                <div class="bg-gray-50 p-8 rounded-xl shadow-md hover:shadow-xl transition duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-trophy text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Community & Gamification</h3>
                    <p class="text-gray-600">Earn points and badges, level up, unlock new challenge categories, and compete in weekly championships with fellow creators.</p>
                </div>
                
                <div class="bg-gray-50 p-8 rounded-xl shadow-md hover:shadow-xl transition duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-blue-600 mb-4">
                        <i class="fas fa-random text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Variety of Categories</h3>
                    <p class="text-gray-600">Topics change daily so you'll never get bored, plus themed weeks like zero-waste week, literary week, or photography week.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- User Content Showcase -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">User Gallery</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">See what our community has created with daily challenges</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" data-aos="fade-up">
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                    <img src="https://placehold.co/400x300/purple/white?text=Writing+Challenge" class="w-full h-48 object-cover" alt="Creative Writing Challenge">
                    <div class="p-4">
                        <span class="text-xs font-semibold text-purple-600 bg-purple-100 rounded-full px-3 py-1">Creative Writing</span>
                        <h3 class="mt-2 font-medium text-gray-900">Micro Short Story Challenge</h3>
                        <p class="text-sm text-gray-600 mt-1">By @username</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                    <img src="https://placehold.co/400x300/blue/white?text=Photo+Challenge" class="w-full h-48 object-cover" alt="Photography Challenge">
                    <div class="p-4">
                        <span class="text-xs font-semibold text-blue-600 bg-blue-100 rounded-full px-3 py-1">Photography</span>
                        <h3 class="mt-2 font-medium text-gray-900">Water Reflection Shot</h3>
                        <p class="text-sm text-gray-600 mt-1">By @username</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                    <img src="https://placehold.co/400x300/green/white?text=DIY+Challenge" class="w-full h-48 object-cover" alt="DIY Challenge">
                    <div class="p-4">
                        <span class="text-xs font-semibold text-green-600 bg-green-100 rounded-full px-3 py-1">DIY & Crafts</span>
                        <h3 class="mt-2 font-medium text-gray-900">Recycled Paper Planter</h3>
                        <p class="text-sm text-gray-600 mt-1">By @username</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                    <img src="https://placehold.co/400x300/red/white?text=Healthy+Habit" class="w-full h-48 object-cover" alt="Healthy Habit Challenge">
                    <div class="p-4">
                        <span class="text-xs font-semibold text-red-600 bg-red-100 rounded-full px-3 py-1">Healthy Habits</span>
                        <h3 class="mt-2 font-medium text-gray-900">5-Minute Morning Routine</h3>
                        <p class="text-sm text-gray-600 mt-1">By @username</p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-10">
                <a href="/gallery" class="inline-block px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-md hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
                    View Full Gallery
                </a>
            </div>
        </div>
    </section>
    
    <!-- Call to Action -->
    <section class="bg-gradient-to-r from-blue-700 to-indigo-800 py-16">
        <div class="max-w-4xl mx-auto px-4 text-center text-white" data-aos="zoom-in">
            <h2 class="text-3xl font-bold mb-6">Ready for your daily dose of creativity?</h2>
            <p class="text-xl mb-8">Join thousands of users who are building creative habits with Challengify micro-challenges.</p>
            <a href="/register" class="inline-block px-8 py-4 bg-white text-indigo-800 font-bold rounded-lg shadow-lg hover:bg-gray-100 transition duration-300 transform hover:scale-105 text-lg">
                Sign Up Now — It's Free!
            </a>
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
</body>
</html>