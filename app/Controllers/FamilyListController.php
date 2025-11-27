<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FamilyMemberModel;
use CodeIgniter\HTTP\ResponseInterface;

class FamilyListController extends BaseController
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
        return view('family_tree/list', $data);
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
            'birth_place' => 'permit_empty|max_length[255]',
            'dead_year' => 'permit_empty|numeric|less_than_equal_to[' . date('Y') . ']',
            'dead_place' => 'permit_empty|max_length[255]',
            'parent_id' => 'permit_empty|numeric',
            'mother' => 'permit_empty|max_length[255]',
            'spouse' => 'permit_empty|max_length[255]',
            'gender' => 'permit_empty|in_list[1,2]',
            'occupation' => 'permit_empty|max_length[255]',
            'alias' => 'permit_empty|max_length[100]',
            'comments' => 'permit_empty',
            'm_order' => 'permit_empty|numeric'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'name' => $this->request->getPost('name'),
                'birth_year' => $this->request->getPost('birth_year') ?: null,
                'birth_place' => $this->request->getPost('birth_place'),
                'dead_year' => $this->request->getPost('dead_year') ?: null,
                'dead_place' => $this->request->getPost('dead_place'),
                'parent_id' => $this->request->getPost('parent_id') ?: null,
                'mother' => $this->request->getPost('mother') ?: null,
                'spouse' => $this->request->getPost('spouse') ?: null,
                'gender' => $this->request->getPost('gender'),
                'occupation' => $this->request->getPost('occupation'),
                'alias' => $this->request->getPost('alias'),
                'comments' => $this->request->getPost('comments'),
                'm_order' => $this->request->getPost('m_order') ?: null
            ];

            if ($this->familyMemberModel->insert($data)) {
                return redirect()->to('family-tree')->with('success', 'Family member added successfully.');
            }

            return redirect()->back()->withInput()->with('error', 'Failed to add family member.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while adding the family member.');
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

        // Get mother name if exists
        $mother_name = '';
        if ($member['mother']) {
            $mother = $this->familyMemberModel->find($member['mother']);
            if ($mother) {
                $mother_name = $mother['name'];
            }
        }

        // Get spouse name if exists
        $spouse_name = '';
        if ($member['spouse']) {
            $spouse = $this->familyMemberModel->find($member['spouse']);
            if ($spouse) {
                $spouse_name = $spouse['name'];
            }
        }

        return view('family_tree/edit', [
            'title' => 'Edit Family Member',
            'member' => $member,
            'parent_name' => $parent_name,
            'mother_name' => $mother_name,
            'spouse_name' => $spouse_name
        ]);
    }

    public function update($id)
    {
        try {
            $validationRules = [
                'name' => 'required|min_length[2]|max_length[100]',
                'birth_year' => 'permit_empty|numeric|less_than_equal_to[' . date('Y') . ']',
                'birth_place' => 'permit_empty|max_length[255]',
                'dead_year' => 'permit_empty|numeric|less_than_equal_to[' . date('Y') . ']',
                'dead_place' => 'permit_empty|max_length[255]',
                'parent_id' => 'permit_empty|numeric',
                'mother' => 'permit_empty|max_length[255]',
                'spouse' => 'permit_empty|max_length[255]',
                'gender' => 'permit_empty|in_list[1,2]',
                'occupation' => 'permit_empty|max_length[255]',
                'alias' => 'permit_empty|max_length[100]',
                'comments' => 'permit_empty',
                'm_order' => 'permit_empty|numeric'
            ];

            if (!$this->validate($validationRules)) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $this->validator->getErrors());
            }

            $data = [
                'name' => $this->request->getPost('name'),
                'birth_year' => $this->request->getPost('birth_year') ?: null,
                'birth_place' => $this->request->getPost('birth_place'),
                'dead_year' => $this->request->getPost('dead_year') ?: null,
                'dead_place' => $this->request->getPost('dead_place'),
                'parent_id' => $this->request->getPost('parent_id') ?: null,
                'mother' => $this->request->getPost('mother') ?: null,
                'spouse' => $this->request->getPost('spouse') ?: null,
                'gender' => $this->request->getPost('gender'),
                'occupation' => $this->request->getPost('occupation'),
                'alias' => $this->request->getPost('alias'),
                'comments' => $this->request->getPost('comments'),
                'm_order' => $this->request->getPost('m_order') ?: null
            ];

            if ($this->familyMemberModel->update($id, $data)) {
                return redirect()->to('family-tree')
                    ->with('success', 'Family member updated successfully');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update family member');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
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

            // Check for children or spouse relationships
            $hasChildren = $this->familyMemberModel->where('parent_id', $id)
                    ->orWhere('mother', $id)
                    ->countAllResults() > 0;

            $hasSpouse = $this->familyMemberModel->where('spouse', $id)->countAllResults() > 0;

            if ($hasChildren || $hasSpouse) {
                session()->setFlashdata('error', 'Cannot delete: Member has related records (children or spouse)');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot delete: Member has related records (children or spouse)'
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

    public function view($id)
    {
        $member = $this->familyMemberModel->find($id);

        if (!$member) {
//            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Family member not found');
            $data=[
                'page_title'      =>'404 - Page not found',
                'main_content'    =>'includes/error_404'
            ];
            return view('layout/main2', $data);
        }

        // Get family relationships
        $parent = $member['parent_id'] ? $this->familyMemberModel->find($member['parent_id']) : null;
        $mother = $member['mother'] ? $this->familyMemberModel->find($member['mother']) : null;
        $spouse = $member['spouse'] ? $this->familyMemberModel->find($member['spouse']) : null;

        // Get children
        $children = $this->familyMemberModel->where('parent_id', $id)
            ->orWhere('mother', $id)
            ->findAll();

        // Get brothers and sisters (same father only)
        $siblings = [];
        if ($member['parent_id']) {
            $siblings = $this->familyMemberModel
                ->where('id !=', $id)
                ->where('parent_id', $member['parent_id'])
                ->findAll();
        }

        $data = [
            'title' => 'View Family Member',
            'member' => $member,
            'parent' => $parent,
            'mother' => $mother,
            'spouse' => $spouse,
            'children' => $children,
            'siblings' => $siblings
        ];

        return view('family_tree/view', $data);
    }
}