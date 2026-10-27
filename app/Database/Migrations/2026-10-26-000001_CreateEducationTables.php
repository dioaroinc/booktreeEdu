<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * 책나무 온라인 교육 기본 테이블
 *
 *  users          회원(수강생 / 관리자)
 *  courses        교육과정 (신규원장교육, 지점SW 사용교육, 보수교육 …)
 *  sections       과정 내 단원(챕터)
 *  lessons        강의 (영상 + 시험)
 *  questions      강의별 시험 문항 (4지선다)
 *  progress       회원별 강의 진도/시험 결과 요약
 *  quiz_attempts  시험 응시 기록
 */
class CreateEducationTables extends Migration
{
    public function up(): void
    {
        // users
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'login_id'    => ['type' => 'VARCHAR', 'constraint' => 50],
            'password'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'branch_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'phone'       => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'email'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'type'        => ['type' => 'INT', 'default' => 1, 'comment' => '1=수강생, 100=관리자'],
            'status'      => ['type' => 'TINYINT', 'default' => 1, 'comment' => '1=사용, 0=정지'],
            'source'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'local', 'comment' => 'local | booktree'],
            'last_login_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('login_id');
        $this->forge->createTable('users');

        // courses
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'subtitle'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'icon'        => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => '📘'],
            'color'       => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => '#136a43'],
            'sort_order'  => ['type' => 'INT', 'default' => 0],
            'is_active'   => ['type' => 'TINYINT', 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('courses');

        // sections
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'course_id'  => ['type' => 'INT', 'unsigned' => true],
            'title'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'sort_order' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('course_id');
        $this->forge->createTable('sections');

        // lessons
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'course_id'    => ['type' => 'INT', 'unsigned' => true],
            'section_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'title'        => ['type' => 'VARCHAR', 'constraint' => 200],
            'description'  => ['type' => 'TEXT', 'null' => true],
            'video_type'   => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'youtube', 'comment' => 'youtube | file | url'],
            'video_url'    => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'video_file'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'duration_min' => ['type' => 'INT', 'default' => 0],
            'pass_score'   => ['type' => 'INT', 'default' => 80, 'comment' => '합격 기준(%)'],
            'require_video' => ['type' => 'TINYINT', 'default' => 1, 'comment' => '영상 시청 완료 후 시험 응시 가능'],
            'sort_order'   => ['type' => 'INT', 'default' => 0],
            'is_active'    => ['type' => 'TINYINT', 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('course_id');
        $this->forge->addKey('section_id');
        $this->forge->createTable('lessons');

        // questions
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'lesson_id'   => ['type' => 'INT', 'unsigned' => true],
            'question'    => ['type' => 'TEXT'],
            'choice1'     => ['type' => 'VARCHAR', 'constraint' => 500],
            'choice2'     => ['type' => 'VARCHAR', 'constraint' => 500],
            'choice3'     => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'choice4'     => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'answer'      => ['type' => 'TINYINT', 'comment' => '정답 번호 1~4'],
            'explanation' => ['type' => 'TEXT', 'null' => true],
            'sort_order'  => ['type' => 'INT', 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('lesson_id');
        $this->forge->createTable('questions');

        // progress
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'unsigned' => true],
            'lesson_id'      => ['type' => 'INT', 'unsigned' => true],
            'video_status'   => ['type' => 'TINYINT', 'default' => 0, 'comment' => '0=미시청 1=시청중 2=시청완료'],
            'video_seconds'  => ['type' => 'INT', 'default' => 0, 'comment' => '마지막 재생 위치(초)'],
            'video_done_at'  => ['type' => 'DATETIME', 'null' => true],
            'quiz_attempts'  => ['type' => 'INT', 'default' => 0],
            'quiz_best'      => ['type' => 'INT', 'default' => 0, 'comment' => '최고 점수(%)'],
            'quiz_passed'    => ['type' => 'TINYINT', 'default' => 0],
            'quiz_passed_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'lesson_id']);
        $this->forge->createTable('progress');

        // quiz_attempts
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true],
            'lesson_id'  => ['type' => 'INT', 'unsigned' => true],
            'score'      => ['type' => 'INT', 'comment' => '점수(%)'],
            'correct'    => ['type' => 'INT'],
            'total'      => ['type' => 'INT'],
            'passed'     => ['type' => 'TINYINT', 'default' => 0],
            'answers'    => ['type' => 'TEXT', 'null' => true, 'comment' => 'JSON {question_id: choice}'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'lesson_id']);
        $this->forge->createTable('quiz_attempts');
    }

    public function down(): void
    {
        foreach (['quiz_attempts', 'progress', 'questions', 'lessons', 'sections', 'courses', 'users'] as $table) {
            $this->forge->dropTable($table, true);
        }
    }
}
