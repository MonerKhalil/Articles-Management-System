<?php

namespace App\Http\Resources;

use App\Application\Application;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "id_parent" => $this->id_parent,
            "name" => Application::getApp()->getLang()==="ar" ? $this->name : $this->name_en,
            "description" => Application::getApp()->getLang()==="ar" ? $this->description : $this->description_en,
            "path_photo" => $this->path_photo,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
