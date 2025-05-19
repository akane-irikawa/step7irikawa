<!-- 商品一覧表示 -->
@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
<div class="container">
    <!-- 成功メッセージの表示 -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- エラーメッセージの表示 -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <h1>商品一覧画面</h1>

    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('productList.index') }}">
        <input type="text" name="product_name" placeholder="商品名で検索" value="{{ request('product_name') }}">
        <select name="company_id">
            <option value="">メーカー名</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                    {{ $company->company_name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary">検索</button>
    </form>

    <!-- 新規登録リンク -->
    <a href="{{ route('productList.create') }}" class="btn btn-primary mt-3">新規登録</a>

    <!-- 商品一覧テーブル -->
    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>メーカー名</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    @if ($product->img_path)
                        <img src="{{ asset('storage/' . $product->img_path) }}" width="80">
                    @else
                        画像なし
                    @endif
                </td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->price }}円</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->company->company_name ?? '不明' }}</td>
                <td>
                    <!-- 詳細ボタン -->
                    <a href="{{ route('productList.show', $product->id) }}" class="btn btn-info btn-sm">詳細</a>

                    <!-- 削除フォーム -->
                    <form action="{{ route('productList.destroy', $product->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('本当に削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
