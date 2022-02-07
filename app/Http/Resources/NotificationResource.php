<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id'=>$this->id,
            'title'=>$this->data['title'],
            'message'=>$this->data['message'],
            'date'=>Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'read'=>is_null($this->read_at ) ? 0:1,
        ];
    }
}
