/* 책나무 온라인 교육 - 강의 영상 플레이어 (진도 저장 / 시청완료 처리)
 *
 *  - HTML5 <video> (업로드 파일 / 외부 URL) 와 유튜브 IFrame API 모두 지원
 *  - 10초마다 재생 위치 저장, 95% 이상 또는 종료 시 "시청완료" 전송
 */
(function () {
    'use strict';

    var box = document.getElementById('player');
    if (!box) return;

    var type        = box.dataset.type;
    var progressUrl = box.dataset.progressUrl;
    var resumeAt    = parseInt(box.dataset.resume || '0', 10);
    var done        = box.dataset.done === '1';
    var statusEl    = document.getElementById('videoStatus');
    var bar         = document.getElementById('watchBar');
    var text        = document.getElementById('watchText');
    var quizBtn     = document.getElementById('quizBtn');
    var lastSent    = 0;
    var duration    = 0;

    var csrf = window.BT && window.BT.csrf ? window.BT.csrf() : null;

    function fmt(s) {
        s = Math.max(0, Math.floor(s));
        var m = Math.floor(s / 60), r = s % 60;
        return m + ':' + (r < 10 ? '0' : '') + r;
    }

    function render(current) {
        if (duration > 0) {
            var pct = Math.min(100, Math.round(current / duration * 100));
            if (bar) bar.style.width = pct + '%';
            if (text) text.textContent = fmt(current) + ' / ' + fmt(duration) + ' (' + pct + '%)' + (done ? ' · 시청 완료' : '');
        }
    }

    function send(seconds, finished) {
        if (!progressUrl) return;
        var body = new FormData();
        body.append('seconds', Math.floor(seconds));
        body.append('done', finished ? 1 : 0);
        if (csrf) body.append(csrf.name, csrf.token);

        fetch(progressUrl, {
            method: 'POST',
            body: body,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (r) { return r.json(); }).then(function (res) {
            if (res && res.ok && res.video_status === 2 && !done) {
                done = true;
                markDone();
            }
        }).catch(function () { /* 네트워크 오류는 무시 (다음 저장에서 재시도) */ });
    }

    function markDone() {
        if (statusEl) {
            statusEl.textContent = '✅ 시청 완료';
            statusEl.dataset.status = '2';
        }
        if (quizBtn) quizBtn.removeAttribute('data-locked');
        if (text) text.textContent += ' · 시청 완료';
    }

    function tick(current, total) {
        duration = total || duration;
        render(current);

        var now = Date.now();
        if (now - lastSent > 10000) {           // 10초마다 저장
            lastSent = now;
            var finished = duration > 0 && current / duration >= 0.95;
            send(current, finished);
        }
    }

    function ended(current) {
        lastSent = Date.now();
        send(current, true);
    }

    // 문제풀기 버튼 잠금 안내
    if (quizBtn) {
        quizBtn.addEventListener('click', function (e) {
            if (quizBtn.dataset.locked === '1') {
                e.preventDefault();
                alert('강의 영상을 끝까지 시청한 후 시험에 응시할 수 있습니다.');
            }
        });
    }

    // ── HTML5 video ────────────────────────────────────────
    var video = document.getElementById('htmlPlayer');
    if (video) {
        video.addEventListener('loadedmetadata', function () {
            duration = video.duration;
            if (resumeAt > 5 && resumeAt < duration - 5 && !done) {
                video.currentTime = resumeAt;
            }
            render(video.currentTime);
        });
        video.addEventListener('timeupdate', function () { tick(video.currentTime, video.duration); });
        video.addEventListener('pause', function () { lastSent = 0; tick(video.currentTime, video.duration); });
        video.addEventListener('ended', function () { ended(video.duration); });
        window.addEventListener('beforeunload', function () {
            if (video.currentTime > 0 && navigator.sendBeacon && progressUrl) {
                var body = new FormData();
                body.append('seconds', Math.floor(video.currentTime));
                body.append('done', 0);
                if (csrf) body.append(csrf.name, csrf.token);
                navigator.sendBeacon(progressUrl, body);
            }
        });
        return;
    }

    // ── YouTube IFrame API ─────────────────────────────────
    var ytId = box.dataset.youtubeId;
    if (type === 'youtube' && ytId) {
        var ytPlayer = null;
        var timer    = null;

        window.onYouTubeIframeAPIReady = function () {
            ytPlayer = new YT.Player('ytPlayer', {
                videoId: ytId,
                playerVars: { rel: 0, modestbranding: 1, playsinline: 1, start: (!done && resumeAt > 5) ? resumeAt : 0 },
                events: {
                    onReady: function () {
                        duration = ytPlayer.getDuration();
                        render(ytPlayer.getCurrentTime());
                    },
                    onStateChange: function (e) {
                        if (e.data === YT.PlayerState.PLAYING) {
                            clearInterval(timer);
                            timer = setInterval(function () {
                                tick(ytPlayer.getCurrentTime(), ytPlayer.getDuration());
                            }, 1000);
                        } else {
                            clearInterval(timer);
                            if (e.data === YT.PlayerState.ENDED) {
                                ended(ytPlayer.getDuration());
                            } else if (e.data === YT.PlayerState.PAUSED) {
                                lastSent = 0;
                                tick(ytPlayer.getCurrentTime(), ytPlayer.getDuration());
                            }
                        }
                    }
                }
            });
        };

        var tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        document.head.appendChild(tag);
    }
})();
