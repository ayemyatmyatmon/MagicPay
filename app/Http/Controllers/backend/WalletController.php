<?php

namespace App\Http\Controllers\backend;

use App\User;
use App\Wallet;
use Carbon\Carbon;
use App\Transaction;
use App\helper\UUIDGenerate;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index(){
        return view('backend.wallet.index');
    }

    public function ssd(){
        $wallets=Wallet::with('users');

        return Datatables::of($wallets)
        ->addColumn('account_person',function($wallet){
            $user=$wallet->users;
            if($user){
                return '<p> Name : ' .$user->name .'</p> <p> Email :'.$user->email.'</p> <p>Phone :'.$user->phone.'</p> ';
            }
        })
        ->editColumn('amount',function($wallet){
            $amount =number_format($wallet->amount ,2);
            return $amount;
        })
        ->editColumn('created_at',function($wallet){
            return Carbon::parse($wallet->created_at)->format('Y-m-d H:i:s');
        })
        ->editColumn('updated_at',function($wallet){
            return Carbon::parse($wallet->updated_at)->format('Y-m-d H:i:s');
        })
        ->rawColumns(['account_person'])
        ->make(true);

    }
    public function addAmount(){
        $users=User::orderBy('name')->get();
        return view('backend.wallet.add_amount',compact('users'));
    }

    public function addAmountStore(Request $request){
        $to_account=User::with('wallet')->where('id',$request->user_id)->firstOrFail();
        DB::beginTransaction();
        try {
            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $request->amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refnumber();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxid();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->source_id = 0;
            $to_account_transaction->amount = $request->amount;
            $to_account_transaction->description = $request->description;
            $to_account_transaction->save();

            DB::commit();
            return redirect('admin/wallet')->with('success', 'Transfer Successfully.');

        } catch (\exception $e) {
            DB::rollBack();
            return back()->withErrors(['fail' => 'Something Wrong.' . $e->getMessage()])->withInput();
        }
    }
    public function reducedAmount(){
        $users=User::orderBy('name')->get();
        return view('backend.wallet.reduced_amount',compact('users'));
    }
    
    public function reducedAmountStroe(Request $request){
        $from_account=User::with('wallet')->where('id',$request->user_id)->firstOrFail();
        DB::beginTransaction();
        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount', $request->amount);
            $from_account_wallet->update();

            $ref_no = UUIDGenerate::refnumber();

            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxid();
            $from_account_transaction->user_id = $from_account->id;
            $from_account_transaction->type = 1;
            $from_account_transaction->source_id = 0;
            $from_account_transaction->amount = $request->amount;
            $from_account_transaction->description = $request->description;
            $from_account_transaction->save();

            DB::commit();
            return redirect('admin/wallet')->with('success', 'Transfer Successfully.');

        } catch (\exception $e) {
            DB::rollBack();
            return back()->withErrors(['fail' => 'Something Wrong.' . $e->getMessage()])->withInput();
        }
    }

}
