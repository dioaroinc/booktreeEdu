/* 책나무 온라인 교육 - 공통 스크립트 */
(function () {
    'use strict';

    // 삭제 등 확인이 필요한 폼
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm(form.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    // 알림 메시지 자동 닫기
    document.querySelectorAll('.alert').forEach(function (el) {
        el.addEventListener('click', function () { el.remove(); });
        setTimeout(function () {
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 400);
        }, 6000);
    });

    // 모바일 메뉴: 바깥 클릭 시 닫기
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.site-header, .app-header, .admin-side, .nav-toggle')) {
            document.body.classList.remove('nav-open', 'side-open');
        }
    });

    // CSRF 토큰 (AJAX 용)
    window.BT = window.BT || {};
    window.BT.csrf = function () {
        var n = document.querySelector('meta[name=csrf-name]');
        var t = document.querySelector('meta[name=csrf-token]');
        return n && t ? { name: n.content, token: t.content } : null;
    };
})();
