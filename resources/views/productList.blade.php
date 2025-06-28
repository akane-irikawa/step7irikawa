@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h1>商品一覧画面</h1>

    <!-- 検索フォーム -->
    <form id="searchForm" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label for="product_name" class="form-label">商品名</label>
            <input type="text" name="product_name" id="product_name" class="form-control" placeholder="商品名で検索">
        </div>

        <div class="col-md-3">
            <label for="company_id" class="form-label">メーカー名</label>
            <select name="company_id" id="company_id" class="form-select">
                <option value="">選択してください</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">価格</label>
            <div class="input-group">
                <input type="number" name="price_min" class="form-control" placeholder="最小">
                <span class="input-group-text">〜</span>
                <input type="number" name="price_max" class="form-control" placeholder="最大">
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label">在庫数</label>
            <div class="input-group">
                <input type="number" name="stock_min" class="form-control" placeholder="最小">
                <span class="input-group-text">〜</span>
                <input type="number" name="stock_max" class="form-control" placeholder="最大">
            </div>
        </div>

        <div class="col-12 text-end">
            <button type="submit" class="btn btn-secondary">検索</button>
        </div>
    </form>

    <a href="{{ route('productList.create') }}" class="btn btn-primary mt-3">新規登録</a>
    
    <table class="table mt-4 tablesorter" id="productTable">
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
        <tbody id="productTableBody">
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
                    <a href="{{ route('productList.show', $product->id) }}" class="btn btn-info btn-sm">詳細</a>
                    <form class="delete-form" data-id="{{ $product->id }}"method="POST" action="/productList/{{ $product->id }}" style="display:inline-block;">
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

@section('scripts')

<!-- jQuery本体 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- tablesorter用のCSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.default.min.css">

<!-- tablesorter本体 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>

<script>
$(function () {

    const deleteBaseUrl = "{{ url('/productList') }}";

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // tablesorterの初期化（初期表示はID降順）
    $("#productTable").tablesorter({
        sortList: [[0, 0]] // 0列目（ID）を降順でソート
    });

    // 検索フォームの送信時
    $('#searchForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route("productList.search") }}',
            type: 'GET',
            data: $(this).serialize(),
            success: function (data) {
                let rows = '';
                data.forEach(function (product) {
                    rows += `
                        <tr>
                            <td>${product.id}</td>
                            <td>${product.img_path ? `<img src="/storage/${product.img_path}" width="80">` : '画像なし'}</td>
                            <td>${product.product_name}</td>
                            <td>${product.price}円</td>
                            <td>${product.stock}</td>
                            <td>${product.company_name}</td>
                            <td>
                                <a href="/productList/${product.id}" class="btn btn-info btn-sm">詳細</a>
                                <form class="delete-form" data-id="${product.id}" method="POST" action="/productList/${product.id}" style="display:inline-block;">
                                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm">削除</button>
                                </form>
                            </td>
                        </tr>
                    `;

                    
                });
                $('#productTableBody').html(rows);

                // 検索結果表示後に tablesorter を再適用（初期ソートID降順）
                $("#productTable").trigger("update").trigger("sorton", [[[0,1]]]);
            },
            error: function () {
                alert('検索に失敗しました。');
            }
        });
    });
                 // 削除フォームのsubmitを非同期処理に置き換え
                $(document).on('submit', '.delete-form', function (e) {
                    e.preventDefault();

                    if (!confirm('本当に削除しますか？')) {
                        return;
                    }

                    const form = $(this);
                    const productId = form.data('id');
                    const url = `${deleteBaseUrl}/${productId}`;

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        // 削除成功したら行をフェードアウトしてテーブル更新
                        form.closest('tr').fadeOut(500, function () {
                            $(this).remove();
                            $("#productTable").trigger("update");
                        });
                        alert('削除しました。');
                    },
                    error: function () {
                        alert('削除に失敗しました。');
                    }
        });
    });
});
</script>
@endsection


