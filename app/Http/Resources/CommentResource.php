<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = new class{};
        $user->id = $this->id_user;
        $user->name = $this->first_name . " " . $this->last_name;
        $user->path_photo = $this->path_photo;
        return [
            "id" => $this->id,
            "id_article" => $this->id_article,
            "comment" => $this->comment,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "user" => $user
        ];
    }
}
