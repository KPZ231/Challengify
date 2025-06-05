<?php

/*
 * Odpowiedź na pytanie: "W jakim języku byłoby najlepiej napisać ten skrypt?"
 *
 * Najlepszym wyborem do napisania tego typu skryptu (panel debugowania wyświetlany na stronie, pokazujący zmienne PHP i umożliwiający interakcję w przeglądarce) jest połączenie PHP oraz JavaScript.
 *
 * Uzasadnienie:
 * - PHP jest niezbędny do pobierania i wyświetlania danych serwerowych, takich jak $_SESSION, $_GET, $_POST, $_COOKIE, $_SERVER.
 * - JavaScript jest idealny do obsługi interakcji użytkownika na stronie (pokazywanie/ukrywanie panelu, obsługa przycisków, czyszczenie localStorage).
 * - HTML i CSS są potrzebne do prezentacji i stylowania panelu debugowania.
 *
 * Alternatywy:
 * - Jeśli panel miałby działać tylko po stronie klienta (np. debugować wyłącznie dane z localStorage lub cookies), wystarczyłby sam JavaScript.
 * - Jeśli panel miałby być częścią aplikacji SPA (np. React, Vue), można by napisać go w TypeScript/JavaScript w ramach frameworka.
 * - Jednak do debugowania zmiennych serwerowych w aplikacji PHP, najlepszym wyborem jest połączenie PHP (do generowania danych) i JavaScript (do interakcji).
 *
 * Podsumowanie:
 * Najlepiej napisać ten skrypt w PHP (do generowania danych debugowych) oraz JavaScript (do obsługi panelu na stronie).
 */

// Nie używamy już warunku "if debug", zawsze ładujemy skrypt z ikonką
// Panel jest domyślnie ukryty i aktywuje się po kliknięciu
?>
<style>
#debughand-icon {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    background: #2563eb;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    z-index: 99998;
    font-size: 20px;
    font-weight: bold;
    font-family: monospace;
    user-select: none;
    transition: transform 0.2s, background-color 0.2s;
}

#debughand-icon:hover {
    transform: scale(1.1);
    background: #1d4ed8;
}

#debughand-panel {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 420px;
    max-height: 70vh;
    background: #222;
    color: #eee;
    font-family: monospace;
    font-size: 13px;
    border-radius: 8px;
    box-shadow: 0 0 12px rgba(0,0,0,0.6);
    z-index: 99999;
    overflow: auto;
    transform: scale(0);
    opacity: 0;
    transform-origin: bottom right;
    transition: transform 0.3s, opacity 0.3s;
}

#debughand-panel.visible {
    transform: scale(1);
    opacity: 1;
}

#debughand-panel h3 {
    margin: 0;
    padding: 12px 16px;
    background: #111;
    font-size: 15px;
    cursor: move;
    user-select: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

#debughand-panel .debughand-content {
    padding: 16px;
}

#debughand-panel .debughand-section {
    margin-bottom: 16px;
}

#debughand-panel .debughand-section-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
    cursor: pointer;
}

#debughand-panel .debughand-section-content {
    max-height: 200px;
    overflow: auto;
}

#debughand-panel pre {
    margin: 0;
    background: #181818;
    padding: 8px;
    border-radius: 4px;
    overflow: auto;
}

#debughand-panel button {
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 4px 10px;
    margin-left: 6px;
    cursor: pointer;
    font-size: 12px;
}

#debughand-panel button:hover {
    background: #1d4ed8;
}

#debughand-tabs {
    display: flex;
    background: #333;
    border-bottom: 1px solid #444;
}

#debughand-tabs button {
    background: transparent;
    color: #ccc;
    border: none;
    padding: 8px 16px;
    margin: 0;
    cursor: pointer;
    font-size: 13px;
    border-radius: 0;
}

#debughand-tabs button.active {
    background: #222;
    color: white;
    border-bottom: 2px solid #2563eb;
}

.debughand-tab-content {
    display: none;
}

.debughand-tab-content.active {
    display: block;
}
</style>

<!-- Debug icon -->
<div id="debughand-icon">D</div>

