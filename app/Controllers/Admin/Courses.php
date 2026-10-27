<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\LessonModel;
use App\Models\SectionModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Courses extends BaseController
{
    public function index(): string
    {
        return view('admin/courses/index', [
            'title'   => '교육과정 관리',
            'courses' => model(CourseModel::class)->listWithCounts(),
        ]);
    }

    public function form(int $id = 0): string
    {
        $course = $id ? model(CourseModel::class)->find($id) : null;
        if ($id && ! $course) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('admin/courses/form', [
            'title'  => $course ? '교육과정 수정' : '교육과정 등록',
            'course' => $course ?? [
                'id' => 0, 'title' => '', 'subtitle' => '', 'description' => '',
                'icon' => '📘', 'color' => '#136a43', 'sort_order' => 0, 'is_active' => 1,
            ],
        ]);
    }

    public function save(): RedirectResponse
    {
        $rules = [
            'title'      => 'required|max_length[150]',
            'subtitle'   => 'permit_empty|max_length[255]',
            'icon'       => 'permit_empty|max_length[20]',
            'color'      => 'permit_empty|max_length[20]',
            'sort_order' => 'permit_empty|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $id   = (int) $this->request->getPost('id');
        $data = [
            'title'       => trim($this->request->getPost('title')),
            'subtitle'    => trim((string) $this->request->getPost('subtitle')),
            'description' => trim((string) $this->request->getPost('description')),
            'icon'        => trim((string) $this->request->getPost('icon')) ?: '📘',
            'color'       => trim((string) $this->request->getPost('color')) ?: '#136a43',
            'sort_order'  => (int) $this->request->getPost('sort_order'),
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $model = model(CourseModel::class);
        if ($id) {
            $model->update($id, $data);
        } else {
            $id = $model->insert($data);
        }

        return redirect()->to('/admin/courses')->with('success', '교육과정이 저장되었습니다.');
    }

    public function delete(int $id): RedirectResponse
    {
        $lessons = model(LessonModel::class)->where('course_id', $id)->countAllResults();
        if ($lessons > 0) {
            return redirect()->to('/admin/courses')->with('error', '강의가 등록된 과정은 삭제할 수 없습니다. 강의를 먼저 삭제하세요.');
        }

        model(SectionModel::class)->where('course_id', $id)->delete();
        model(CourseModel::class)->delete($id);

        return redirect()->to('/admin/courses')->with('success', '교육과정이 삭제되었습니다.');
    }
}
