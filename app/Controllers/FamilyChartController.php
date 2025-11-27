<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FamilyMemberModel;
use CodeIgniter\HTTP\ResponseInterface;

class FamilyChartController extends BaseController
{
    protected $familyMemberModel;

    public function __construct()
    {
        $this->familyMemberModel = new FamilyMemberModel();
    }
    public function index()
    {
        //
    }

    public function generateOrganizationalChart()
    {
        $familyMembers = $this->familyMemberModel->findAll();
        $chartRows = [];

        foreach ($familyMembers as $member) {
            // Get parent ID instead of name for proper hierarchy
            $parentId = null;
            if ($member['parent_id']) {
                $parent = $this->familyMemberModel->find($member['parent_id']);
                if ($parent) {
                    $parentId = $member['parent_id'];
                }
            }

            $yearsDisplay = '';
            if (!empty($member['birth_year']) || !empty($member['dead_year'])) {
                $yearsDisplay = '<div style="font-size:0.8rem">';
                if (!empty($member['birth_year']) && !empty($member['dead_year'])) {
                    $yearsDisplay .= $member['birth_year'] . ' - ' . $member['dead_year'];
                } else if (!empty($member['birth_year'])) {
                    $yearsDisplay .= $member['birth_year'] . ' - ...';
                } else if (!empty($member['dead_year'])) {
                    $yearsDisplay .= '... - ' . $member['dead_year'];
                }
                $yearsDisplay .= '</div>';
            }


            // Build the data row with ID references for hierarchy
            $chartRows[] = sprintf(
//                "[{v:'%d', f:'%s<div style=\"color:red; font-style:italic; font-size:0.8rem\">%s</div>'}, '%s', '%s']",
                "[{v:'%d', f:'%s<div style=\"color:red; font-style:italic; font-size:0.8rem\"></div>'}, '%s', '%s', '%s']",
                $member['id'],                  // v: unique ID (important for hierarchy)
                esc($member['name']),           // f: display name (escaped for safety)
//                $yearsDisplay ?: '',                // birth year - death year
                $parentId ? $parentId : '',     // parent's ID (not name) for proper hierarchy
                $member['id'] ?? '',
                $member['name'] ?? '',
                    $member['gender'] ?? '',

//                'Member'                        // tooltip

            );
        }

        // Join the rows with commas to create the final array string
        $chartData = '[' . implode(",\n", $chartRows) . ']';

//        $chartData2[] = [
//            strval($member['id']),      // Convert ID to string
//            strval($parentId),          // Convert parent ID to string
//            esc($member['name']) . $yearsDisplay,
//            strval($member['gender']),  // Convert gender to string
//            $yearsDisplay
//        ];


        $data = [
            'title'         => 'Family Tree Organizational Chart',
            'chartData'     => $chartData,
//            'chartData2'    => json_encode($chartData2)

        ];

        return view('family_tree/organizational_chart', $data);
    }

    public function chart()
    {
        $members = $this->familyMemberModel->findAll();
        $chartDataModal = [];

        foreach ($members as $member) {
            $chartDataModal[] = [
                $member['id'],                    // Member ID
                $member['parent_id'],             // Parent ID
                $member['name'],                  // Name
                $member['gender'],                // Gender
                $member['birth_year'],            // Birth Year
                $member['birth_place'],           // Birth Place
                $member['dead_year'],             // Dead Year
                $member['dead_place'],            // Dead Place
                $member['occupation'],            // Occupation
            ];
        }

        return view('family_tree/chart', [
            'chartDataModal' => json_encode($chartDataModal),
            'title' => 'Family Tree Chart'
        ]);
    }
}
