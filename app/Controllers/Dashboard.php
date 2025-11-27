<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FamilyMemberModel;
//use App\Models\MessageModel;
//use App\Models\ContributionModel;

class Dashboard extends BaseController{
  public function show(){
      $session = session();
      if ($session->get('logged_in')) {
          return redirect()->to(base_url('dashboard'));
      }

      $data=[
          'page_title'      =>'Login',
          'main_content'    =>'includes/dashboard'
      ];
      return view('layout/main2',$data);
//      return view('includes/dashboard');
  }
}