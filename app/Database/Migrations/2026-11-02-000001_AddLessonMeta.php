<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * 강의에 본사 자료실 번호 / 강연자 / 교육 날짜 / 필수 시청 여부 추가
 */
class AddLessonMeta extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('lessons', [
            'ref_no'       => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'after' => 'description', 'comment' => '본사 자료실(bbs) 글 번호'],
            'lecturer'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'ref_no'],
            'lecture_date' => ['type' => 'DATE', 'null' => true, 'after' => 'lecturer'],
            'is_required'  => ['type' => 'TINYINT', 'default' => 0, 'after' => 'lecture_date', 'comment' => '1=신규 원장 필수 시청(★)'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('lessons', ['ref_no', 'lecturer', 'lecture_date', 'is_required']);
    }
}
