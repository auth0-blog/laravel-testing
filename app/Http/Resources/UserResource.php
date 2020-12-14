<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {

    public function toArray($request) {

        return [
            'id'         => $this->id,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'created_at' => (string)$this->created_at,
            'email'      => $this->email,
            'wallet'     => new WalletResource($this->wallet)
        ];
    }
}
