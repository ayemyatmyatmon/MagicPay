<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Transaction;
use App\helper\Response;
use App\helper\UUIDGenerate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProfileResource;
use App\Notifications\GeneralNotification;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\NotificationDetailResource;

class PageController extends Controller
{
    public function profile(){
        $user=auth()->user();
        $data=new ProfileResource($user);
        return Response::success('success',$data);
    }
    public function transaction(Request $request){
        $user=auth()->user();
        $transactions = Transaction::orderBy('created_at', 'DESC')->with('user', 'source')->where('user_id', $user->id);
        if($request->date){
            $transactions=$transactions->whereDate('created_at',$request->date);
        }
        if($request->type){
            $transactions=$transactions->where('type',$request->type);

        }
        $transactions=$transactions->paginate(3);
        $data=TransactionResource::collection($transactions)->additional(["result"=>1,'message'=>'success']);
        return $data;
    }
    public function transactionDetail($trx_id){
        $user=auth()->user();
        $transactions=Transaction::with('user','source')->where('user_id',$user->id)->where('trx_id',$trx_id)->firstOrFail();
        $data=new TransactionDetailResource($transactions);
        return Response::success('success',$data);
    }
    public function notification(){
            $user=auth()->user();
           $notifications=$user->notifications()->paginate(3);
           $data=NotificationResource::collection($notifications)->additional(['result'=>1,'message'=>'success']);
           return $data;

    }
    public function notificationDetail($id){
        $authuser=auth()->user();
        $notification=$authuser->notifications()->where('id',$id)->firstOrFail();
        $notification->markAsRead();
        $data=new NotificationDetailResource($notification);
        return Response::success('success',$data);
    }
    public function toAccountVerify(Request $request){
        if($request->phone){
            $authuser=auth()->user();
            if($request->phone!=$authuser->phone){
                $user=User::where('phone',$request->phone)->first();
                return Response::success('success',['name'=>$user->name,'phone'=>$user->phone]);
            }
        }
        return Response::fail('The Phone number is invalid',null);
    }
    public function transferConfirmation(Request $request){
        $authuser = auth()->user();
        $fromuser = $authuser;
        $amount = $request->amount;
        $description = $request->description;
        if ($request->amount < 1000) {
            return Response::fail('The amount must be greater than 1000 MMK',null);
        }

        if ($authuser->phone == $request->phone) {
            return Response::fail('The Phone Number is invalid',null);


        }

        $to_account = User::where('phone', $request->phone)->first();
        if (!$to_account) {
            return Response::fail('The Phone Number is invalid',null);

        }
        if($fromuser->wallet->amount <=$amount){
            return Response::fail('The amount is not transfer the money is not enough.',null);
        }
        return Response::success('success',[
            'from_account_name'=>$fromuser->name,
            'from_account_phone'=>$fromuser->phone,
            'to_account_name'=>$to_account->name,
            'to_account_phone'=>$to_account->phone,
            'amount'=>$amount,
            'description'=>$description,

        ]);
    }
    public function transferComplete(Request $request){

       if(!$request->password){
           return Response::fail('Please Fill your Password.',null);
       }
       $authuser = auth()->user();

        if (!Hash::check($request->password, $authuser->password)) {
           return Response::fail('The Password is incorrect.',null);
        }
        $fromuser = $authuser;
        $amount = $request->amount;
        $description = $request->description;

        if ($request->amount < 1000) {
            return Response::fail('The amount must be greater than 1000 MMK',null);
        }

        if ($fromuser->phone == $request->phone) {
            return Response::fail('The Phone Number is invalid',null);


        }

        $to_account = User::where('phone', $request->phone)->first();
        if (!$to_account) {
            return Response::fail('The Phone Number is invalid',null);

        }
        if($fromuser->wallet->amount <=$amount){
            return Response::fail('The amount is not transfer the money is not enough.',null);


        }

        if (!$fromuser->wallet || !$to_account->wallet) {
            return Response::fail('Something Wrong.',null);


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
            return Response::success('Successfully Transfered.',['trx_id'=>$from_account_transaction->trx_id]);

        } catch (\exception $e) {
            DB::rollBack();
            return back()->withErrors(['fail' => 'Something Wrong.' . $e->getMessage()])->withInput();
        }
    }
    public function scanAndPayTransfer(Request $request){
        $from_account=auth()->user();
        $to_account=User::where('phone',$request->phone)->first();

        if(!$to_account){
            return Response::fail('QR code is invalid',null);
        }
        return Response::success('success',[
            'from_account_name'=>$from_account->name,
            'from_account_phone'=>$from_account->phone,
            'to_account_name'=>$to_account->name,
            'to_account_phone'=>$to_account->phone,

        ]);
    }

    public function scanAndPayConfirmation(Request $request){
        $authuser = Auth::user();
        $fromuser = $authuser;
        $amount = $request->amount;
        $description = $request->description;
        if ($request->amount < 1000) {
            return Response::fail('The amount must be greater than 1000 MMK',null);
        }

        if ($authuser->phone == $request->phone) {
            return Response::fail('The Phone Number is invalid',null);


        }

        $to_account = User::where('phone', $request->phone)->first();
        if (!$to_account) {
            return Response::fail('The Phone Number is invalid',null);

        }
        if($fromuser->wallet->amount <=$amount){
            return Response::fail('The amount is not transfer the money is not enough.',null);
        }

        return Response::success('success',[
            'from_account_name'=>$fromuser->name,
            'from_account_phone'=>$fromuser->phone,
            'to_account_name'=>$to_account->name,
            'to_account_phone'=>$to_account->phone,
            'amount'=>$amount,
            'description'=>$description,

        ]);
    }

    public function scanAndPayTransferComplete(Request $request){

        if(!$request->password){
            return Response::fail('Please Fill your Password.',null);
        }
        $authuser = auth()->user();

         if (!Hash::check($request->password, $authuser->password)) {
            return Response::fail('The Password is incorrect.',null);
         }
         $fromuser = $authuser;
         $amount = $request->amount;
         $description = $request->description;

         if ($request->amount < 1000) {
             return Response::fail('The amount must be greater than 1000 MMK',null);
         }

         if ($fromuser->phone == $request->phone) {
             return Response::fail('The Phone Number is invalid',null);


         }

         $to_account = User::where('phone', $request->phone)->first();
         if (!$to_account) {
             return Response::fail('The Phone Number is invalid',null);

         }
         if($fromuser->wallet->amount <=$amount){
             return Response::fail('The amount is not transfer the money is not enough.',null);


         }

         if (!$fromuser->wallet || !$to_account->wallet) {
             return Response::fail('Something Wrong.',null);


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
             return Response::success('Successfully Transfered.',['trx_id'=>$from_account_transaction->trx_id]);

         } catch (\exception $e) {
             DB::rollBack();
             return back()->withErrors(['fail' => 'Something Wrong.' . $e->getMessage()])->withInput();
         }
     }

}
