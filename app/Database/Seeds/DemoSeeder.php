<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * 데모 데이터: 관리자/수강생 계정, 교육과정 3종, 데모 영상 강의, 데모 퀴즈
 *
 *   php spark db:seed DemoSeeder
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // ── 회원 ───────────────────────────────────────────
        $this->db->table('users')->insertBatch([
            [
                'login_id'    => 'admin',
                'password'    => password_hash('admin1234', PASSWORD_DEFAULT),
                'name'        => '본사 관리자',
                'branch_name' => '책나무 본사',
                'type'        => USER_TYPE_ADMIN,
                'status'      => 1,
                'source'      => 'local',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'login_id'    => 'demo',
                'password'    => password_hash('demo1234', PASSWORD_DEFAULT),
                'name'        => '홍길동',
                'branch_name' => '책나무 서울송파잠실점',
                'type'        => USER_TYPE_STUDENT,
                'status'      => 1,
                'source'      => 'local',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'login_id'    => 'demo2',
                'password'    => password_hash('demo1234', PASSWORD_DEFAULT),
                'name'        => '김영희',
                'branch_name' => '책나무 분당정자점',
                'type'        => USER_TYPE_STUDENT,
                'status'      => 1,
                'source'      => 'local',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);

        // ── 교육과정 ───────────────────────────────────────
        $courses = [
            [
                'title'       => '신규원장교육',
                'subtitle'    => '책나무 가맹 원장님을 위한 필수 기본 과정',
                'description' => "책나무 독서교육 프로그램의 철학과 운영 방법, 홈페이지 메인 프로그램 사용법, 수강생 관리와 주문·결제 절차까지 신규 원장님이 반드시 알아야 할 내용을 단계별로 안내합니다.",
                'icon'        => '🌱',
                'color'       => '#136a43',
                'sort_order'  => 1,
            ],
            [
                'title'       => '지점SW 사용교육',
                'subtitle'    => '지점 지원 소프트웨어 완전 정복',
                'description' => "수강생 등록부터 진단테스트, BToS(독서 퀴즈 온라인 시스템), 북히스토리 관리까지 지점 소프트웨어의 모든 기능을 화면과 함께 배웁니다.",
                'icon'        => '💻',
                'color'       => '#0f88ca',
                'sort_order'  => 2,
            ],
            [
                'title'       => '보수교육',
                'subtitle'    => '운영 중인 원장님을 위한 심화·업데이트 교육',
                'description' => "매년 업데이트되는 교재와 프로그램 변경 사항, 학부모 상담 노하우, 지점 운영 사례를 공유하는 심화 과정입니다.",
                'icon'        => '📚',
                'color'       => '#b5651d',
                'sort_order'  => 3,
            ],
        ];

        $courseIds = [];
        foreach ($courses as $course) {
            $course['is_active']  = 1;
            $course['created_at'] = $now;
            $course['updated_at'] = $now;
            $this->db->table('courses')->insert($course);
            $courseIds[] = $this->db->insertID();
        }
        [$c1, $c2, $c3] = $courseIds;

        // ── 단원 ───────────────────────────────────────────
        $sectionIds = [];
        $sections   = [
            [$c1, '1. 홈페이지 메인 프로그램 Part 1', 1],
            [$c1, '2. 주문 및 결제 안내', 2],
            [$c2, '1. 지점SW 시작하기', 1],
            [$c3, '1. 2026년 프로그램 업데이트', 1],
        ];
        foreach ($sections as [$courseId, $title, $order]) {
            $this->db->table('sections')->insert([
                'course_id'  => $courseId,
                'title'      => $title,
                'sort_order' => $order,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $sectionIds[] = $this->db->insertID();
        }
        [$s1, $s2, $s3, $s4] = $sectionIds;

        // ── 강의 (데모 영상) ───────────────────────────────
        // 데모 1: 유튜브 임베드 (기존 프로토타입에 들어있던 영상)
        // 데모 2: 외부 MP4 URL (MDN CC0 샘플) → HTML5 플레이어 + 진도 추적 데모
        // 실제 운영 시에는 관리자 > 강의 등록에서 MP4 파일을 직접 업로드하면 됩니다.
        $lessons = [
            [
                'course_id'    => $c1,
                'section_id'   => $s1,
                'title'        => '접속방법, 메인프로그램 소개, 진단평가',
                'description'  => "[데모 영상] 책나무 홈페이지 접속 방법과 메인 프로그램의 구성, 학년별 진단 TEST 의 의미를 소개합니다.\n\n이 강의는 유튜브 영상 임베드 방식의 데모입니다. 영상을 끝까지 시청하면 자동으로 시청완료 처리되고 시험을 볼 수 있습니다.",
                'video_type'   => VIDEO_TYPE_YOUTUBE,
                'video_url'    => 'https://www.youtube.com/watch?v=5cJ-tHkI4MA',
                'duration_min' => 10,
                'pass_score'   => 80,
                'sort_order'   => 1,
            ],
            [
                'course_id'    => $c1,
                'section_id'   => $s1,
                'title'        => '수강생 관리, 활동지 및 북히스토리',
                'description'  => "[데모 영상] 수강생 등록·반 편성, 활동지 출력, 북히스토리 기록 방법을 안내합니다.\n\n이 강의는 MP4 파일 재생 방식의 데모입니다 (직접 업로드한 파일과 동일하게 동작). 재생 위치가 자동 저장되어 다음에 이어서 볼 수 있습니다.",
                'video_type'   => VIDEO_TYPE_URL,
                'video_url'    => 'https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.mp4',
                'duration_min' => 1,
                'pass_score'   => 80,
                'sort_order'   => 2,
            ],
            [
                'course_id'    => $c1,
                'section_id'   => $s1,
                'title'        => '독서 프로그램 운영과 학부모 상담',
                'description'  => "개별 맞춤 독서 프로그램의 운영 원리와 학부모 상담 시 활용하는 진단 결과 해석법을 다룹니다.",
                'video_type'   => VIDEO_TYPE_URL,
                'video_url'    => 'https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                'duration_min' => 1,
                'pass_score'   => 80,
                'sort_order'   => 3,
            ],
            [
                'course_id'    => $c1,
                'section_id'   => $s2,
                'title'        => '교재 주문과 결제 절차',
                'description'  => "교재·교구 주문 방법, 결제 수단, 배송 일정과 반품 규정을 안내합니다.",
                'video_type'   => VIDEO_TYPE_URL,
                'video_url'    => 'https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                'duration_min' => 1,
                'pass_score'   => 80,
                'sort_order'   => 4,
            ],
            [
                'course_id'    => $c2,
                'section_id'   => $s3,
                'title'        => '지점SW 로그인과 기본 화면 구성',
                'description'  => "지점 지원 소프트웨어 로그인, 메뉴 구조, 자주 쓰는 기능의 위치를 익힙니다.",
                'video_type'   => VIDEO_TYPE_URL,
                'video_url'    => 'https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                'duration_min' => 1,
                'pass_score'   => 80,
                'sort_order'   => 1,
            ],
            [
                'course_id'    => $c2,
                'section_id'   => $s3,
                'title'        => 'BToS 독서 퀴즈 온라인 시스템 활용',
                'description'  => "BToS(Booktree Test online System) 에서 독서 퀴즈를 배정하고 결과를 확인하는 방법을 배웁니다.",
                'video_type'   => VIDEO_TYPE_URL,
                'video_url'    => 'https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
                'duration_min' => 1,
                'pass_score'   => 80,
                'sort_order'   => 2,
            ],
            [
                'course_id'    => $c3,
                'section_id'   => $s4,
                'title'        => '2026년 교재 개정 사항 안내',
                'description'  => "개정 교재의 변경 포인트와 수업 적용 방법을 안내합니다.",
                'video_type'   => VIDEO_TYPE_URL,
                'video_url'    => 'https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
                'duration_min' => 1,
                'pass_score'   => 80,
                'sort_order'   => 1,
            ],
        ];

        $lessonIds = [];
        foreach ($lessons as $lesson) {
            $lesson['require_video'] = 1;
            $lesson['is_active']     = 1;
            $lesson['created_at']    = $now;
            $lesson['updated_at']    = $now;
            $this->db->table('lessons')->insert($lesson);
            $lessonIds[] = $this->db->insertID();
        }

        // ── 데모 퀴즈 ──────────────────────────────────────
        $quiz = [
            // 제1강
            $lessonIds[0] => [
                ['책나무 독서교육에서 "독서"의 의미로 가장 알맞은 것은?', '책을 빠르게 많이 읽는 것', '읽고, 생각하고, 쓰는 모든 과정', '줄거리를 외우는 것', '독후감을 제출하는 것', 2, '책나무는 읽기만이 아니라 읽고·생각하고·쓰는 전 과정을 독서로 봅니다.'],
                ['학년별 진단 TEST 의 주된 목적은 무엇인가요?', '학생의 등수를 매기기 위해', '개별 맞춤 독서 프로그램을 설계하기 위해', '교재 판매량을 늘리기 위해', '수업 시간을 줄이기 위해', 2, '진단 결과를 바탕으로 학생별 수준에 맞는 프로그램을 구성합니다.'],
                ['책나무 홈페이지 메인 프로그램에 접속할 때 사용하는 계정은?', '개인 이메일 계정', '지점(가맹점) 계정', '학생 계정', '학부모 계정', 2, '지점SW 와 동일한 가맹점 계정으로 로그인합니다.'],
                ['BToS 는 무엇의 약자인가요?', 'Booktree Teacher online School', 'Booktree Test online System', 'Best Textbook of Study', 'Book Trading online Service', 2, 'BToS = Booktree Test online System (독서 퀴즈 온라인 프로그램)'],
                ['진단 TEST 결과지에 포함되지 않는 항목은?', '영역별 점수', '학년 수준 비교', '학생의 혈액형', '추천 독서 단계', 3, '결과지는 영역별 점수·수준 비교·추천 단계를 제공합니다.'],
            ],
            // 제2강
            $lessonIds[1] => [
                ['수강생을 새로 등록할 때 가장 먼저 해야 할 일은?', '교재를 먼저 주문한다', '진단 TEST 를 실시한다', '학부모 상담을 생략한다', '활동지를 먼저 출력한다', 2, '등록 → 진단 TEST → 프로그램 배정 순서로 진행합니다.'],
                ['북히스토리에 기록되는 내용으로 알맞은 것은?', '학생이 읽은 도서와 활동 기록', '지점의 매출 정보', '교사의 출퇴근 기록', '학부모의 연락처', 1, '북히스토리는 학생의 독서·활동 이력을 누적 관리합니다.'],
                ['활동지는 어디에서 출력하나요?', '지점SW 의 활동지 메뉴', '학생의 개인 이메일', '본사에 전화 요청', '출력할 수 없다', 1, '지점SW 활동지 메뉴에서 학생별로 출력합니다.'],
                ['다양한 글쓰기 활동의 목적으로 가장 알맞은 것은?', '글씨체 교정', '읽은 내용을 생각하고 표현하는 힘 기르기', '시험 점수 올리기', '시간 채우기', 2, '글쓰기는 독서의 완성 단계로, 사고력과 표현력을 기릅니다.'],
                ['도서관식 몰입독서 환경의 특징은?', '모든 학생이 같은 책을 읽는다', '학생 개별 수준의 책을 스스로 선택해 몰입한다', '교사가 책을 대신 읽어준다', '독서 시간이 없다', 2, '개별 맞춤 + 몰입독서 환경이 책나무 프로그램의 핵심입니다.'],
            ],
            // 제3강
            $lessonIds[2] => [
                ['학부모 상담에서 진단 결과를 설명할 때 가장 중요한 태도는?', '점수만 강조한다', '다른 학생과 비교한다', '강점과 보완점을 함께 설명한다', '결과를 보여주지 않는다', 3, '강점·보완점을 균형 있게 전달해야 신뢰를 얻습니다.'],
                ['개별 맞춤 독서 프로그램 설계의 기준은?', '학생의 진단 결과와 독서 수준', '교재 재고', '학부모의 희망 학년', '교사의 취향', 1, '진단 결과에 따른 수준별 설계가 원칙입니다.'],
                ['정기 상담의 적절한 주기로 권장되는 것은?', '매일', '학기별 또는 분기별', '졸업할 때 한 번', '상담하지 않는다', 2, '학기·분기 단위 정기 상담을 권장합니다.'],
            ],
            // 제4강
            $lessonIds[3] => [
                ['교재 주문은 어디에서 진행하나요?', '지점SW 주문 메뉴', '개인 온라인 서점', '학부모가 직접', '본사 방문만 가능', 1, '지점SW 주문 메뉴에서 교재·교구를 주문합니다.'],
                ['주문 후 결제 확인은 어떻게 하나요?', '주문 내역의 결제 상태 확인', '확인할 수 없다', '학생에게 묻는다', '본사에 매번 전화', 1, '주문 내역 화면에서 결제·배송 상태를 확인합니다.'],
                ['반품이 가능한 경우는?', '사용한 교재', '파손·오배송된 미사용 교재', '한 학기 지난 교재', '모든 경우 불가', 2, '미사용 상태의 파손·오배송 교재는 반품 규정에 따라 처리됩니다.'],
            ],
            // 지점SW 1강
            $lessonIds[4] => [
                ['지점SW 로그인에 사용하는 계정은?', '가맹점 계정', '학생 계정', '학부모 계정', '본사 공용 계정', 1, '가맹점(지점) 계정으로 로그인합니다.'],
                ['지점SW 에서 수강생을 관리하는 메뉴는?', '수강생 관리', '매출 관리', '교재 주문', '공지사항', 1, '수강생 관리 메뉴에서 등록·반 편성·이력을 관리합니다.'],
                ['비밀번호를 잊었을 때는?', '본사(1522-9320)에 문의', '새 계정을 만든다', '다른 지점 계정을 쓴다', '포기한다', 1, '로그인 관련 문의는 본사로 연락합니다.'],
            ],
            // 지점SW 2강
            $lessonIds[5] => [
                ['BToS 에서 독서 퀴즈를 배정하는 단위는?', '학생별 도서', '지점 전체', '학부모', '교사', 1, '학생이 읽은 도서별로 퀴즈를 배정합니다.'],
                ['퀴즈 결과는 어디에 누적되나요?', '북히스토리', '매출 장부', '교재 주문서', '누적되지 않는다', 1, '퀴즈 결과는 학생의 북히스토리에 기록됩니다.'],
                ['BToS 의 장점으로 알맞은 것은?', '독서 확인과 결과 관리를 온라인으로 간편하게', '종이 시험지만 사용', '교사가 채점을 직접 해야 함', '결과를 볼 수 없음', 1, '온라인 퀴즈로 독서 확인·채점·결과 관리가 자동화됩니다.'],
            ],
            // 보수교육 1강
            $lessonIds[6] => [
                ['개정 교재를 수업에 적용할 때 먼저 확인할 것은?', '변경된 단원 구성', '교재의 표지 색', '가격표', '아무것도 확인하지 않는다', 1, '변경 단원과 활동 구성을 먼저 파악합니다.'],
                ['보수교육의 목적은?', '운영 중인 원장님의 지식 업데이트', '신규 가맹 안내', '학생 평가', '교재 판매', 1, '운영 노하우와 변경 사항을 공유하는 심화 과정입니다.'],
            ],
        ];

        foreach ($quiz as $lessonId => $rows) {
            $order = 1;
            foreach ($rows as [$q, $a, $b, $c, $d, $answer, $explanation]) {
                $this->db->table('questions')->insert([
                    'lesson_id'   => $lessonId,
                    'question'    => $q,
                    'choice1'     => $a,
                    'choice2'     => $b,
                    'choice3'     => $c,
                    'choice4'     => $d,
                    'answer'      => $answer,
                    'explanation' => $explanation,
                    'sort_order'  => $order++,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }

        // ── 데모 수강 이력 (demo 계정: 제1강 통과, 제2강 시청중) ──
        $demoUser = $this->db->table('users')->where('login_id', 'demo')->get()->getRowArray();
        if ($demoUser) {
            $this->db->table('progress')->insertBatch([
                [
                    'user_id'        => $demoUser['id'],
                    'lesson_id'      => $lessonIds[0],
                    'video_status'   => VIDEO_DONE,
                    'video_seconds'  => 600,
                    'video_done_at'  => $now,
                    'quiz_attempts'  => 1,
                    'quiz_best'      => 100,
                    'quiz_passed'    => 1,
                    'quiz_passed_at' => $now,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ],
                [
                    'user_id'        => $demoUser['id'],
                    'lesson_id'      => $lessonIds[1],
                    'video_status'   => VIDEO_STARTED,
                    'video_seconds'  => 8,
                    'video_done_at'  => null,
                    'quiz_attempts'  => 0,
                    'quiz_best'      => 0,
                    'quiz_passed'    => 0,
                    'quiz_passed_at' => null,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ],
            ]);
            $this->db->table('quiz_attempts')->insert([
                'user_id'    => $demoUser['id'],
                'lesson_id'  => $lessonIds[0],
                'score'      => 100,
                'correct'    => 5,
                'total'      => 5,
                'passed'     => 1,
                'answers'    => json_encode([]),
                'created_at' => $now,
            ]);
        }
    }
}
