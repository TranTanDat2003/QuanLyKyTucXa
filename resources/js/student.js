import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const loading = document.querySelector('.loading');
    if (!loading) return;

    // Đảm bảo loading hiển thị ít nhất 500ms
    const minLoadingTime = 500;
    const startTime = Date.now();

    // Tìm tất cả các thẻ <img> trong trang
    const images = document.querySelectorAll('img');
    let loadedImages = 0;
    const totalImages = images.length;

    // Hàm kiểm tra và ẩn loading
    function hideLoading() {
        const elapsedTime = Date.now() - startTime;
        if (elapsedTime < minLoadingTime) {
            setTimeout(() => {
                loading.style.display = 'none';
            }, minLoadingTime - elapsedTime);
        } else {
            loading.style.display = 'none';
        }
    }

    // Nếu không có ảnh, ẩn loading sau minLoadingTime
    if (totalImages === 0) {
        hideLoading();
        return;
    }

    // Theo dõi trạng thái tải của mỗi ảnh
    images.forEach((img) => {
        if (img.complete) {
            loadedImages++;
            if (loadedImages >= totalImages) {
                hideLoading();
            }
        } else {
            img.addEventListener('load', () => {
                loadedImages++;
                if (loadedImages >= totalImages) {
                    hideLoading();
                }
            });
            img.addEventListener('error', () => {
                loadedImages++;
                if (loadedImages >= totalImages) {
                    hideLoading();
                }
            });
        }
    });

    // Fallback: Ẩn loading sau 10 giây nếu có lỗi
    setTimeout(() => {
        loading.style.display = 'none';
    }, 10000);
});