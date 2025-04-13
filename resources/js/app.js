import './bootstrap';

import '../css/app.css';

document.addEventListener('DOMContentLoaded', function () {
    const preloader = document.getElementById('preloader');
    const images = document.getElementsByTagName('img');
    let loadedCount = 0;
    const totalImages = images.length;

    function hidePreloader() {
        preloader.classList.add('hidden');
        preloader.addEventListener('transitionend', () => {
            preloader.style.display = 'none';
        }, { once: true });
    }

    if (totalImages === 0) {
        window.addEventListener('load', hidePreloader);
    } else {
        Array.from(images).forEach(img => {
            if (img.complete) {
                loadedCount++;
                if (loadedCount === totalImages) hidePreloader();
            } else {
                img.addEventListener('load', () => {
                    loadedCount++;
                    if (loadedCount === totalImages) hidePreloader();
                });
                img.addEventListener('error', () => {
                    loadedCount++; // Vẫn đếm nếu hình lỗi
                    if (loadedCount === totalImages) hidePreloader();
                });
            }
        });
    }
});