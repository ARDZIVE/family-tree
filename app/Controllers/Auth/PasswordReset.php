<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;
use App\Models\SystemMailContentsModel;

class PasswordReset extends BaseController
{

    public function index()
    {
        $data['page_title'] = 'Reset Password';
        $data['main_content'] = 'auth/reset_password';
        return view('layout/main_no_menu', $data);
    }

    public function forgotPassword()
    {
        $data['page_title'] = 'Forgot Password';
        $data['main_content'] = 'auth/forgot_password';
        return view('layout/main_no_menu', $data);
    }

    public function processForgotPassword()
    {
        $username = $this->request->getPost('username');
        $userModel = new UsersModel();
        $user = $userModel->getUserByUsername($username);
        if (!$user) {
            return redirect()->back()->with('error', 'User Name not found!');
        } else {

//            $token = rand(1000, 999999);
            $token = random_int(100000, 999999);
            $expiration = date('Y-m-d H:i:s', strtotime('+1 hour')); // token expires in 1 hour

            // Update user with reset token and expiration
            $userModel->updateUser($user['id'], [
                'reset_token'   => $token,
                'token_expiry'  => $expiration
            ]);

            $user_fullname = $user['firstname'] . " " . $user['lastname'];

            $htmlContent = null;
            $htmlSubject = null;
//            $htmlContent = null;
            $htmlSubject = 'Password Reset';
            $htmlContent = '<h1>Sending email via SMTP server</h1>';
            $htmlContent .= '<h5>Hello : ' . $user_fullname . '</h5>';
            $htmlContent .= '<h3>Failed Login by using: ' .$username.'</h3>';
            $htmlContent .= '<b>Token Link: </b>' . $token;


            $content_var=array(
                'USER_FULLNAME' => $user_fullname,
                'USERNAME'      => $username,
                'TOKEN_CODE'    => $token
            );
            $pattern = '[%s]';
            foreach($content_var as $key=>$val){
                $varMap[sprintf($pattern,$key)] = $val;
            }
            $systemMailContentModel=new SystemMailContentsModel();
            $sysmail=$systemMailContentModel->getSystemMailbyID(1);
            $htmlContent = strtr($sysmail['message'],$varMap);
//            $htmlContent = $sysmail['message'];
            $htmlSubject = $sysmail['subject'];

            smtp_send_one($user['email'], $user_fullname, $htmlContent, $htmlSubject);
            return redirect()->to('/auth/password-reset')->with('info', 'Please check the email associated with this account to retrieve the 6-digit code that has been sent to you.');
        }
    }

    public function processResetPassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        $userModel = new UsersModel();
        $user = $userModel->asArray()
            ->where('reset_token', $token)
            ->where('token_expiry >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Token is invalid or expired.');
        }

        // Hash the new password
        $newPassword = password_hash($password, PASSWORD_BCRYPT);

        $userModel->updateUser($user['id'], [
            'password' => $newPassword,
            'reset_token' => null,
            'token_expiry' => null
        ]);

        return redirect()->to('/auth/login')->with('message', 'Your password has been reset successfully.');
    }


////////////// UPADTE PASSWORD ////////////
    public function update_password()
    {
        if ($this->request->getMethod() !== "POST") {
            return redirect()->to('auth/login');
        }

        $rules = [
            'username' => [
                'label' => 'User Name',
                'rules' => 'required|min_length[2]|max_length[20]',
                'errors' => [
                    'required' => '- is required',
                    'min_length' => '- must be at least {param} characters',
                ],
            ],
            'token' => [
                'label' => 'Token',
                'rules' => 'required|numeric|exact_length[6]',
                'errors' => [
                    'required' => '- is required',
                    'numeric' => '- only numbers',
                    'exact_length' => '- must be {param} digits',
                ],
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|alpha_numeric_punct|max_length[20]|min_length[8]',
                'errors' => [
                    'required' => '- is required',
                    'alpha_numeric_punct' => '- only alpha numeric and special characters',
                    'max_length' => '- must not exceed {param} characters',
                    'min_length' => '- must be at least {param} characters',
                ],
            ],
            'passconf' => [
                'label' => 'Password Confirm',
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => '- is required',
                    'matches' => '- does not match the Password field',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            session()->setFlashData("error", "Data not saved!");
            $data['validation'] = $this->validator;
            $data['page_title'] = 'Reset Password';
            $data['main_content'] = 'auth/reset_password';
            return view('layout/main_no_menu', $data);
        }

        $username = $this->request->getPost('username');
        $token = $this->request->getPost('token');

        $userModel = new UsersModel();
        $user = $userModel->asArray()
            ->where('username', $username)
            ->where('reset_token', $token) // Direct comparison with the entered token
            ->where('token_expiry IS NOT NULL')
            ->where('token_expiry >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            // Log potential brute force attempts
            log_message('warning', 'Failed password reset attempt for user: ' . $username . ' with token: ' . $token);
            session()->setFlashData("error", "Invalid username, token, or expired reset request.");
            return redirect()->back();
        }

        // Hash the new password
        $password = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT, ['cost' => 12]);

        try {
            $userModel->updateUser($user['id'], [
                'password' => $password,
                'reset_token' => null,
                'token_expiry' => null,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Send email notification
            $user_fullname = $user['firstname'] . " " . $user['lastname'];
            $htmlSubject = 'Password Changed Successfully';
            $htmlContent = '<h1>Password Changed</h1>';
            $htmlContent .= '<h5>Hello ' . htmlspecialchars($user_fullname) . '</h5>';
            $htmlContent .= '<h3>Your password has been changed for Username: ' . htmlspecialchars($username) . '</h3>';
            $htmlContent .= '<p>If you did not make this change, please contact support immediately.</p>';
            $htmlContent .= '<br>Have a nice day.';

            smtp_send_one($user['email'], $user_fullname, $htmlContent, $htmlSubject);

            session()->setFlashData("success", "Password changed successfully!");
            return redirect()->to('/auth/login')->with('message', 'Your password has been reset successfully.');

        } catch (Exception $e) {
            log_message('error', 'Password reset failed for user: ' . $username . '. Error: ' . $e->getMessage());
            session()->setFlashData("error", "An error occurred while updating your password. Please try again.");
            return redirect()->back();
        }
    }
}