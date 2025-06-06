<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Logs - Admin - Challengify</title>
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
                        <a href="/admin" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-purple-600 hover:bg-opacity-50 transition duration-300">Dashboard</a>
                        <a href="/admin/users" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-purple-600 hover:bg-opacity-50 transition duration-300">Users</a>
                        <a href="/admin/challenges" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-purple-600 hover:bg-opacity-50 transition duration-300">Challenges</a>
                        <a href="/admin/reports" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-purple-600 hover:bg-opacity-50 transition duration-300">Reports</a>
                        <a href="/admin/logs" class="bg-purple-600 bg-opacity-50 text-white px-3 py-2 rounded-md text-sm font-medium">Logs</a>
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
                <a href="/admin" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Dashboard</a>
                <a href="/admin/users" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Users</a>
                <a href="/admin/challenges" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Challenges</a>
                <a href="/admin/reports" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Reports</a>
                <a href="/admin/logs" class="block bg-purple-600 bg-opacity-50 text-white px-3 py-2 rounded-md text-base font-medium">Logs</a>
                <a href="/" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Back to Site</a>
                <a href="/logout" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-purple-700 transition duration-300">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Application Logs</h1>
            
            <div class="flex space-x-4">
                <a href="/admin/logs/clear?type=<?php echo htmlspecialchars($logType); ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to clear these logs?');">
                    <i class="fas fa-trash mr-2"></i> Clear Logs
                </a>
                <a href="/admin/logs/download?type=<?php echo htmlspecialchars($logType); ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-download mr-2"></i> Download Logs
                </a>
            </div>
        </div>
        
        <!-- Log Type Selection -->
        <div class="mb-6 bg-white p-4 rounded-md shadow">
            <h2 class="text-lg font-medium text-gray-700 mb-3">Select Log Type</h2>
            <div class="flex space-x-4">
                <a href="/admin/logs?type=app" class="<?php echo $logType === 'app' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'; ?> px-4 py-2 rounded-md font-medium text-sm hover:bg-blue-50 transition">
                    Application Logs
                </a>
                <a href="/admin/logs?type=profile" class="<?php echo $logType === 'profile' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'; ?> px-4 py-2 rounded-md font-medium text-sm hover:bg-blue-50 transition">
                    Profile Update Logs
                </a>
            </div>
        </div>
        
        <!-- Display Lines Selection -->
        <div class="mb-6 bg-white p-4 rounded-md shadow">
            <h2 class="text-lg font-medium text-gray-700 mb-3">Show Lines</h2>
            <div class="flex flex-wrap gap-2">
                <?php foreach ([50, 100, 200, 500, 1000] as $lineOption): ?>
                <a href="/admin/logs?type=<?php echo htmlspecialchars($logType); ?>&lines=<?php echo $lineOption; ?>" 
                   class="<?php echo $lines === $lineOption ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'; ?> px-3 py-1 rounded-md font-medium text-sm hover:bg-blue-50 transition">
                    <?php echo $lineOption; ?> Lines
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Log Display -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <h2 class="text-lg font-medium text-gray-700 mb-3">
                <?php echo $logType === 'profile' ? 'Profile Update Logs' : 'Application Logs'; ?>
            </h2>
            
            <?php if (empty($logContents) || $logContents === "Log file does not exist."): ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <?php echo $logContents === "Log file does not exist." ? "No log file exists yet." : "No logs available."; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-gray-800 text-gray-200 rounded-lg p-4 overflow-auto" style="max-height: 600px; font-family: monospace;">
                    <?php 
                    // Format and colorize log entries
                    $formattedLogs = htmlspecialchars($logContents);
                    
                    // Colorize log levels
                    $formattedLogs = preg_replace('/\[INFO\]/', '<span class="text-blue-400">[INFO]</span>', $formattedLogs);
                    $formattedLogs = preg_replace('/\[DEBUG\]/', '<span class="text-purple-400">[DEBUG]</span>', $formattedLogs);
                    $formattedLogs = preg_replace('/\[ERROR\]/', '<span class="text-red-400">[ERROR]</span>', $formattedLogs);
                    $formattedLogs = preg_replace('/\[WARNING\]/', '<span class="text-yellow-400">[WARNING]</span>', $formattedLogs);
                    
                    // Colorize timestamps
                    $formattedLogs = preg_replace('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]/', '<span class="text-green-400">$0</span>', $formattedLogs);
                    
                    // Make line breaks work in HTML
                    $formattedLogs = nl2br($formattedLogs);
                    
                    echo $formattedLogs;
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 