<!-- Debug panel -->
<div id="debughand-panel">
    <h3 id="debughand-header">
        DebugHand
        <span>
            <button id="debughand-refresh">Odśwież</button>
            <button id="debughand-close">Zamknij</button>
        </span>
    </h3>
    
    <div id="debughand-tabs">
        <button class="debughand-tab-btn active" data-tab="server">Serwer</button>
        <button class="debughand-tab-btn" data-tab="client">Klient</button>
        <button class="debughand-tab-btn" data-tab="performance">Wydajność</button>
        <button class="debughand-tab-btn" data-tab="network">Sieć</button>
        <button class="debughand-tab-btn" data-tab="options">Opcje</button>
    </div>
    
    <div class="debughand-content">
        <!-- Server Tab -->
        <div class="debughand-tab-content active" id="debughand-tab-server">
            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>$_SESSION</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre><?php echo htmlspecialchars(print_r($_SESSION ?? [], true)); ?></pre>
                </div>
            </div>
            
            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>$_GET</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre><?php echo htmlspecialchars(print_r($_GET, true)); ?></pre>
                </div>
            </div>
            
            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>$_POST</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre><?php echo htmlspecialchars(print_r($_POST, true)); ?></pre>
                </div>
            </div>
            
            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>$_COOKIE</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre><?php echo htmlspecialchars(print_r($_COOKIE, true)); ?></pre>
                </div>
            </div>
            
            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>$_SERVER</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre><?php echo htmlspecialchars(print_r($_SERVER, true)); ?></pre>
                </div>
            </div>

            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>PHP Info</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre><?php 
                        $phpInfo = [
                            'version' => PHP_VERSION,
                            'os' => PHP_OS,
                            'extensions' => get_loaded_extensions(),
                            'memory_limit' => ini_get('memory_limit'),
                            'max_execution_time' => ini_get('max_execution_time')
                        ];
                        echo htmlspecialchars(print_r($phpInfo, true)); 
                    ?></pre>
                </div>
            </div>
        </div>
        
        <!-- Client Tab -->
        <div class="debughand-tab-content" id="debughand-tab-client">
            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>localStorage</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-ls"></pre>
                </div>
            </div>
            
            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>Cookies</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-cookies"></pre>
                </div>
            </div>
            
            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>sessionStorage</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-session-storage"></pre>
                </div>
            </div>
            
            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>Informacje o przeglądarce</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-browser-info"></pre>
                </div>
            </div>

            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>Załadowane skrypty</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-loaded-scripts"></pre>
                </div>
            </div>

            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>Załadowane style CSS</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-loaded-styles"></pre>
                </div>
            </div>
        </div>

        <!-- Performance Tab -->
        <div class="debughand-tab-content" id="debughand-tab-performance">
            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>Metryki wydajności</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-performance-metrics"></pre>
                </div>
            </div>

            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>Użycie pamięci</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-memory-usage"></pre>
                </div>
            </div>

            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>Timeline zdarzeń</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-events-timeline"></pre>
                </div>
            </div>
        </div>

        <!-- Network Tab -->
        <div class="debughand-tab-content" id="debughand-tab-network">
            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>Aktywne żądania XHR</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-xhr-requests"></pre>
                </div>
            </div>

            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>Statystyki sieci</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-network-stats"></pre>
                </div>
            </div>

            <div class="debughand-section">
                <div class="debughand-section-title">
                    <strong>WebSocket połączenia</strong>
                    <span class="debughand-toggle">▼</span>
                </div>
                <div class="debughand-section-content">
                    <pre id="debughand-websocket-connections"></pre>
                </div>
            </div>
        </div>
        
        <!-- Options Tab -->
        <div class="debughand-tab-content" id="debughand-tab-options">
            <div style="padding: 16px;">
                <h4>Dane przeglądarki</h4>
                <button id="debughand-ls-clear">Wyczyść localStorage</button>
                <button id="debughand-cookie-clear">Wyczyść Cookies</button>
                <button id="debughand-session-clear">Wyczyść sessionStorage</button>
                
                <h4>Ustawienia panelu</h4>
                <button id="debughand-toggle-dark">Przełącz tryb ciemny</button>
                <button id="debughand-toggle-position">Zmień pozycję</button>
                <button id="debughand-reset-position">Resetuj pozycję</button>

                <h4>Narzędzia deweloperskie</h4>
                <button id="debughand-console-clear">Wyczyść konsolę</button>
                <button id="debughand-reload-scripts">Przeładuj skrypty</button>
                <button id="debughand-check-errors">Sprawdź błędy JS</button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    // DOM elements
    const debugIcon = document.getElementById('debughand-icon');
    const debugPanel = document.getElementById('debughand-panel');
    const closeBtn = document.getElementById('debughand-close');
    const refreshBtn = document.getElementById('debughand-refresh');
    const tabButtons = document.querySelectorAll('.debughand-tab-btn');
    const sectionTitles = document.querySelectorAll('.debughand-section-title');
    const header = document.getElementById('debughand-header');
    
    // Tab switching
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Deactivate all tabs
            tabButtons.forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.debughand-tab-content').forEach(tab => tab.classList.remove('active'));
            
            // Activate clicked tab
            button.classList.add('active');
            document.getElementById('debughand-tab-' + button.dataset.tab).classList.add('active');
            
            // Refresh data for specific tabs
            if (button.dataset.tab === 'performance') {
                refreshPerformanceData();
            } else if (button.dataset.tab === 'network') {
                refreshNetworkData();
            }
        });
    });
    
    // Section toggling
    sectionTitles.forEach(title => {
        title.addEventListener('click', () => {
            const content = title.nextElementSibling;
            const toggle = title.querySelector('.debughand-toggle');
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                toggle.textContent = '▼';
            } else {
                content.style.display = 'none';
                toggle.textContent = '►';
            }
        });
    });
    
    // Show/hide panel
    debugIcon.addEventListener('click', () => {
        debugPanel.classList.add('visible');
        refreshData();
    });
    
    closeBtn.addEventListener('click', () => {
        debugPanel.classList.remove('visible');
    });
    
    refreshBtn.addEventListener('click', refreshData);
    
    // Make panel draggable
    let isDragging = false;
    let offsetX, offsetY;
    
    header.addEventListener('mousedown', (e) => {
        isDragging = true;
        offsetX = e.clientX - debugPanel.getBoundingClientRect().left;
        offsetY = e.clientY - debugPanel.getBoundingClientRect().top;
    });
    
    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        
        const x = e.clientX - offsetX;
        const y = e.clientY - offsetY;
        
        debugPanel.style.left = `${x}px`;
        debugPanel.style.right = 'auto';
        debugPanel.style.top = `${y}px`;
        debugPanel.style.bottom = 'auto';
    });
    
    document.addEventListener('mouseup', () => {
        isDragging = false;
    });
    
    // Data handling functions
    function refreshData() {
        dumpLocalStorage();
        dumpCookies();
        dumpSessionStorage();
        dumpBrowserInfo();
        dumpLoadedScripts();
        dumpLoadedStyles();
        refreshPerformanceData();
        refreshNetworkData();
    }
    
    function dumpLocalStorage() {
        const out = {};
        for (let i = 0; i < localStorage.length; ++i) {
            const key = localStorage.key(i);
            out[key] = localStorage.getItem(key);
        }
        document.getElementById('debughand-ls').textContent = JSON.stringify(out, null, 2);
    }
    
    function dumpCookies() {
        const cookies = {};
        document.cookie.split(';').forEach(cookie => {
            const [key, value] = cookie.trim().split('=');
            if (key) cookies[key] = value;
        });
        document.getElementById('debughand-cookies').textContent = JSON.stringify(cookies, null, 2);
    }
    
    function dumpSessionStorage() {
        const out = {};
        for (let i = 0; i < sessionStorage.length; ++i) {
            const key = sessionStorage.key(i);
            out[key] = sessionStorage.getItem(key);
        }
        document.getElementById('debughand-session-storage').textContent = JSON.stringify(out, null, 2);
    }
    
    function dumpBrowserInfo() {
        const info = {
            userAgent: navigator.userAgent,
            language: navigator.language,
            platform: navigator.platform,
            screenWidth: window.screen.width,
            screenHeight: window.screen.height,
            viewportWidth: window.innerWidth,
            viewportHeight: window.innerHeight,
            devicePixelRatio: window.devicePixelRatio,
            connection: navigator.connection ? {
                type: navigator.connection.effectiveType,
                downlink: navigator.connection.downlink,
                rtt: navigator.connection.rtt
            } : 'Not available'
        };
        document.getElementById('debughand-browser-info').textContent = JSON.stringify(info, null, 2);
    }

    function dumpLoadedScripts() {
        const scripts = Array.from(document.scripts).map(script => ({
            src: script.src || 'inline',
            type: script.type,
            async: script.async,
            defer: script.defer
        }));
        document.getElementById('debughand-loaded-scripts').textContent = JSON.stringify(scripts, null, 2);
    }

    function dumpLoadedStyles() {
        const styles = Array.from(document.styleSheets).map(sheet => ({
            href: sheet.href || 'inline',
            disabled: sheet.disabled,
            media: sheet.media.mediaText
        }));
        document.getElementById('debughand-loaded-styles').textContent = JSON.stringify(styles, null, 2);
    }

    function refreshPerformanceData() {
        if (!window.performance) return;

        const timing = performance.timing;
        const metrics = {
            pageLoad: timing.loadEventEnd - timing.navigationStart,
            domReady: timing.domComplete - timing.domLoading,
            networkLatency: timing.responseEnd - timing.fetchStart,
            processingTime: timing.loadEventEnd - timing.responseEnd,
            memoryInfo: performance.memory ? {
                usedJSHeapSize: Math.round(performance.memory.usedJSHeapSize / 1048576) + ' MB',
                totalJSHeapSize: Math.round(performance.memory.totalJSHeapSize / 1048576) + ' MB'
            } : 'Not available'
        };

        document.getElementById('debughand-performance-metrics').textContent = JSON.stringify(metrics, null, 2);
        document.getElementById('debughand-memory-usage').textContent = JSON.stringify(performance.memory || 'Not available', null, 2);
        
        const entries = performance.getEntriesByType('navigation')[0];
        document.getElementById('debughand-events-timeline').textContent = JSON.stringify(entries || 'Not available', null, 2);
    }

    function refreshNetworkData() {
        const networkInfo = {
            activeXHR: performance.getEntriesByType('resource').filter(entry => entry.initiatorType === 'xmlhttprequest'),
            connection: navigator.connection ? {
                type: navigator.connection.effectiveType,
                downlink: navigator.connection.downlink + ' Mbps',
                rtt: navigator.connection.rtt + ' ms'
            } : 'Not available'
        };

        document.getElementById('debughand-xhr-requests').textContent = JSON.stringify(networkInfo.activeXHR, null, 2);
        document.getElementById('debughand-network-stats').textContent = JSON.stringify(networkInfo.connection, null, 2);
        
        // WebSocket connections info
        const wsConnections = Array.from(document.querySelectorAll('script')).filter(script => 
            script.textContent.includes('WebSocket')
        ).length;
        document.getElementById('debughand-websocket-connections').textContent = 
            JSON.stringify({ activeConnections: wsConnections }, null, 2);
    }
    
    // Clear data buttons
    document.getElementById('debughand-ls-clear').addEventListener('click', () => {
        if (confirm('Czy na pewno wyczyścić localStorage?')) {
            localStorage.clear();
            dumpLocalStorage();
        }
    });
    
    document.getElementById('debughand-cookie-clear').addEventListener('click', () => {
        if (confirm('Czy na pewno wyczyścić cookies?')) {
            document.cookie.split(';').forEach(cookie => {
                const [key] = cookie.trim().split('=');
                document.cookie = `${key}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/`;
            });
            dumpCookies();
        }
    });
    
    document.getElementById('debughand-session-clear').addEventListener('click', () => {
        if (confirm('Czy na pewno wyczyścić sessionStorage?')) {
            sessionStorage.clear();
            dumpSessionStorage();
        }
    });

    // New options handlers
    document.getElementById('debughand-toggle-dark').addEventListener('click', () => {
        debugPanel.classList.toggle('dark-mode');
    });

    document.getElementById('debughand-toggle-position').addEventListener('click', () => {
        const currentPosition = window.getComputedStyle(debugPanel).right;
        if (currentPosition === '20px') {
            debugPanel.style.right = 'auto';
            debugPanel.style.left = '20px';
        } else {
            debugPanel.style.left = 'auto';
            debugPanel.style.right = '20px';
        }
    });

    document.getElementById('debughand-reset-position').addEventListener('click', () => {
        debugPanel.style.top = 'auto';
        debugPanel.style.left = 'auto';
        debugPanel.style.right = '20px';
        debugPanel.style.bottom = '20px';
    });

    document.getElementById('debughand-console-clear').addEventListener('click', () => {
        console.clear();
    });

    document.getElementById('debughand-reload-scripts').addEventListener('click', () => {
        location.reload(true);
    });

    document.getElementById('debughand-check-errors').addEventListener('click', () => {
        const errors = window.performance.getEntriesByType('error');
        console.log('JavaScript Errors:', errors);
    });
    
    // Initialize
    refreshData();
})();
</script>
