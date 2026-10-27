<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LessonModel;
use App\Models\QuestionModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Questions extends BaseController
{
    public function index(int $lessonId): string
    {
        $lesson = model(LessonModel::class)->findWithCourse($lessonId);
        if (! $lesson) {
            throw PageNotFoundException::forPageNotFound();
        }

        $editId = (int) $this->request->getGet('edit');
        $edit   = $editId ? model(QuestionModel::class)->find($editId) : null;

        return view('admin/questions/index', [
            'title'     => $lesson['title'] . ' - 시험 문항',
            'lesson'    => $lesson,
            'questions' => model(QuestionModel::class)->byLesson($lessonId),
            'edit'      => $edit,
        ]);
    }

    public function save(): RedirectResponse
    {
        $rules = [
            'lesson_id' => 'required|integer',
            'question'  => 'required',
            'choice1'   => 'required|max_length[500]',
            'choice2'   => 'required|max_length[500]',
            'choice3'   => 'permit_empty|max_length[500]',
            'choice4'   => 'permit_empty|max_length[500]',
            'answer'    => 'required|integer|greater_than[0]|less_than[5]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $lessonId = (int) $this->request->getPost('lesson_id');
        $answer   = (int) $this->request->getPost('answer');
        $choices  = [];
        for ($i = 1; $i <= 4; $i++) {
            $choices[$i] = trim((string) $this->request->getPost('choice' . $i));
        }

        if ($choices[$answer] === '') {
            return redirect()->back()->withInput()->with('error', '정답으로 지정한 보기가 비어 있습니다.');
        }

        $data = [
            'lesson_id'   => $lessonId,
            'question'    => trim($this->request->getPost('question')),
            'choice1'     => $choices[1],
            'choice2'     => $choices[2],
            'choice3'     => $choices[3] ?: null,
            'choice4'     => $choices[4] ?: null,
            'answer'      => $answer,
            'explanation' => trim((string) $this->request->getPost('explanation')),
            'sort_order'  => (int) $this->request->getPost('sort_order'),
        ];

        $model = model(QuestionModel::class);
        $id    = (int) $this->request->getPost('id');
        $id ? $model->update($id, $data) : $model->insert($data);

        return redirect()->to('/admin/lessons/' . $lessonId . '/questions')->with('success', '문항이 저장되었습니다.');
    }

    public function delete(int $id): RedirectResponse
    {
        $model = model(QuestionModel::class);
        $q     = $model->find($id);
        if (! $q) {
            throw PageNotFoundException::forPageNotFound();
        }

        $model->delete($id);

        return redirect()->to('/admin/lessons/' . $q['lesson_id'] . '/questions')->with('success', '문항이 삭제되었습니다.');
    }
}
