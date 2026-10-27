<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'login_id', 'password', 'name', 'branch_name', 'phone', 'email',
        'type', 'status', 'source', 'last_login_at',
    ];
    protected $useTimestamps = true;

    public function findByLoginId(string $loginId): ?array
    {
        return $this->where('login_id', $loginId)->first();
    }

    /**
     * 로컬 계정 인증. 성공 시 회원 배열, 실패 시 null
     */
    public function authenticate(string $loginId, string $password): ?array
    {
        $user = $this->findByLoginId($loginId);
        if (! $user || (int) $user['status'] !== 1) {
            return null;
        }

        if (! password_verify($password, $user['password'])) {
            return null;
        }

        return $user;
    }

    /**
     * 책나무 본사 DB(user 테이블) 계정으로 인증 후 로컬 users 에 동기화
     */
    public function authenticateRemote(string $loginId, string $password): ?array
    {
        if (env('booktree.remoteAuth') !== 'true' && env('booktree.remoteAuth') !== true) {
            return null;
        }

        try {
            $remote = \Config\Database::connect('booktree');
            $row    = $remote->table('user')
                ->select('num, id, name, type, status, phone, email')
                ->where('id', $loginId)
                ->where('password', $password)
                ->get()
                ->getRowArray();
        } catch (\Throwable $e) {
            log_message('error', '책나무 본사 DB 연결 실패: ' . $e->getMessage());

            return null;
        }

        if (! $row || (int) $row['status'] !== 1) {
            return null;
        }

        $data = [
            'login_id'    => $row['id'],
            'password'    => password_hash($password, PASSWORD_DEFAULT),
            'name'        => $row['name'],
            'branch_name' => $row['name'],
            'phone'       => $row['phone'] ?? null,
            'email'       => $row['email'] ?? null,
            'type'        => (int) $row['type'] === 100 ? USER_TYPE_ADMIN : USER_TYPE_STUDENT,
            'status'      => 1,
            'source'      => 'booktree',
        ];

        $local = $this->findByLoginId($loginId);
        if ($local) {
            $this->update($local['id'], $data);

            return $this->find($local['id']);
        }

        $id = $this->insert($data);

        return $this->find($id);
    }
}
