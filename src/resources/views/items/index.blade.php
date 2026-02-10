<h1>商品一覧</h1>

<form method="GET" action="/">
  <input type="text" name="q" value="{{ request('q') }}" placeholder="キーワードで検索">
  <button type="submit">検索</button>

  {{-- mylist状態なら維持したい場合 --}}
  @if(request('tab') === 'mylist')
    <input type="hidden" name="tab" value="mylist">
  @endif
</form>

@foreach ($items as $item)
  <div style="
    border:1px solid #ddd;
    padding:16px;
    border-radius:8px;
    display:flex;
    gap:16px;
    align-items:center;
    margin:12px 0;
    background:#fff;
  ">
  

    {{-- 商品画像 --}}
    <a href="{{ route('items.show', ['item_id' => $item->id]) }}">
  <img
    src="{{ asset($item->image) }}"
    alt="{{ $item->name }}"
    style="width:120px; height:120px; object-fit:cover; border-radius:8px;"
  >
</a>

    {{-- 商品情報 --}}
    <div>
      <div style="font-weight:bold; font-size:18px;">
        {{ $item->name }}
      </div>
      @if ($item->orders_count > 0)
  <span style="margin-left:8px; padding:2px 6px; background:#111; color:#fff; border-radius:6px; font-weight:bold; font-size:12px;">
    SOLD
  </span>
@endif

      <div style="font-size:16px; margin:4px 0;">
        {{ number_format($item->price) }}円
      </div>

      <div style="color:#555;">
        出品者：{{ $item->user->name }}
      </div>

      <div style="color:#e0245e; font-weight:bold;">
        ♥ {{ $item->favorites_count }}
      </div>
    </div>
    

  </div>
@endforeach
