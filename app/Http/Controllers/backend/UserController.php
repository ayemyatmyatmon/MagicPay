<?php

namespace App\Http\Controllers\backend;

use App\helper\UUIDGenerate;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    public function home()
    {
        return view('backend.home');
    }

    public function index()
    {
        return view('backend.user.index');
    }

    public function ssd()
    {
        $users = User::query();

        return Datatables::of($users)
            ->editColumn('created_at', function ($user) {
                return Carbon::parse($user->created_at)->format('Y-m-d H:i:s');
            })
            ->editColumn('updated_at', function ($user) {
                return Carbon::parse($user->updated_at)->format('Y-m-d H:i:s');

            })
            ->editColumn('user_agent', function ($user) {
                if ($user->user_agent) {
                    $agent = new Agent();
                    $agent->setUserAgent($user->user_agent);
                    $device = $agent->device();
                    $platform = $agent->platform();
                    $browser = $agent->browser();
                    return "<table class='table table-bordered'>
                            <tbody>
                                <tr><td>Device</td><td>" . $device . "</td></tr>
                                <tr><td>Platform</td><td>" . $platform . "</td></tr>
                                <tr><td>Browser</td><td>" . $browser . "</td></tr>
                            </tbody>
                        </table>";
                }
                return '-';
            })

            ->addColumn('action', function ($user) {
                $edit_icon = '<a href="' . route('admin.user.edit', $user->id) . '" class="text-warning mr-2 "><i class="fas fa-edit"></i></a>';
                $delete_icon = '<a href="#" class="delete text-danger" data-id="' . $user->id . '" "><i class="fas fa-trash"></i></a>';
                return "<div class='icon'>" . $edit_icon . $delete_icon . "</div>";
            })
            ->rawColumns(['user_agent', 'action'])
            ->make(true);
    }
    public function create()
    {
        return view('backend.user.create');
    }
    public function store(StoreUser $request)
    {
        try {
            $users = new User();
            $users->name = $request->name;
            $users->email = $request->email;
            $users->phone = $request->phone;
            $users->password = Hash::make($request->password);
            $users->save();

            Wallet::firstOrCreate(
                ['user_id' => $users->id],
                [
                    'account_number' => UUIDGenerate::accountnumber(),
                    'amount' => 0,
                ]
            );
            DB::commit();

            return redirect()->route('admin.user.index')->with('create', 'Successfully created');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['fail' => 'Something Wrong. ' . $e->getMessage()])->withInput();
        }

    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.user.edit', compact('user'));
    }
    public function update($id, UpdateUser $request)
    {
        DB::beginTransaction();
        try {
            $users = User::findOrFail($id);
            $users->name = $request->name;
            $users->email = $request->email;
            $users->phone = $request->phone;
            $users->password = $request->password ? Hash::make($request->password) : $users->password;
            $users->update();
            Wallet::firstOrCreate(
                ['user_id' => $users->id],
                [
                    'account_number' => UUIDGenerate::accountnumber(),
                    'amount' => 0,
                ]
            );
            DB::commit();
            return redirect()->route('admin.user.index')->with('update', 'Successfully Updated');
            } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['fail' => 'Something Wrong. ' . $e->getMessage()])->withInput();
            }
        }
    public function destroy($id)
    {
        $users = User::findOrFail($id);
        $users->delete($id);
        return "success";
    }
}
