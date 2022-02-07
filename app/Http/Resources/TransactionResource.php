<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $title='';
        if($this->type==1){
            $title='From '. $this->source->name;
        }elseif($this->type==2){
            $title="To " .$this->source->name;
        }


        return [
            'trx_id'=>$this->trx_id,
            'date'=>Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'amount'=>number_format($this->amount ). 'MMK',
            'title'=>$title,
            'type'=>$this->type,//1->incom,2->expense


        ];
    }
}
