<?php

namespace App\Application\DB;

use Illuminate\Http\Request;

trait OrderByData
{
    public function OrderByData(Request $request): object
    {
        $order = new class{};
        if( $request->has("type_order") && is_string($request->type_order)
            && in_array($request->type_order,$this->TypeOrder())){
            $order->type = $request->type_order;
        }else{
            $order->type = "id";
        }
        if ($request->has("latest")&&is_bool($request->latest)){
            $order->latest = $request->latest ? "desc" : "asc";
        }else{
            $order->latest = "desc";
        }
        return $order;
    }

    private function TypeOrder(): array
    {
        return [
            "id","name","children"
        ];
    }
}
