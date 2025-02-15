@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endpush

@section('title', '商品一覧')

@section('content')
<div class="container">
    <div class="tab-container">
        <div class="tab-links">
            <a href="{{ route('items.index') }}" class="{{ request('tab') === 'mylist' ? '' : 'active' }}">おすすめ</a>
            <a href="{{ route('items.index', ['tab' => 'mylist']) }}" class="{{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
        <div class="tab-border"></div> <!-- 横幅いっぱいの線 -->
    </div>

    <main>
        <div class="item-list">
            @foreach ($items as $item)
                <a href="{{ route('items.detail', ['item_id' => $item->id]) }}" class="item-link">
                    <div class="item">
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
                        <p>{{ $item->name }}</p>
                        @if ($item->buyer)
                            <p class="sold">Sold</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </main>
</div>
@endsection
