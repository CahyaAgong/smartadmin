<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon;
use Auth;
use DB;

class MemberController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }

  public function editMember(Request $request)
  {
    $name = $request->name;
    $username = $request->username;
    $full_name = $request->full_name;
    $email = $request->email;

    $data = [
      'name' => $name,
      'username' => $username,
      'full_name' => $full_name,
      'email' => $email,
      'updated_at' => Carbon::now(),
      'updated_by' => Auth::user()->email
    ];

    $query = DB::table('users')->where( 'id', Auth::id() )->update($data);
    if ($query) {
      return json_encode(['Success' => true]);
    }else{
        return json_encode(['Success' => false]);
    }

  }

  public function editPass(Request $request)
  {
    $pass = $request->password;
    $oldpass = $request->old_password;
    if ( Hash::check($oldpass, Auth::user()->password )) {
      $data = [
        'password' => Hash::make($pass)
      ];
      $query = DB::table('users')->where('id', Auth::id() )->update($data);
      if ($query) {
        return json_encode(['status' => 1, 'message' => 'berhasil update password']);
      } else{
        return json_encode(['status' => 2, 'message' => 'update password gagal']);
      }
    }else{
      return json_encode(['status' => 0, 'message' => 'password lama salah!']);
    }
  }

}
