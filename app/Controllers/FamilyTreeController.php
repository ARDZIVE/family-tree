<?php namespace App\Controllers;

use App\Models\FamilyMemberModel;
use CodeIgniter\Controller;

class FamilyTreeController extends Controller
{
    protected $familyMemberModel;

    public function __construct()
    {
        $this->familyMemberModel = new FamilyMemberModel();
    }

    public function getTree()
    {
        try {
            $excludeId = $this->request->getGet('exclude_id');

            // Get all members except the excluded one
            $familyMembers = $excludeId
                ? $this->familyMemberModel->where('id !=', $excludeId)->findAll()
                : $this->familyMemberModel->findAll();

            if (empty($familyMembers)) {
                return $this->response->setBody('<div class="alert alert-info">No family members found</div>');
            }

            $treeHtml = $this->renderTree($familyMembers, 0);
            return $this->response->setBody($treeHtml);

        } catch (\Exception $e) {
            log_message('error', 'Error in getTree: ' . $e->getMessage());
            return $this->response->setBody(
                '<div class="alert alert-danger">Error loading family tree</div>'
            );
        }
    }

    private function renderTree($members, $parentId = 0)
    {
        $html = '<ul class="tree-list p-0">';

        foreach ($members as $member) {
            if ($member['parent_id'] != $parentId) {
                continue;
            }

            $hasChildren = $this->hasChildren($members, $member['id']);

            $html .= '<li class="tree-item">';
            $html .= '<div class="tree-content d-flex align-items-center">';

            if ($hasChildren) {
                $html .= '<span class="tree-toggle me-2"><i class="bi bi-chevron-right"></i></span>';
            }

            $html .= '<a href="#" class="select-parent" data-id="' . $member['id'] . '" data-name="' . esc($member['name']) . '">';
            $html .= esc($member['name']);
            if (!empty($member['birth_year'])) {
                $html .= ' <span class="text-muted">(' . $member['birth_year'] . ')</span>';
            }
            $html .= '</a>';
            $html .= '</div>';

            if ($hasChildren) {
                $html .= $this->renderTree($members, $member['id']);
            }

            $html .= '</li>';
        }

        $html .= '</ul>';
        return $html;
    }

    private function hasChildren($members, $parentId)
    {
        foreach ($members as $member) {
            if ($member['parent_id'] == $parentId) {
                return true;
            }
        }
        return false;
    }
}