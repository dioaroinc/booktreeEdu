# 책나무 온라인 교육 (CodeIgniter 4)

책나무 가맹 원장님을 위한 동영상 교육 시스템입니다.
기존 CodeIgniter 3 프로토타입(HTML 목업)을 **CodeIgniter 4.7** 로 재구축하고 실제 기능을 구현했습니다.

| 영역 | 주소 | 설명 |
|------|------|------|
| 홍보 메인 | `/` | 교육 소개 랜딩 페이지 (과정 소개, 이용 방법, 데모 영상) |
| 로그인 | `/login` | 지점SW 계정 또는 직접 등록한 계정 |
| 강의실 | `/academy` | 과정 → 강의(영상) → 확인 시험 → 결과, 나의 교육 현황 |
| 관리자 | `/admin` | 과정·단원·강의(영상 업로드)·시험 문항·회원·수강 현황 관리 |

## 데모 계정

| 구분 | 아이디 | 비밀번호 |
|------|--------|----------|
| 관리자 | `admin` | `admin1234` |
| 수강생 | `demo` | `demo1234` |
| 수강생 | `demo2` | `demo1234` |

> 운영 배포 후에는 관리자 > 회원 관리에서 비밀번호를 반드시 변경하세요.

## 로컬 개발 (Laragon)

```bash
# 1. DB 생성 (Laragon MySQL, root / 비밀번호 없음)
mysql -uroot -e "CREATE DATABASE booktreeedu CHARACTER SET utf8mb4"

# 2. 테이블 생성 + 데모 데이터
php spark migrate
php spark db:seed DemoSeeder

# 3. 개발 서버
php spark serve --port 8080
# → http://localhost:8080
```

`.env` 가 로컬 설정입니다 (`app.baseURL`, `database.default.*`).
Laragon Apache 로 띄울 때는 `C:/laragon/www/booktreeEdu/public` 을 DocumentRoot 로 잡으면 됩니다
(Laragon 은 `public` 폴더를 자동 인식해 `booktreeedu.test` 가상호스트를 만듭니다).

## 운영 서버(카페24) 배포

`main` 브랜치에 푸시하면 GitHub Actions 가 **FTP 로 자동 배포**합니다 (`.github/workflows/deploy.yml`).
GitHub 저장소 secret 등록, 서버 첫 설정(PHP 8.2+, DB 가져오기, `.env`, 폴더 권한), 확인 방법은
**[deploy/README_배포.md](deploy/README_배포.md)** 를 보세요.

- DB 초기 스키마 + 데모 데이터: `deploy/booktreeedu.sql` (phpMyAdmin 으로 가져오기)
- 서버 `.env` 견본: `env.production.example`
- 루트의 `.htaccess` 와 `index.php` 는 카페24처럼 `/www` 가 프로젝트 루트일 때 `public/` 만 공개되게 하는 파일입니다.

## 영상 등록 방식 (관리자 > 교육과정 > 강의 등록)

| 방식 | 설명 |
|------|------|
| 📁 영상 파일 업로드 | MP4/WebM/MOV 를 서버 `public/uploads/videos/` 에 저장. HTML5 플레이어, 재생 위치 자동 저장 |
| ▶ 유튜브 | 주소 또는 영상 ID 입력. 유튜브 IFrame API 로 시청 완료 감지 |
| 🔗 외부 URL | 다른 서버/CDN 의 MP4 주소 |

영상을 95% 이상 또는 끝까지 보면 **시청 완료** 로 처리되고 시험 응시가 열립니다
(강의별 "영상 시청 완료 후에만 시험 응시 가능" 옵션으로 해제 가능).

## 구조

```
app/
  Config/Routes.php          라우트
  Filters/AuthFilter.php     로그인 필터  (academy/*, admin/*)
  Filters/AdminFilter.php    관리자 필터  (admin/*)
  Controllers/Home.php       홍보 메인
  Controllers/Auth.php       로그인/로그아웃
  Controllers/Academy.php    수강생 강의실 (영상 진도, 시험 채점)
  Controllers/Admin/*.php    관리자
  Models/*.php               users, courses, sections, lessons, questions, progress, quiz_attempts
  Database/Migrations/       테이블 정의
  Database/Seeds/DemoSeeder  데모 데이터
  Helpers/app_helper.php     youtube_id(), video_src(), lesson_status_label() 등
  Views/                     layouts(public/app/admin), home, auth, academy, admin
public/
  assets/css/app.css         전체 스타일
  assets/js/player.js        영상 진도 추적 (HTML5 + YouTube)
  uploads/videos/            업로드된 강의 영상
legacy_ci3/                  기존 CI3 프로토타입 (참고용, 서비스에 사용되지 않음)
```

## 데모 데이터 초기화

```bash
mysql -uroot booktreeedu -e "TRUNCATE users; TRUNCATE courses; TRUNCATE sections; TRUNCATE lessons; TRUNCATE questions; TRUNCATE progress; TRUNCATE quiz_attempts;"
php spark db:seed DemoSeeder
```
