# 카페24 배포 순서 (FTP 자동 배포)

GitHub `main` 브랜치에 푸시하면 GitHub Actions 가 **FTP 로 카페24 `/www` 에 자동 배포**합니다.
(`.github/workflows/deploy.yml`) 서버에서 바뀌면 안 되는 것(`.env`, 업로드된 영상, 로그)은 배포가 건드리지 않습니다.

```
/www
 ├─ .env            ← 서버에서 한 번만 직접 만든다 (배포가 덮어쓰지 않음)
 ├─ .htaccess       ← 웹에서 public/ 만 열리게 하는 파일
 ├─ index.php       ← 카페24 가 /www 에서 먼저 읽는 시작 파일 (public/index.php 로 넘겨줌)
 ├─ app/
 ├─ vendor/         ← Actions 가 composer install 해서 올림
 ├─ writable/
 └─ public/         ← 실제로 웹에 공개되는 곳 (assets, uploads/videos)
```

## 1. GitHub 저장소 secret 등록 (처음 한 번)

GitHub 저장소 > **Settings > Secrets and variables > Actions > New repository secret**

| 이름 | 값 |
|------|----|
| `FTP_HOST` | 카페24 FTP 주소 (예: `아이디.cafe24.com`) |
| `FTP_USERNAME` | 카페24 FTP 아이디 |
| `FTP_PASSWORD` | 카페24 FTP 비밀번호 |
| `FTP_SERVER_DIR` | `/www/` (끝에 `/` 필수) |

등록 후 `main` 에 푸시하거나, GitHub > **Actions > Deploy to cafe24 (FTP) > Run workflow** 로 수동 실행합니다.
첫 배포는 vendor 까지 올라가서 수 분 걸립니다. 이후에는 바뀐 파일만 올라갑니다.

## 2. 서버 준비 (처음 한 번, 카페24 관리자 / FTP / phpMyAdmin)

1. **PHP 8.2 이상**으로 설정 (호스팅 관리 > PHP 버전). 확장 `intl`, `mbstring`, `mysqli`, `curl` 필요.
2. **DB 만들기**: phpMyAdmin 에서 `deploy/booktreeedu.sql` 을 가져오기(import).
   - 테이블 7개 + 데모 데이터(관리자 `admin / admin1234`, 수강생 `demo / demo1234`)가 들어갑니다.
   - **가져온 뒤 관리자 비밀번호를 꼭 바꾸세요** (관리자 > 회원 관리).
3. **`.env` 만들기**: `env.production.example` 내용을 복사해 `/www/.env` 로 올리고 수정
   - `app.baseURL` = 실제 주소 (끝에 `/`)
   - `database.default.*` = 카페24 DB 정보
   - `booktree.remoteAuth = true` 이면 지점SW 계정으로도 로그인 가능 (본사 DB 외부접속 허용 IP 에 이 서버 IP 추가 필요). 필요 없으면 `false`.
   - FTP 프로그램의 "숨김 파일 표시"를 켜야 `.env`, `.htaccess` 가 보입니다.
4. **쓰기 권한**: FTP 에서 아래 폴더를 **755** (안 되면 **777**)
   - `writable` 과 하위 폴더 전부 (cache, logs, session, uploads, debugbar)
   - `public/uploads`, `public/uploads/videos`
5. **영상 업로드 용량**: 호스팅 관리에서 `upload_max_filesize`, `post_max_size`, `max_execution_time` 을 늘립니다 (예: 1G / 1G / 600).
   카페24에서 조정이 안 되면 영상은 유튜브(미등록)에 올리고 주소만 입력하세요.

## 3. 확인

**열려야 하는 주소**
1. `https://주소/` → 홍보 메인 페이지
2. `https://주소/login` → 로그인 → 강의실 / 관리자
3. 관리자 > 교육과정 > 강의 등록에서 MP4 업로드 → 수강생 화면에서 재생

**막혀야 하는 주소 (403/404 가 정상, 내용이 보이면 안 됩니다)**
- `https://주소/.env`
- `https://주소/app/Config/App.php`
- `https://주소/vendor/autoload.php`
- `https://주소/writable/logs/index.html`

**오류가 나면** `writable/logs/log-날짜.log` 를 열어보면 원인이 적혀 있습니다.
- `Unable to connect` / `Access denied` → `.env` 의 DB 정보 확인
- `Class "..." not found` → PHP 버전(8.2+), `intl` 확장
- 모든 주소가 500 → 서버가 `.htaccess` 를 거부하는 경우. 알려주세요.
- 배포 자체가 실패하면 GitHub > Actions 의 로그에서 FTP 접속 오류(secret 오타, 서버 폴더)를 확인합니다.

## 4. DB 구조가 바뀌었을 때

카페24에는 SSH 가 없어 `php spark migrate` 를 못 돌립니다.
로컬에서 마이그레이션 후 아래로 새 덤프를 만들어 phpMyAdmin 으로 반영하거나, 변경된 ALTER 문만 실행합니다.

```bash
mysqldump -uroot --no-data --skip-comments --add-drop-table booktreeedu > deploy/booktreeedu.sql
```

(`--no-data` 를 빼면 데모 데이터까지 포함됩니다. 운영 DB 에 데이터가 있는 상태에서는 구조만 반영하세요.)
