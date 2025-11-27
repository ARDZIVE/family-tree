<?php namespace App\Models;

use CodeIgniter\Model;

class FamilyMemberModel extends Model
{
    protected $table = 'family_members';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'birth_year',
        'birth_place',
        'dead_year',
        'dead_place',
        'mother',
        'spouse',
        'gender',
        'parent_id',
        'alias',
        'occupation',
        'comments',
        'm_order'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}