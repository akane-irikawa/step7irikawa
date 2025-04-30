{{-- resources/views/detail.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">商品情報詳細画面</div>

                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>商品情報ID</th>
                            <td>{{ $product->id }}</td>
                        </tr>
                        <tr>
                            <th>商品画像</th>
                            <td>
                                @if ($product->img_path)
                                    <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" class="img-fluid" style="max-width: 200px;">
                                @else
                                    <p>画像はありません</p>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>商品名</th>
                            <td>{{ $product->product_name }}</td>
                        </tr>
                        <tr>
                            <th>メーカー</th>
                            <td>{{ $product->company->company_name ?? '不明' }}</td> <!-- メーカー情報 -->
                        </tr>
                        <tr>
                            <th>価格</th>
                            <td>¥{{ number_format($product->price) }}</td>
                        </tr>
                        <tr>
                            <th>在庫数</th>
                            <td>{{ $product->stock }} 個</td>
                        </tr>
                        <tr>
                            <th>コメント</th>
                            <td>{{ $product->comment ?? 'なし' }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <a href="{{ route('productList.edit', $product->id) }}" class="btn btn-warning">編集</a>
                        <a href="{{ route('productList.index') }}" class="btn btn-secondary">戻る</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
