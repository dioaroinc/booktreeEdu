<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (is_logged_in()) {
            return redirect()->to('/academy');
        }

        return view('auth/login');
    }

    public function attempt(): \CodeIgniter\HTTP\RedirectResponse
    {
        $loginId  = trim((string) $this->request->getPost('login_id'));
        $password = (string) $this->request->getPost('password');

        if ($loginId === '' || $password === '') {
            return redirect()->back()->withInput()->with('error', '아이디와 비밀번호를 입력하세요.');
        }

        $users = model(UserModel::class);
        $user  = $users->authenticate($loginId, $password) ?? $users->authenticateRemote($loginId, $password);

        if (! $user) {
            return redirect()->back()->withInput()->with('error', '아이디 또는 비밀번호를 확인하시기 바랍니다.');
        }

        $users->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        $session = session();
        $session->regenerate(true);
        $session->set([
            'user_id'     => (int) $user['id'],
            'user_name'   => $user['name'],
            'user_type'   => (int) $user['type'],
            'user_branch' => $user['branch_name'],
        ]);

        $redirect = $session->get('redirect_to');
        $session->remove('redirect_to');

        if ((int) $user['type'] === USER_TYPE_ADMIN && ! $redirect) {
            return redirect()->to('/admin');
        }

        return redirect()->to($redirect ?: '/academy');
    }

    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        $session = session();
        $session->remove(['user_id', 'user_name', 'user_type', 'user_branch', 'redirect_to']);
        $session->regenerate(true);

        return redirect()->to('/')->with('success', '로그아웃 되었습니다.');
    }

    public function find(): string
    {
        return view('auth/find');
    }
}
