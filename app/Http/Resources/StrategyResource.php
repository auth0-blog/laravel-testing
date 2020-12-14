<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StrategyResource extends JsonResource {

    public function toArray($request) {

        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'tenure'     => $this->tenure,
            'yield'      => $this->yield,
            'relief'     => $this->relief,
            'investments' => InvestmentResource::collection($this->investments),
            'created_at' => (string)$this->created_at,
        ];

    }
}
