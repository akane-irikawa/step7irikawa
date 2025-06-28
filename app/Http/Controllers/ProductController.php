<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $query = Product::with('company');

        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('price_min')) {
        $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
        $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('stock_min')) {
        $query->where('stock', '>=', $request->stock_min);
        }

        if ($request->filled('stock_max')) {
        $query->where('stock', '<=', $request->stock_max);
        }

        $products = $query->get();
        $companies = Company::all();

        return view('productList', compact('products', 'companies'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('create', compact('companies'));
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->only(['company_id', 'product_name', 'price', 'stock', 'comment']);

            if ($request->hasFile('img_path')) {
                $data['img_path'] = $request->file('img_path')->store('images', 'public');
            }

            Product::create($data);

            return redirect()->route('productList.index')->with('success', '商品を登録しました。');
        } catch (\Exception $e) {
            Log::error('商品登録エラー: ' . $e->getMessage());
            return back()->with('error', '商品登録中にエラーが発生しました');
        }
    }

    public function show($id)
    {
        $product = Product::with('company')->findOrFail($id);
        return view('detail', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('edit', compact('product', 'companies'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->only(['company_id', 'product_name', 'price', 'stock', 'comment']));

            if ($request->hasFile('img_path')) {
                if ($product->img_path) {
                    Storage::delete('public/' . $product->img_path);
                }
                $product->img_path = $request->file('img_path')->store('images', 'public');
            }

            $product->save();

            return redirect()->route('productList.index')->with('success', '商品情報が更新されました');
        } catch (\Exception $e) {
            Log::error('商品更新エラー: ' . $e->getMessage());
            return back()->with('error', '商品更新中にエラーが発生しました');
        }
    }
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->img_path && Storage::exists('public/' . $product->img_path)) {
                Storage::delete('public/' . $product->img_path);
            }

            $product->delete();

            if (request()->ajax()) {
                return response()->json(['message' => '削除しました。']);
            }

            return redirect()->route('productList.index')->with('success', '商品が削除されました');
        } catch (\Exception $e) {
            Log::error('商品削除エラー: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json(['message' => '削除に失敗しました。'], 500);
            }

            return back()->with('error', '商品削除中にエラーが発生しました');
        }
    }

    public function search(Request $request)
    {
        $query = Product::with('company');

        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('price_min')) {
        $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
        $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('stock_min')) {
        $query->where('stock', '>=', $request->stock_min);
        }

        if ($request->filled('stock_max')) {
        $query->where('stock', '<=', $request->stock_max);
        }

        $products = $query->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'price' => $product->price,
                'stock' => $product->stock,
                'company_name' => $product->company->company_name ?? '不明',
                'img_path' => $product->img_path,
                ];
            });
        return response()->json($products);
    }
}
