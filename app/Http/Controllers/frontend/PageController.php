<?php

namespace App\Http\Controllers\frontend;

use App\User;
use App\Transaction;
use App\helper\UUIDGenerate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePassword;
use App\Http\Requests\TransferValidate;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class PageController extends Controller
{
    public function home()
    {
        $authuser = Auth::user();
        $users = Auth::user()->wallet;
        return view('frontend.home', compact('users', 'authuser'));
    }

    public function profile()
    {
        return view('frontend.profile');
    }

    public function updatePassword()
    {
        return view('frontend.updatepassword');
    }

    public function updatePasswordStore(UpdatePassword $request)
    {
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $user = Auth::user();
        if (Hash::check($old_password, $user->password)) {
            $user->password = Hash::make($new_password);
            $user->update();

            $title='Update Password';
            $message='Successfully updated';
            $sourceable_id=$user->id;
            $sourceable_type=1;
            $web_link=url('/profile');
            $deep_link=[
                'target'=>'profile',
                'parameter'=>null,
            ];
            Notification::send([$user], new GeneralNotification($title,$message,$sourceable_id,$sourceable_type,$web_link,$deep_link));

            return redirect('profile')->with('update', 'Successfully Update');
        }
        return back()->withErrors(["old_password" => "The old Password is wrong."])->withInput();
    }

    public function wallet()
    {
        $user = Auth::user();
        $authuser = Auth::user()->wallet;
        return view('frontend.wallet', compact('authuser', 'user'));
    }

    public function transfer()
    {
        $authuser = Auth::user();
        return view('frontend.transfer', compact('authuser'));
    }

    public function transferConfirmation(TransferValidate $request)
    {
        $authuser = Auth::user();
        $fromuser = $authuser;
        $amount = $request->amount;
        $description = $request->description;
        if ($request->amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be greater than 1000 MMK'])->withInput();
        }

        if ($authuser->phone == $request->to_phone) {
            return back()->withErrors(['to_phone' => 'The Phone Number is invalid']);

        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'The Phone Number is invalid']);
        }
        if($fromuser->wallet->amount <=$amount){
            return back()->withErrors(['amount' => 'The amount is not transfer the money is not enough.'])->withInput();

        }

        return view('frontend.transferconfirmation', compact('amount', 'description', 'fromuser', 'to_account'));
    }

    public function transferComplete(TransferValidate $request)
    {
        $authuser = Auth::user();
        $fromuser = $authuser;
        $amount = $request->amount;
        $description = $request->description;

        if ($request->amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be greater than 1000 MMK'])->withInput();
        }

        $authuser = Auth::user();
        if ($authuser->phone == $request->to_phone) {
            return back()->withErrors(['to_phone' => 'The Phone Number is invalid']);

        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'The Phone Number is invalid']);
        }
        if($fromuser->wallet->amount <=$amount){
            return back()->withErrors(['amount' => 'The amount is not transfer the money is not enough.'])->withInput();

        }

        if (!$fromuser->wallet || !$to_account->wallet) {
            return back()->withErrors(['fail' => 'Something Wrong']);

        }

        DB::beginTransaction();
        try {
            $fromuser_wallet = $fromuser->wallet;
            $fromuser_wallet->decrement('amount', $amount);
            $fromuser_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refnumber();

            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxid();
            $from_account_transaction->user_id = $fromuser->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxid();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->source_id = $fromuser->id;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            $title='E-money Transfer';
            $message='E-money transfer ' . $amount . ' MMK to '. $to_account->name;
            $sourceable_id=$from_account_transaction->id;
            $sourceable_type=Transaction::class;
            $web_link=url('/transaction/' . $from_account_transaction->trx_id);
            $deep_link=[
                'target'=>'transaction_detail',
                'parameter'=>[
                    'trx_id'=>$from_account_transaction->trx_id,
                ],
            ];
            Notification::send([$fromuser], new GeneralNotification($title,$message,$sourceable_id,$sourceable_type,$web_link,$deep_link));

            $title='E-money Received';
            $message='E-money received ' . $amount .' MMK to '.$fromuser->name;
            $sourceable_id=$to_account_transaction->id;
            $sourceable_type=Transaction::class;
            $web_link=url('/transaction/' . $to_account_transaction->trx_id);
            $deep_link=[
                'target'=>'transaction_detail',
                'parameter'=>[
                    'trx_id'=>$to_account_transaction->trx_id,
                ],
            ];
            Notification::send([$to_account], new GeneralNotification($title,$message,$sourceable_id,$sourceable_type,$web_link,$deep_link));

            DB::commit();
            return redirect('/transaction/' . $from_account_transaction->trx_id)->with('success', 'Transfer Successfully.');

        } catch (\exception $e) {
            DB::rollBack();
            return back()->withErrors(['fail' => 'Something Wrong.' . $e->getMessage()])->withInput();
        }

    }

    public function transaction(Request $request)
    {
        $authuser = Auth::user();
        $transactions = Transaction::orderBy('created_at', 'DESC')->with('user', 'source')->where('user_id', $authuser->id);
        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }
        if ($request->date) {
            $transactions = $transactions->whereDate('created_at', $request->date);
        }
        $transactions = $transactions->paginate(5);
        return view('frontend.transaction', compact('transactions'));
    }

    public function transactionDetail($trx_id)
    {
        $authuser = Auth::user();
        $transaction = Transaction::with('user', 'source')->where('user_id', $authuser->id)->where('trx_id', $trx_id)->first();
        return view('frontend.transactiondetail', compact('transaction'));
    }

    public function toAccountVerify(Request $request)
    {
        $authuser = Auth::user();
        if ($authuser->phone != $request->phone) {
            $user = User::where('phone', $request->phone)->first();

            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'data' => $user,
                ]);
            }
        }
        return response()->json([
            'status' => 'fail',
        ]);
    }

    public function checkPassword(Request $request)
    {
        $authuser = Auth::user();
        if (Hash::check($request->password, $authuser->password)) {
            return response()->json([
                'status' => 'success',
                'message' => 'The password is correct',
            ]);
        }
        return response()->json([
            'status' => 'fail',
            'message' => 'The password is incorrect',
        ]);
    }
    public function transferHash(Request $request)
    {
        $str = $request->to_phone . $request->amount . $request->description;
        $hash_value = hash_hmac('sha256', $str, 'magicPay123!@#');

        return response()->json([
            'status' => 'success',
            'data' => $hash_value,
        ]);
    }
    public function receivedQr(){
        $authusers=Auth::user();
        return view('frontend.received_qr',compact('authusers'));
    }
    public function scanAndPay(){
        return view('frontend.scan_and_pay');
    }
    public function scanAndPayTransfer(Request $request){
        $from_account=Auth::guard('web')->user();
        $to_account=User::where('phone',$request->to_phone)->first();

        if(!$to_account){
            return back()->withErrors(['fail'=>'QR code is invalid'])->withInput();
        }
        return view('frontend.scan_and_pay_transfer',compact('from_account','to_account'));
    }
    public function scanAndPayTransferConfirm(TransferValidate $request)
    {
        $authuser = Auth::user();
        $fromuser = $authuser;
        $amount = $request->amount;
        $description = $request->description;
        if ($request->amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be greater than 1000 MMK'])->withInput();
        }

        if ($fromuser->phone == $request->to_phone) {
            return back()->withErrors(['to_phone' => 'The Phone Number is invalid']);

        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'The Phone Number is invalid']);
        }
        if($fromuser->wallet->amount <=$amount){
            return back()->withErrors(['amount' => 'The amount is not transfer the money is not enough.'])->withInput();

        }

        return view('frontend.scan_and_pay_transfer_confirm', compact('amount', 'description', 'fromuser', 'to_account'));
    }
    public function scanAndPayTransferComplete(TransferValidate $request)
    {
        $authuser = Auth::user();
        $fromuser = $authuser;
        $amount = $request->amount;
        $description = $request->description;

        if ($request->amount < 1000) {
            return back()->withErrors(['amount' => 'The amount must be greater than 1000 MMK'])->withInput();
        }

        $authuser = Auth::user();
        if ($authuser->phone == $request->to_phone) {
            return back()->withErrors(['to_phone' => 'The Phone Number is invalid']);

        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return back()->withErrors(['to_phone' => 'The Phone Number is invalid']);
        }
        if($fromuser->wallet->amount <=$amount){
            return back()->withErrors(['amount' => 'The amount is not transfer the money is not enough.'])->withInput();

        }

        if (!$fromuser->wallet || !$to_account->wallet) {
            return back()->withErrors(['fail' => 'Something Wrong']);

        }

        DB::beginTransaction();
        try {
            $fromuser_wallet = $fromuser->wallet;
            $fromuser_wallet->decrement('amount', $amount);
            $fromuser_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refnumber();

            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxid();
            $from_account_transaction->user_id = $fromuser->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxid();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->source_id = $fromuser->id;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            $title='E-money Transfer';
            $message='E-money transfer ' . $amount . ' MMK to '. $to_account->name;
            $sourceable_id=$from_account_transaction->id;
            $sourceable_type=Transaction::class;
            $web_link=url('/transaction/' . $from_account_transaction->trx_id);
            $deep_link=[
                'target'=>'transaction_detail',
                'parameter'=>[
                    'trx_id'=>$from_account_transaction->trx_id,
                ],
            ];

            Notification::send([$fromuser], new GeneralNotification($title,$message,$sourceable_id,$sourceable_type,$web_link,$deep_link));

            $title='E-money Received';
            $message='E-money received ' . $amount .' MMK to '.$fromuser->name;
            $sourceable_id=$to_account_transaction->id;
            $sourceable_type=Transaction::class;
            $web_link=url('/transaction/' . $to_account_transaction->trx_id);
            $deep_link=[
                'target'=>'transaction_detail',
                'parameter'=>[
                    'trx_id'=>$to_account_transaction->trx_id,
                ],
            ];

            Notification::send([$to_account], new GeneralNotification($title,$message,$sourceable_id,$sourceable_type,$web_link,$deep_link));

            DB::commit();
            return redirect('/transaction/' . $from_account_transaction->trx_id)->with('success', 'Transfer Successfully.');

        } catch (\exception $e) {
            DB::rollBack();
            return back()->withErrors(['fail' => 'Something Wrong.' . $e->getMessage()])->withInput();
        }

    }

    public function languageSwitcher(Request $request){
        session(['language'=>$request->language]);
        return [
            'result'=>1,
            'message'=>'success'
        ];
    }
}
