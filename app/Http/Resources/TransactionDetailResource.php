<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
        [
            'trx_id'=>$this->trx_id,
            'ref_no'=>$this->ref_no,
            'type'=>$this->type,
            'amount'=>number_format($this->amount,2),
            'date'=>Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'description'=>$this->description,


        ];
    }

}
