<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $unread_noti_count=$this->unreadNotifications()->count();

        return
        [
            'name'=>$this->name,
            'phone'=>$this->phone,
            'email'=>$this->email,
            'account_number'=>$this->wallet? $this->wallet->account_number : '',
            'amount'=>$this->wallet ? number_format($this->wallet->amount) :'',
            'profile'=>asset('frontend/img/woman.png'),
            'unread_noti_count'=>$unread_noti_count,
        ];

    }
}
