<?php

namespace Kpzsproductions\Challengify\Components;

/**
 * Notification component for displaying animated notifications.
 */
class Notification
{
    /**
     * Display an animated notification on the page.
     * 
     * @param string $message The notification message.
     * @param string $type The type of notification: 'success', 'error', 'warning', 'info'.
     * @param int $duration Duration in milliseconds before auto-dismiss (default: 3000).
     */
    public static function show($message, $type = 'info', $duration = 3000)
    {
        // Output the notification HTML and JS
        $typeClass = htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
        $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        $duration = (int)$duration;
        echo <<<HTML
        <div class="notification notification-{$typeClass}" id="notification-component" style="position:fixed;top:30px;right:30px;z-index:9999;min-width:250px;max-width:400px;padding:16px 32px;border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,0.15);font-family:'Poppins',sans-serif;font-size:1rem;opacity:0;transform:translateY(-30px);transition:opacity 0.4s,transform 0.4s;pointer-events:auto;display:flex;align-items:center;">
            <span style="flex:1;">{$safeMessage}</span>
            <button id="notification-close-btn" style="background:none;border:none;font-size:1.2rem;margin-left:16px;cursor:pointer;color:inherit;">&times;</button>
        </div>
        <style>
            .notification-success { background: #10B981; color: #fff; }
            .notification-error { background: #EF4444; color: #fff; }
            .notification-warning { background: #F59E0B; color: #fff; }
            .notification-info { background: #3B82F6; color: #fff; }
            .notification-hide { opacity: 0 !important; transform: translateY(-30px) !important; pointer-events: none; }
            .notification-show { opacity: 1 !important; transform: translateY(0) !important; }
        </style>
        <script>
            (function() {
                var notif = document.getElementById('notification-component');
                if (!notif) return;
                setTimeout(function() {
                    notif.classList.add('notification-show');
                }, 10);
                var closeBtn = document.getElementById('notification-close-btn');
                var removeNotif = function() {
                    notif.classList.remove('notification-show');
                    notif.classList.add('notification-hide');
                    setTimeout(function() {
                        if (notif && notif.parentNode) notif.parentNode.removeChild(notif);
                    }, 400);
                };
                closeBtn.addEventListener('click', removeNotif);
                setTimeout(removeNotif, {$duration});
            })();
        </script>
        HTML;
    }
}