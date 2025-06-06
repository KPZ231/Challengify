<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings - Challengify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/vendor/tailwind.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                        <a href="/dashboard" class="text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-600 hover:bg-opacity-50 transition duration-300">Dashboard</a>
                        <a href="/settings" class="bg-blue-600 bg-opacity-50 text-white px-3 py-2 rounded-md text-sm font-medium">Settings</a>
                    </div>
                </div>
                
                <!-- User profile dropdown -->
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center">
                        <div class="relative">
                            <button type="button" class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true" onclick="document.getElementById('user-dropdown').classList.toggle('hidden')">
                                <span class="sr-only">Open user menu</span>
                                <?php if (!empty($user['profile_image'])): ?>
                                    <img class="h-8 w-8 rounded-full" src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile">
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
                <a href="/dashboard" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300">Dashboard</a>
                <a href="/settings" class="block bg-blue-600 bg-opacity-50 text-white px-3 py-2 rounded-md text-base font-medium">Settings</a>
                <div class="flex items-center px-3 py-2">
                    <?php if (!empty($user['profile_image'])): ?>
                        <img class="h-8 w-8 rounded-full" src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile">
                    <?php else: ?>
                        <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    <span class="ml-2 text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
                <a href="/logout" class="block text-white px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 transition duration-300">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Account Settings</h1>

        <!-- Display success messages -->
        <?php if (!empty($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc pl-5">
                    <?php foreach ($success as $message): ?>
                        <li><?php echo htmlspecialchars($message); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Settings Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form action="/update-settings" method="POST" enctype="multipart/form-data">
                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button type="button" class="tab-btn active border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="profile">
                            Profile Information
                        </button>
                        <button type="button" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="password">
                            Password
                        </button>
                        <button type="button" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="preferences">
                            Preferences
                        </button>
                    </nav>
                </div>

                <!-- Profile Tab -->
                <div id="profile-tab" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Left Column - Profile Image -->
                        <div class="md:col-span-1">
                            <div class="flex flex-col items-center">
                                <div class="mb-4">
                                    <?php if (!empty($user['profile_image'])): ?>
                                        <img id="profile-preview" class="h-32 w-32 rounded-full object-cover" src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile">
                                    <?php else: ?>
                                        <div id="profile-preview" class="h-32 w-32 rounded-full bg-blue-600 flex items-center justify-center text-white text-4xl">
                                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Image</label>
                                    <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/jpeg,image/png,image/gif" onchange="previewImage(this)">
                                    <label for="profile_image" class="cursor-pointer py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                        Choose Image
                                    </label>
                                    <?php if (!empty($errors['profile_image'])): ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo htmlspecialchars($errors['profile_image']); ?></p>
                                    <?php endif; ?>
                                    <p class="mt-1 text-xs text-gray-500">Max size: 2MB. JPG, PNG or GIF.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Profile Info -->
                        <div class="md:col-span-2">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                    <input type="text" name="username" id="username" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" value="<?php echo htmlspecialchars($user['username']); ?>">
                                    <?php if (!empty($errors['username'])): ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo htmlspecialchars($errors['username']); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" id="email" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" value="<?php echo htmlspecialchars($user['email']); ?>">
                                    <?php if (!empty($errors['email'])): ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo htmlspecialchars($errors['email']); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div>
                                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                                    <textarea name="bio" id="bio" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Brief description for your profile.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Tab -->
                <div id="password-tab" class="tab-content hidden">
                    <div class="grid grid-cols-1 gap-4 max-w-md mx-auto">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <?php if (!empty($errors['current_password'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo htmlspecialchars($errors['current_password']); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <?php if (!empty($errors['new_password'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo htmlspecialchars($errors['new_password']); ?></p>
                            <?php endif; ?>
                            <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                        </div>
                        
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <?php if (!empty($errors['confirm_password'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo htmlspecialchars($errors['confirm_password']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Preferences Tab -->
                <div id="preferences-tab" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Notification Settings -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Notification Settings</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="email_notifications" id="email_notifications" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" <?php echo (!empty($settings['email_notifications'])) ? 'checked' : ''; ?>>
                                    <label for="email_notifications" class="ml-2 block text-sm text-gray-700">Email Notifications</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="push_notifications" id="push_notifications" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" <?php echo (!empty($settings['push_notifications'])) ? 'checked' : ''; ?>>
                                    <label for="push_notifications" class="ml-2 block text-sm text-gray-700">Push Notifications</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Interface Settings -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Interface Settings</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                                    <select name="language" id="language" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="en" <?php echo ($settings['language'] === 'en') ? 'selected' : ''; ?>>English</option>
                                        <option value="es" <?php echo ($settings['language'] === 'es') ? 'selected' : ''; ?>>Spanish</option>
                                        <option value="fr" <?php echo ($settings['language'] === 'fr') ? 'selected' : ''; ?>>French</option>
                                        <option value="de" <?php echo ($settings['language'] === 'de') ? 'selected' : ''; ?>>German</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="theme" class="block text-sm font-medium text-gray-700 mb-1">Theme</label>
                                    <select name="theme" id="theme" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="light" <?php echo ($settings['theme'] === 'light') ? 'selected' : ''; ?>>Light</option>
                                        <option value="dark" <?php echo ($settings['theme'] === 'dark') ? 'selected' : ''; ?>>Dark</option>
                                        <option value="system" <?php echo ($settings['theme'] === 'system') ? 'selected' : ''; ?>>System Default</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Category Preferences -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Preferred Categories</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <?php foreach ($categories as $category): ?>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="preferred_categories[]" id="category_<?php echo $category['category_id']; ?>" value="<?php echo $category['category_id']; ?>" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            <?php echo (in_array($category['category_id'], $preferredCategories)) ? 'checked' : ''; ?>>
                                        <label for="category_<?php echo $category['category_id']; ?>" class="ml-2 block text-sm text-gray-700"><?php echo htmlspecialchars($category['name']); ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 pt-5 border-t border-gray-200">
                    <div class="flex justify-end">
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('active', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Show the selected tab content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId + '-tab').classList.remove('hidden');
                });
            });
        });
        
        // Profile image preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const preview = document.getElementById('profile-preview');
                    
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // Create new image element
                        const img = document.createElement('img');
                        img.id = 'profile-preview';
                        img.className = 'h-32 w-32 rounded-full object-cover';
                        img.src = e.target.result;
                        
                        // Replace the div with the image
                        preview.parentNode.replaceChild(img, preview);
                    }
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html> 