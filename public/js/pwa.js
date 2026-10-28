// PWA Service Worker Registration & Install Banner
(function() {
  // Register Service Worker
  if ('serviceWorker' in navigator && window.location.protocol === 'https:') {
    window.addEventListener('load', function() {
      navigator.serviceWorker.register('/sw.js')
        .then(function(reg) {
          console.log('PWA: Service Worker registered successfully', reg.scope);
        })
        .catch(function(err) {
          console.warn('PWA: Service Worker registration failed:', err);
        });
    });
  }

  // Handle Before Install Prompt
  let deferredPrompt = null;
  const installBanner = document.getElementById('pwa-install-banner');
  const installBtn = document.getElementById('pwa-install-btn');
  const closeBtn = document.getElementById('pwa-close-btn');

  // Check if already dismissed recently
  const isDismissed = localStorage.getItem('pwa_install_dismissed');
  const now = Date.now();
  const oneWeek = 7 * 24 * 60 * 60 * 1000;

  window.addEventListener('beforeinstallprompt', function(e) {
    // Prevent default browser banner
    e.preventDefault();
    deferredPrompt = e;

    if (!isDismissed || (now - parseInt(isDismissed, 10)) > oneWeek) {
      if (installBanner) {
        installBanner.style.display = 'flex';
      }
    }
  });

  if (installBtn) {
    installBtn.addEventListener('click', async function() {
      if (deferredPrompt) {
        installBanner.style.display = 'none';
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        console.log('PWA: User response to install:', outcome);
        deferredPrompt = null;
      }
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', function() {
      if (installBanner) {
        installBanner.style.display = 'none';
      }
      localStorage.setItem('pwa_install_dismissed', Date.now().toString());
    });
  }

  window.addEventListener('appinstalled', function() {
    console.log('PWA: App successfully installed');
    if (installBanner) {
      installBanner.style.display = 'none';
    }
    deferredPrompt = null;
  });
})();
