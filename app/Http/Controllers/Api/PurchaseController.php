<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        return DB::transaction(function () use ($request) {
            $product = Product::lockForUpdate()->find($request->product_id);

            if ($product->stock < $request->quantity) {
                return response()->json(['error' => '在庫不足です'], 400);
            }

            // 在庫を減らす
            $product->stock -= $request->quantity;
            $product->save();

            // 売上を登録
            Sale::create([
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
            ]);

            return response()->json(['message' => '購入が完了しました'], 200);
        });
    }
}

