<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Controllers\UserModel;
use App\Models\UsersModel;

class Register extends BaseController
{
    public function index()
    {
        $data['page_title']='Register';
        $data['main_content']='auth/register';
        return view('layout/main_no_menu',$data);
    }

    public function sign()
    {
        $data = [];
        $validation = \Config\Services::validation();
        if($this->request->getMethod()==="POST"){
            $rules = [
                'username' => [
                    'label'  => 'User Name',
                    'rules'  => 'required|min_length[2]|max_length[20]|is_unique[users.username,id,{id}]',
                    'errors' => [
                        'is_unique'     => '- must be unique',
                        'required'      => '- is required',
                        'min_length'    => '- must be at least {param} characters',
                    ],
                ],
                'firstname' => [
                    'label'  => 'First Name',
                    'rules'  => 'required|min_length[2]|max_length[20]',
                    'errors' => [
                        'required'      => '- is required',
                        'is_unique'     => '- is unique',
                        'min_length'    => '- must be at least {param} characters',
                        'max_length'    => '- must not exceed {param} characters',
                    ],
                ],
                'lastname' => [
                    'label'  => 'Last Name',
                    'rules'  => 'required|min_length[2]|max_length[20]',
                    'errors' => [
                        'required'      => '- is required',
                        'min_length'    => '- must be at least {param} characters',
                    ],
                ],
                'email' => [
                    'label'  => 'Email',
                    'rules'  => 'required|valid_email|is_unique[users.email,id,{id}]',
                    'errors' => [
                        'is_unique'         => '- must be unique',
                        'required'          => '- is required',
                        'valid_email'       => '- not a valid email address',
                        'max_length'        => '- must not exceed {param} characters',
                    ],
                ],
                'password' => [
                    'label'  => 'Password',
                    'rules'  => 'required|min_length[8]|alpha_numeric_punct',
                    'errors' => [
                        'required'              => '- is required',
                        'alpha_numeric_punct'   => '- only alpha numéric and special characters',
//                        'max_length'            => '- must not exceed {param} characters',
                        'min_length'            => '- must be at least {param} characters',
                    ],
                ],
                'passconf' => [
                    'label'  => 'Password Confirm',
                    'rules'  => 'required|matches[password]',
                    'errors' => [
                        'required'       => '- is required',
                        'matches'        => '- does not match the Password field',
                    ],
                ],
                'phone' => [
                    'label'  => 'Phone',
                    'rules'  => 'required|numeric|max_length[12]',
                    'errors' => [
                        'required'       => '- is required',
                        'numeric'        => '- only numéric values',
                        'max_length'     => '- must not exceed {param} characters',
                    ],
                ],
            ];


            if (!$this->validate($rules)) {
                session()->setFlashData("error","Data not saved!");
                $data['validation']=$this->validator;
                $data['page_title']='Register';
                $data['main_content']='auth/register';
                return view('layout/main_no_menu',$data);
//                return redirect()->to('register');

            }else {
                $firstname           = $this->request->getVar('firstname');
                $lastname           = $this->request->getVar('lastname');
                $username           = $this->request->getVar('username');
                $email              = $this->request->getVar('email');
                $password           = PASSWORD_HASH($this->request->getVar('password'),PASSWORD_BCRYPT);
                $phone              = $this->request->getVar('phone');

                $data = [
                    'firstname'      => $firstname,
                    'lastname'      => $lastname,
                    'username'      => $username,
                    'email'         => $email,
                    'password'      => $password,
                    'phone'         => $phone,
                    'updated_at'    => date("Y-m-d h:i:s"),//NOW(),
                ];

                $userModel = new UsersModel();
                $userModel->save($data);
                session()->setFlashData("success","Data Saved successfully!");
//                return redirect()->to('register');
            }

            return redirect()->to('register');
        }

    }

    public function users_list()
    {
        $userModel = new UserModel();

        // Fetch all users
        $data['users'] = $userModel->findAll();

        // Load the view and pass the users data
        echo view('users_list', $data);
    }
}
