<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 全アクションにログイン必須
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Product::with('company');

        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
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

    public function store(Request $request)
    {
            $request->validate([
                'company_id' => 'required|integer',
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'comment' => 'nullable|string',
                'img_path' => 'nullable|image',
            ], [
                'product_name.required' => '商品名を入力してください。',
                'product_name.max' => '商品名は255文字以内で入力してください。',
                'company_id.required' => 'メーカー名を選択してください。',
                'price.required' => '価格を入力してください。',
                'price.numeric' => '価格は数字で入力してください。',
                'stock.required' => '在庫数を入力してください。',
                'stock.integer' => '在庫数は整数で入力してください。',
                'img_path.image' => '画像ファイルを選択してください。',
            ]);

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

    public function update(Request $request, $id)
    {
            $request->validate([
                'company_id' => 'required|integer',
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'comment' => 'nullable|string',
                'img_path' => 'nullable|image',
            ], [
                'product_name.required' => '商品名を入力してください。',
                'product_name.max' => '商品名は255文字以内で入力してください。',
                'company_id.required' => 'メーカー名を選択してください。',
                'price.required' => '価格を入力してください。',
                'price.numeric' => '価格は数字で入力してください。',
                'stock.required' => '在庫数を入力してください。',
                'stock.integer' => '在庫数は整数で入力してください。',
                'img_path.image' => '画像ファイルを選択してください。',
            ]);
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

            return redirect()->route('productList.index')->with('success', '商品が削除されました');
        } catch (\Exception $e) {
            Log::error('商品削除エラー: ' . $e->getMessage());
            return back()->with('error', '商品削除中にエラーが発生しました');
        }
    }
}