<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\SubscribersModel;
use App\Models\UsersModel;

class Login extends BaseController
{
    public function index()
    {
        $session = session();
        if ($session->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        $captcha = create_captcha($this->captcha_config);
        $session->set('captchaCode');
        $session->set('captchaCode', $captcha['word']);

        $data['captchaImg'] = $captcha['image'];
        $data["captcha_content"]="captcha";
        $data['page_title']='Login';
        $data['main_content']='auth/login';
        return view('layout/main_no_menu',$data);
    }

    public function sign()
    {
        $session = session();
        $userModel = new UsersModel();
        $subscriberModel=new SubscribersModel();
        $validation = \Config\Services::validation();
        if($this->request->getMethod()==="POST") {
            $rules = [
                'username' => [
                    'label' => 'User Name',
                    'rules' => 'required|min_length[2]|max_length[20]',
                    'errors' => [
                        'required' => '- is required',
                        'min_length' => '- must be at least {param} characters',
                    ],
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]',
                    'errors' => [
                        'required' => '- is required',
                        'min_length' => '- must be at least {param} characters',
                    ],
                ],
                'captcha' => [
                    'label' => 'Captcha',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '- is required',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                session()->setFlashData("warning", "<b>INFO - </b>Please fill in all the required fields");

                $captcha = create_captcha($this->captcha_config);
                $session->set('captchaCode');
                $session->set('captchaCode', $captcha['word']);

                $data['validation'] = $this->validator;
                $data['captchaImg'] = $captcha['image'];
                $data["captcha_content"] = "captcha";
                $data['page_title'] = 'Login';
                $data['main_content'] = 'auth/login';
                return view('layout/main_no_menu', $data);

            } else {
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('password');
                $captcha = $this->request->getPost('captcha');
                $user = $userModel->where('username', $username)->first();
                if ($user !== null && $captcha === $session->get('captchaCode') && is_array($user) && array_key_exists('password', $user) && password_verify($password, $user['password'])) {
                    $session->set([
                        'user_id'       => $user['id'],
                        'username'      => $user['username'],
                        'email'         => $user['email'],
                        'firstname'      => $user['firstname'],
                        'lastname'      => $user['lastname'],
                        'group_id'      => $user['group_id'],
                        'logged_in'     => true,
                    ]);
                    return redirect()->to('family-tree');
                } else {
                    $htmlContent = null;
                    $htmlSubject = null;
                    $htmlSubject = 'Family Tree Login Failed';
                    $htmlContent = '<h1>Family Tree Login Failed</h1>';
                    $htmlContent .= '<h3>Failed Login by using:</h3>';
                    $htmlContent .= '<b>URL: </b>' . current_url();
                    $htmlContent .= '<br><b>User Name: </b>' . $username;
                    $htmlContent .= '<br><b>Password: </b>' . $password;
                    $htmlContent .= '<br><b>Captcha Typed: </b>' . $captcha;
                    $htmlContent .= '<br><b>Captcha Code: </b>' . $session->get('captchaCode');;
//                    $htmlContent .= '<br><b>IP: </b>'.$this->input->ip_address();
                    $subscribersList = $subscriberModel->find();
                    smtp_send(2, $subscribersList, $htmlContent, $htmlSubject);
                    //            log_message('error', 'Login failed for username: ' . $username); // Log the error
                    return redirect()->to('auth/login')->with('error', 'Invalid Username, Password or Captcha');
                }
            }
        }
    }

    public function refresh(){
        $session = session();

        $captcha = create_captcha($this->captcha_config);
//        $session->remove('captchaCode');
        $session->set('captchaCode',$captcha['word']);
        return $captcha['image'];

    }

    public function validate_captcha(){
        $session = session();
        if($this->request->getPost('captcha') != $session->get['captchaCode'])
        {
            $this->form_validation->set_message('validate_captcha', 'Wrong code');
            return false;
        }else{
            return true;
        }

    }

    public function logout()
    {
        $session = session();
        session_destroy();
        ob_clean();
        $data['page_title']='Logout';
        $data['main_content']='auth/logout';
        return view('layout/main_no_menu',$data);
    }

}
