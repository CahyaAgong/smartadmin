<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Carbon;
use Auth;

class AccountController extends Controller
{
    public function getAccount()
    {
      $getdata = DB::table('users')->get();
      if ($getdata->count() > 0) {
          $temp = [];
          $i = 0;
          foreach ($getdata as $key) {
              $temp[$i]['No'] = $i+1;
              $temp[$i]['id'] = $key->id;
              $temp[$i]['name'] = $key->name;
              $temp[$i]['username'] = $key->username;
              $temp[$i]['full_name'] = $key->full_name;
              $temp[$i]['email'] = $key->email;
              $temp[$i]['email_verified_at'] = $key->email_verified_at;
              $temp[$i]['member_end'] = $key->member_end;
              $temp[$i]['Is_Admin'] = $key->Is_Admin;
              $temp[$i]['created_at'] = $key->created_at;
              $temp[$i]['updated_at'] = $key->updated_at;
              $i++;
          }
          $data['data'] = $temp;
          // $data['total'] = Event::count();
          $data['total'] = $getdata->count();
      } else {
          $data['data'] = [];
          $data['total'] = 0;
      }
      header('Content-type: application/json');
      return response()->json($data);
    }

    public function addAccount(Request $request)
    {
      try {
        $current_date = Carbon::now();
        $member_end = date('Y-m-d H:i:s', strtotime('+1 year', strtotime( $current_date )));
        $data = [
          'name'=>$request->name,
          'username'=>$request->username,
          'full_name'=>$request->full_name,
          'email'=>$request->email,
          'password'=>Hash::make($request->password),
          'member_end'=>$member_end,
          'Is_Admin'=>0,
          'created_at'=>Carbon::now(),
          'created_by'=>Auth::user()->name,
          ];

          $insert = DB::table('users')->insert($data);
          if ($insert) {
              return response()->json($insert);
          }
      } catch (\Exception $e) {
        return $e;
      }

    }

    public function deleteAccount(Request $request)
    {
      try {
        $id = $request->id;
        $hapus = DB::table('users')->where('id', $id)->delete();
        if ($hapus) {
          return response()->json($hapus);
        }
      } catch (\Exception $e) {
        return $e;
      }
    }

    public function updateAccount(Request $request)
    {
      try {
        $current_date = Carbon::now();
        $member_end = date('Y-m-d H:i:s', strtotime('+1 year', strtotime( $current_date )));
        $data = [
          'name'=>$request->name,
          'username'=>$request->username,
          'full_name'=>$request->full_name,
          'email'=>$request->email,
          'updated_at'=>Carbon::now(),
          'updated_by'=>Auth::user()->name,
          ];

          $upadte = DB::table('users')->where('id', $request->id)->update($data);
          if ($upadte) {
              return response()->json($upadte);
          }
      } catch (\Exception $e) {
        return $e;
      }

    }
}
