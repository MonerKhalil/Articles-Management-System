<?php

namespace App\Application\PrivateClasses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HandleJson
{
    /**
     * @param Request $request
     * @return int
     */
    public function NumberOfValues(Request $request): int
    {
        return ($request->has("num_values") &&
            is_numeric($request->num_values) && $request->num_values>0)
            ? $request->num_values : 10;
    }

    /**
     * @param string $name
     * @param mixed $paginate
     * @return JsonResponse
     */
    public function PaginateHandle(string $name, mixed $paginate): JsonResponse
    {
        $data = [
            $name => $paginate->items(),
            "current_page" => $paginate->currentPage(),
            "url_next_page" => $paginate->nextPageUrl(),
            "url_first_page" => $paginate->path()."?page=1",
            "url_last_page" => $paginate->path()."?page=".$paginate->lastPage(),
            "total_pages" => $paginate->lastPage(),
            "total_items" => $paginate->total()
        ];
        return $this->DataHandle($data,"paginate");
    }

    /**
     * @param mixed $data
     * @param string|null $name
     * @return JsonResponse
     */
    public function DataHandle(mixed $data , string $name = null): JsonResponse
    {
        return !is_null($name) ? response()->json(['data'=>[$name => $data]]) : response()->json(["data"=>$data]);
    }

    /**
     * @param mixed $messageError
     * @param string $name
     * @return JsonResponse
     */
    public function ErrorsHandle(string $name,mixed $messageError): JsonResponse
    {
        return response()->json([
            "errors" => [
                $name => $messageError
            ]
        ]);
    }
}
