<?php

namespace App\Http\Resources;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use Nette\Utils\DateTime;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     * @throws Exception
     */
    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image_url' => $this->image,
            'price' => $this->price,
            'updated_at' => (new DateTime($this->updated_at))->format('Y-m-d H:i:s')
        ];
    }
}
