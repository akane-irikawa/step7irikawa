<!-- 商品登録フォーム -->
@extends('layouts.app')

@section('title', '商品新規登録')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">商品新規登録画面</h1>

    <form action="{{ route('productList.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="product_name" class="form-label">商品名</label>
            <input type="text" class="form-control" id="product_name" name="product_name" required>
        </div>

        <div class="mb-3">
            <label for="company_id" class="form-label">メーカー名</label>
            <select class="form-select" id="company_id" name="company_id" required>
                <option value="">選択してください</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">価格</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">在庫数</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">コメント</label>
            <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="img_path" class="form-label">商品画像</label>
            <input type="file" class="form-control" id="img_path" name="img_path">
        </div>

        <button type="submit" class="btn btn-primary">新規登録</button>
        <a href="{{ route('productList.index') }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
