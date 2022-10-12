<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyCommentResource extends JsonResource
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
            "id_comment" => $this->id_comment,
            "reply" => $this->reply,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "user" => $user
        ];
    }
}
