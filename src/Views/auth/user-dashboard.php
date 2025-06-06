<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Challengify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/vendor/tailwind.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

       <!-- Font Awesome -->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        <a href="/dashboard" class="bg-blue-600 bg-opacity-50 text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                    </div>
                </div>
                
                <!-- User profile dropdown -->
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center">
                        <div class="relative">
                            <button type="button" class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true" onclick="document.getElementById('user-dropdown').classList.toggle('hidden')">
                                <span class="sr-only">Open user menu</span>
                                <?php if (!empty($_SESSION['profile_image'])): ?>
                                    <img class="h-8 w-8 rounded-full" src="<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="Profile">
                                <?php else: ?>
                                    <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
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
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
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
                <a href="/dashboard" class="block bg-blue-600 bg-opacity-50 text-white px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                <div class="flex items-center px-3 py-2">
                    <?php if (!empty($_SESSION['profile_image'])): ?>
                        <img class="h-8 w-8 rounded-full" src="<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="Profile">
                    <?php else: ?>
                        <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    <span class="ml-2 text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
                <a href="/settings" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300">Settings</a>
                <a href="/logout" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Left column - Profile -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Profile</h2>
                    <div class="text-center mb-6">
                        <?php if (!empty($_SESSION['profile_image'])): ?>
                            <img class="h-24 w-24 rounded-full mx-auto mb-3" src="<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="Profile">
                        <?php else: ?>
                            <div class="h-24 w-24 rounded-full bg-blue-600 flex items-center justify-center text-white text-4xl mx-auto mb-3">
                                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <h3 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                        <p class="text-gray-600"><?php echo ucfirst(htmlspecialchars($_SESSION['role'])); ?></p>
                        
                        <?php if (!empty($user['bio'])): ?>
                            <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($user['bio']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4">
                        <a href="/settings" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-center transition duration-300">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Right column - Dashboard content -->
            <div class="md:col-span-3">
                <!-- Stats Cards -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Dashboard Overview</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white text-center">
                            <i class="fas fa-trophy text-3xl mb-2"></i>
                            <h3 class="text-2xl font-bold"><?php echo $challengesCompleted ?? 0; ?></h3>
                            <p>Challenges Completed</p>
                        </div>
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white text-center">
                            <i class="fas fa-star text-3xl mb-2"></i>
                            <h3 class="text-2xl font-bold"><?php echo $pointsEarned ?? 0; ?></h3>
                            <p>Points Earned</p>
                        </div>
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white text-center">
                            <i class="fas fa-medal text-3xl mb-2"></i>
                            <h3 class="text-2xl font-bold"><?php echo $achievementCount ?? 0; ?></h3>
                            <p>Achievements</p>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h2>
                    <?php if (!empty($recentActivity)): ?>
                        <div class="divide-y divide-gray-200">
                            <?php foreach ($recentActivity as $activity): ?>
                                <div class="py-3">
                                    <p class="font-medium text-gray-800"><?php echo htmlspecialchars($activity['title']); ?></p>
                                    <p class="text-sm text-gray-600">Challenge: <?php echo htmlspecialchars($activity['challenge_title']); ?></p>
                                    <p class="text-xs text-gray-500 mt-1"><?php echo date('F j, Y, g:i a', strtotime($activity['created_at'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="py-4 text-center">
                            <p class="text-gray-600">Welcome to Challengify! Complete challenges to see your activity here.</p>
                            <a href="/challenges" class="inline-block mt-3 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-center transition duration-300">
                                Explore Challenges
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>