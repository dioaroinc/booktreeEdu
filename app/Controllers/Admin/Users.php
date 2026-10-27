<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\LessonModel;
use App\Models\ProgressModel;
use App\Models\QuizAttemptModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Users extends BaseController
{
    public function index(): string
    {
        $keyword = trim((string) $this->request->getGet('q'));
        $model   = model(UserModel::class);

        if ($keyword !== '') {
            $model->groupStart()
                ->like('name', $keyword)
                ->orLike('login_id', $keyword)
                ->orLike('branch_name', $keyword)
                ->groupEnd();
        }

        return view('admin/users/index', [
            'title'   => '회원 관리',
            'users'   => $model->orderBy('type', 'DESC')->orderBy('name')->findAll(),
            'keyword' => $keyword,
        ]);
    }

    public function form(int $id = 0): string
    {
        $user = $id ? model(UserModel::class)->find($id) : null;
        if ($id && ! $user) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('admin/users/form', [
            'title' => $user ? '회원 수정' : '회원 등록',
            'user'  => $user ?? [
                'id' => 0, 'login_id' => '', 'name' => '', 'branch_name' => '', 'phone' => '', 'email' => '',
                'type' => USER_TYPE_STUDENT, 'status' => 1, 'source' => 'local',
            ],
        ]);
    }

    public function save(): RedirectResponse
    {
        $id    = (int) $this->request->getPost('id');
        $rules = [
            'login_id' => 'required|alpha_numeric_punct|max_length[50]|is_unique[users.login_id,id,' . $id . ']',
            'name'     => 'required|max_length[100]',
            'password' => $id ? 'permit_empty|min_length[4]' : 'required|min_length[4]',
            'type'     => 'required|in_list[1,100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = [
            'login_id'    => trim($this->request->getPost('login_id')),
            'name'        => trim($this->request->getPost('name')),
            'branch_name' => trim((string) $this->request->getPost('branch_name')),
            'phone'       => trim((string) $this->request->getPost('phone')),
            'email'       => trim((string) $this->request->getPost('email')),
            'type'        => (int) $this->request->getPost('type'),
            'status'      => $this->request->getPost('status') ? 1 : 0,
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model = model(UserModel::class);
        if ($id) {
            $model->update($id, $data);
        } else {
            $data['source'] = 'local';
            $model->insert($data);
        }

        return redirect()->to('/admin/users')->with('success', '회원 정보가 저장되었습니다.');
    }

    public function delete(int $id): RedirectResponse
    {
        if ($id === current_user_id()) {
            return redirect()->to('/admin/users')->with('error', '로그인 중인 본인 계정은 삭제할 수 없습니다.');
        }

        model(ProgressModel::class)->where('user_id', $id)->delete();
        model(QuizAttemptModel::class)->where('user_id', $id)->delete();
        model(UserModel::class)->delete($id);

        return redirect()->to('/admin/users')->with('success', '회원이 삭제되었습니다.');
    }

    /**
     * 회원별 수강 현황
     */
    public function progress(int $id): string
    {
        $user = model(UserModel::class)->find($id);
        if (! $user) {
            throw PageNotFoundException::forPageNotFound();
        }

        $progressMap = model(ProgressModel::class)->mapByUser($id);
        $courses     = model(CourseModel::class)->activeList();
        $lessonModel = model(LessonModel::class);

        foreach ($courses as &$course) {
            $course['lessons'] = $lessonModel->byCourse((int) $course['id']);
            foreach ($course['lessons'] as &$lesson) {
                $lesson['progress'] = $progressMap[(int) $lesson['id']] ?? null;
            }
            unset($lesson);
        }
        unset($course);

        return view('admin/users/progress', [
            'title'    => $user['name'] . ' - 수강 현황',
            'user'     => $user,
            'courses'  => $courses,
            'summary'  => model(ProgressModel::class)->summaryForUser($id),
            'attempts' => model(QuizAttemptModel::class)->where('user_id', $id)->orderBy('id', 'DESC')->findAll(30),
        ]);
    }

    /**
     * 전체 수강 현황
     */
    public function overview(): string
    {
        return view('admin/users/overview', [
            'title'    => '전체 수강 현황',
            'rows'     => model(ProgressModel::class)->overview(),
            'total'    => model(LessonModel::class)->countActive(),
        ]);
    }
}
