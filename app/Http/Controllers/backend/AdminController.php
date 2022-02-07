<?php

namespace App\Http\Controllers\backend;

use App\AdminUser;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreAdminUser;
use App\Http\Requests\UpdateAdminUser;


class AdminController extends Controller
{
    public function home(){

        return view('backend.home');
    }

    public function index(){
        return view('backend.admin_user.index');
    }

    public function ssd(){
        $adminusers=AdminUser::query();

        return Datatables::of($adminusers)
        ->editColumn('created_at',function($adminuser){
            return Carbon::parse($adminuser->created_at)->format('Y-m-d H:i:s');
        })
        ->editColumn('updated_at',function($adminuser){
            return Carbon::parse($adminuser->updated_at)->format('Y-m-d H:i:s');

        })
        ->editColumn('user_agent',function($adminuser){
            if($adminuser->user_agent){
                $agent = new Agent();
                $agent->setUserAgent($adminuser->user_agent);
                $device = $agent->device();
                $platform = $agent->platform();
                $browser = $agent->browser();
                return "<table class='table table-bordered'>
                            <tbody>
                                <tr><td>Device</td><td>".$device."</td></tr>
                                <tr><td>Platform</td><td>".$platform."</td></tr>
                                <tr><td>Browser</td><td>".$browser."</td></tr>
                            </tbody>
                        </table>";
            }
            return '-';
        })


        ->addColumn('action',function($adminuser){
            $edit_icon='<a href="'.route('admin.admin-user.edit',$adminuser->id).'" class="text-warning mr-2 "><i class="fas fa-edit"></i></a>';
            $delete_icon='<a href="#" class="delete text-danger" data-id="'.$adminuser->id.'" ><i class="fas fa-trash"></i></a>';
            return "<div class='icon'>". $edit_icon. $delete_icon ."</div>";
        })
        ->rawColumns(['user_agent','action'])
        ->make(true);
    }
    public function create(){
        return view('backend.admin_user.create');
    }
    public function store(StoreAdminUser $request){
        $adminusers=new AdminUser();
        $adminusers->name=$request->name;
        $adminusers->email=$request->email;
        $adminusers->phone=$request->phone;
        $adminusers->password=Hash::make($request->password);
        $adminusers->save();
        return redirect()->route('admin.admin-user.index')->with('create','Successfully created');
    }
    public function edit($id){
        $adminuser=AdminUser::findOrFail($id);
        return view('backend.admin_user.edit',compact('adminuser'));
    }
    public function update($id,UpdateAdminUser $request){

        $adminusers=AdminUser::findOrFail($id);
        $adminusers->name=$request->name;
        $adminusers->email=$request->email;
        $adminusers->phone=$request->phone;
        $adminusers->password=$request->password ? Hash::make($request->password) : $adminusers->password;
        $adminusers->update();
        return redirect()->route('admin.admin-user.index')->with('update','Successfully Updated');
    }
    public function destroy($id){
        $adminusers=AdminUser::findOrFail($id);
        $adminusers->delete($id);
        return "success";
    }
}
