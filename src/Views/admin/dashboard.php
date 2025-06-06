<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Challengify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/vendor/tailwind.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-700 to-indigo-800 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="/admin" class="flex items-center transition transform hover:scale-105">
                        <img class="h-10 w-10 mr-2 rounded-full shadow-sm" src="/assets/images/chellengify-logo.png" alt="Challengify Logo">
                        <span class="text-white font-bold text-xl">Admin Dashboard</span>
                    </a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="/admin" class="bg-purple-600 bg-opacity-50 text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        <a href="/admin/users" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-purple-600 hover:bg-opacity-50 transition duration-300">Users</a>
                        <a href="/admin/challenges" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-purple-600 hover:bg-opacity-50 transition duration-300">Challenges</a>
                        <a href="/admin/reports" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-purple-600 hover:bg-opacity-50 transition duration-300">Reports</a>
                        <a href="/admin/logs" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-purple-600 hover:bg-opacity-50 transition duration-300">Logs</a>
                    </div>
                </div>
                
                <!-- User profile dropdown -->
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center">
                        <div class="relative">
                            <a href="/" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-purple-600 hover:bg-opacity-50 transition duration-300">
                                <i class="fas fa-home mr-1"></i> Back to Site
                            </a>
                            <a href="/logout" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-purple-600 hover:bg-opacity-50 transition duration-300">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-white hover:text-purple-200 focus:outline-none focus:text-purple-200" aria-label="Open menu" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-purple-800 rounded-b-lg">
                <a href="/admin" class="block bg-purple-600 bg-opacity-50 text-white px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                <a href="/admin/users" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Users</a>
                <a href="/admin/challenges" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Challenges</a>
                <a href="/admin/reports" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Reports</a>
                <a href="/admin/logs" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Logs</a>
                <a href="/" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Back to Site</a>
                <a href="/logout" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>
        
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 mr-4">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Users</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $totalUsers; ?></h3>
                    </div>
                </div>
            </div>
            
            <!-- Total Challenges -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-3 mr-4">
                        <i class="fas fa-trophy text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Challenges</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $totalChallenges; ?></h3>
                    </div>
                </div>
            </div>
            
            <!-- Total Submissions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="rounded-full bg-purple-100 p-3 mr-4">
                        <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Submissions</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $totalSubmissions; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Quick Links</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="/admin/users" class="flex items-center p-3 rounded-md bg-gray-100 hover:bg-gray-200 transition">
                    <i class="fas fa-users mr-2 text-gray-700"></i>
                    <span>Manage Users</span>
                </a>
                <a href="/admin/challenges" class="flex items-center p-3 rounded-md bg-gray-100 hover:bg-gray-200 transition">
                    <i class="fas fa-trophy mr-2 text-gray-700"></i>
                    <span>Manage Challenges</span>
                </a>
                <a href="/admin/reports" class="flex items-center p-3 rounded-md bg-gray-100 hover:bg-gray-200 transition">
                    <i class="fas fa-flag mr-2 text-gray-700"></i>
                    <span>Review Reports</span>
                </a>
                <a href="/admin/logs" class="flex items-center p-3 rounded-md bg-gray-100 hover:bg-gray-200 transition">
                    <i class="fas fa-clipboard-list mr-2 text-gray-700"></i>
                    <span>View Logs</span>
                </a>
            </div>
        </div>
        
        <!-- System Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4">System Status</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Logs Directory</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo is_dir(__DIR__ . '/../../../logs') && is_writable(__DIR__ . '/../../../logs') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                        <?php echo is_dir(__DIR__ . '/../../../logs') && is_writable(__DIR__ . '/../../../logs') ? 'Writable' : 'Not Writable'; ?>
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Upload Directory</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo is_dir(__DIR__ . '/../../../public/assets/images/profiles') && is_writable(__DIR__ . '/../../../public/assets/images/profiles') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                        <?php echo is_dir(__DIR__ . '/../../../public/assets/images/profiles') && is_writable(__DIR__ . '/../../../public/assets/images/profiles') ? 'Writable' : 'Not Writable'; ?>
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">PHP Version</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <?php echo phpversion(); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 