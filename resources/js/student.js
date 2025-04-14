import '../css/app.css';

document.addEventListener('DOMContentLoaded', () => {
    const loading = document.querySelector('.loading');
    
    // Hiển thị loading khi bắt đầu
    if (loading) {
        loading.style.display = 'flex';
    }

    // Tìm tất cả các thẻ <img> trong trang
    const images = document.querySelectorAll('img');
    let loadedImages = 0;
    const totalImages = images.length;

    // Nếu không có ảnh, ẩn loading ngay
    if (totalImages === 0) {
        if (loading) {
            loading.style.display = 'none';
        }
        return;
    }

    // Theo dõi trạng thái tải của mỗi ảnh
    images.forEach((img) => {
        // Nếu ảnh đã được tải từ cache
        if (img.complete) {
            loadedImages++;
            checkAllImagesLoaded();
        } else {
            img.addEventListener('load', () => {
                loadedImages++;
                checkAllImagesLoaded();
            });
            img.addEventListener('error', () => {
                loadedImages++;
                checkAllImagesLoaded();
            });
        }
    });

    function checkAllImagesLoaded() {
        if (loadedImages >= totalImages && loading) {
            loading.style.display = 'none';
        }
    }

    // Fallback: Ẩn loading sau 10 giây nếu có lỗi
    setTimeout(() => {
        if (loading) {
            loading.style.display = 'none';
        }
    }, 10000);
});