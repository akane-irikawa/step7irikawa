{{-- resources/views/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">商品情報編集画面</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('productList.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- 商品情報ID（表示のみ） --}}
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">商品情報ID</label>
                            <div class="col-md-6 pt-2">
                                {{ $product->id }}
                            </div>
                        </div>

                        {{-- 商品名 --}}
                        <div class="row mb-3">
                            <label for="product_name" class="col-md-4 col-form-label text-md-end">商品名</label>
                            <div class="col-md-6">
                                <input id="product_name" type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
                                @error('product_name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- メーカー名 --}}
                        <div class="row mb-3">
                            <label for="company_id" class="col-md-4 col-form-label text-md-end">メーカー</label>
                            <div class="col-md-6">
                                <select id="company_id" class="form-control @error('company_id') is-invalid @enderror" name="company_id" required>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" {{ $company->id == $product->company_id ? 'selected' : '' }}>
                                            {{ $company->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- 価格 --}}
                        <div class="row mb-3">
                            <label for="price" class="col-md-4 col-form-label text-md-end">価格</label>
                            <div class="col-md-6">
                                <input id="price" type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- 在庫数 --}}
                        <div class="row mb-3">
                            <label for="stock" class="col-md-4 col-form-label text-md-end">在庫数</label>
                            <div class="col-md-6">
                                <input id="stock" type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', $product->stock) }}" required>
                                @error('stock')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- コメント --}}
                        <div class="row mb-3">
                            <label for="comment" class="col-md-4 col-form-label text-md-end">コメント</label>
                            <div class="col-md-6">
                                <textarea id="comment" class="form-control" name="comment">{{ old('comment', $product->comment) }}</textarea>
                            </div>
                        </div>

                        {{-- 商品画像 --}}
                        <div class="row mb-3">
                            <label for="img_path" class="col-md-4 col-form-label text-md-end">商品画像</label>
                            <div class="col-md-6">
                                @if ($product->img_path)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" style="max-width: 200px;">
                                    </div>
                                @endif
                                <input id="img_path" type="file" class="form-control @error('img_path') is-invalid @enderror" name="img_path">
                                @error('img_path')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- ボタン --}}
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">更新する</button>
                                <a href="{{ route('productList.show', $product->id) }}" class="btn btn-secondary ml-2">戻る</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
