<?php

namespace App\Http\Controllers;

use App\Models\Product; // Product モデルをインポート
use App\Models\Company;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 全アクションにログイン必須
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Product::with('company'); // 会社情報も一緒に取得

        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $products = $query->get();
        $companies = Company::all(); // 会社一覧も取得

        return view('productList', compact('products', 'companies'));
    }

    public function create()
    {
        $companies = Company::all(); // 新規登録画面用
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
        ]);

        $data = $request->only(['company_id', 'product_name', 'price', 'stock', 'comment']);

        if ($request->hasFile('img_path')) {
            $data['img_path'] = $request->file('img_path')->store('images', 'public');
        }

        Product::create($data);

        return redirect()->route('productList.index')->with('success', '商品を登録しました。');
    }
    public function show($id)
    {
        // 商品と関連するメーカー情報を事前に読み込む
    $product = Product::with('company')->findOrFail($id);

    return view('detail', compact('product'));
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all(); // 会社情報を取得

        return view('edit', compact('product', 'companies')); // editビューを返す
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
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only(['company_id', 'product_name', 'price', 'stock', 'comment']));

        if ($request->hasFile('img_path')) {
        // 画像がアップロードされていれば、古い画像を削除して新しい画像を保存
        if ($product->img_path) {
            \Storage::delete('public/' . $product->img_path);
        }
        $product->img_path = $request->file('img_path')->store('images', 'public');
        }

        $product->save();

        return redirect()->route('productList.index')->with('success', '商品情報が更新されました');
    } 
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete(); // 商品を削除

        return redirect()->route('productList.index')->with('success', '商品が削除されました');
    }
}
