<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentResource extends JsonResource {

    public function toArray($request) {

        return [
            'id'          => $this->id,
            'user_id'     => $this->user->id,
            'strategy_id' => $this->strategy->id,
            'successful'  => $this->successful,
            'amount'      => $this->amount,
            'returns'     => $this->returns,
            'created_at'  => (string)$this->created_at,
        ];
    }
}
