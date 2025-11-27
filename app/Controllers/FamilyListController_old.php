<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FamilyMemberModel;
use CodeIgniter\HTTP\ResponseInterface;

class FamilyListController_old extends BaseController
{
    protected $familyMemberModel;

    public function __construct()
    {
        $this->familyMemberModel = new FamilyMemberModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Family Tree',
            'familyMembers' => $this->familyMemberModel->findAll()
        ];
        return view('family_tree/index', $data);
    }

    public function create()
    {
        return view('family_tree/create', [
            'title' => 'Add Family Member',
            'familyMembers' => $this->familyMemberModel->findAll()
        ]);
    }

    public function store()
    {
        $validationRules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'birth_year' => 'permit_empty|numeric|less_than_equal_to[' . date('Y') . ']',
            'parent_id' => 'permit_empty|numeric'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput();
        }

        try {
            $data = [
                'name' => $this->request->getPost('name'),
                'birth_year' => $this->request->getPost('birth_year') ?: null,
                'parent_id' => $this->request->getPost('parent_id') ?: null
            ];

            if ($this->familyMemberModel->insert($data)) {
                return redirect()->to('family-tree')->with('success', 'Family member added.');
            }

            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $member = $this->familyMemberModel->find($id);

        if (!$member) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Family member not found');
        }

        // Get parent name if exists
        $parent_name = '';
        if ($member['parent_id']) {
            $parent = $this->familyMemberModel->find($member['parent_id']);
            if ($parent) {
                $parent_name = $parent['name'];
            }
        }

        return view('family_tree/edit', [
            'title' => 'Edit Family Member',
            'member' => $member,
            'parent_name' => $parent_name
        ]);
    }

    public function update($id)
    {
        $response = [
            'success' => false,
            'message' => ''
        ];

        try {
            $validationRules = [
                'name' => 'required|min_length[2]|max_length[100]',
                'birth_year' => 'permit_empty|numeric',
                'parent_id' => 'permit_empty|numeric'
            ];

            if (!$this->validate($validationRules)) {
                $response['message'] = 'Validation failed: ' . implode(', ', $this->validator->getErrors());
                return $this->response->setJSON($response);
            }

            $data = [
                'name'          => $this->request->getPost('name'),
                'birth_year'    => $this->request->getPost('birth_year'),
                'parent_id'     => $this->request->getPost('parent_id') ?: null,
            ];

            if ($this->familyMemberModel->update($id, $data)) {
                $response['success'] = true;
                $response['message'] = 'Family member updated successfully';
            } else {
                $response['message'] = 'Failed to update family member';
            }
        } catch (\Exception $e) {
            $response['message'] = 'Error: ' . $e->getMessage();
        }

        return $this->response->setJSON($response);
    }

    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No ID provided'
            ]);
        }

        try {
            // Check if member exists
            $member = $this->familyMemberModel->find($id);
            if (!$member) {
                session()->setFlashdata('error', 'Member not found');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Member not found'
                ]);
            }

            // Check for children
            $hasChildren = $this->familyMemberModel->where('parent_id', $id)->countAllResults() > 0;
            if ($hasChildren) {
                session()->setFlashdata('error', 'Cannot delete: Member has children records');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot delete: Member has children records'
                ]);
            }

            // Perform deletion
            if ($this->familyMemberModel->delete($id)) {
                session()->setFlashdata('success', 'Member deleted successfully');
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Member deleted successfully'
                ]);
            }

            session()->setFlashdata('error', 'Failed to delete member');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete member'
            ]);

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'An error occurred while deleting');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting'
            ]);
        }
    }
}
