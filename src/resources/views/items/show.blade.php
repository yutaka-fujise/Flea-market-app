<h1>商品詳細</h1>

<div style="border:1px solid #ddd; padding:16px; border-radius:8px; max-width:700px;">
  <div style="display:flex; gap:16px;">
    <img
      src="{{ asset($item->image) }}"
      alt="{{ $item->name }}"
      style="width:220px; height:220px; object-fit:cover; border-radius:8px;"
    >

    <div>
      <h2 style="margin:0 0 8px;">{{ $item->name }}</h2>
      <div style="font-size:18px; font-weight:bold; margin-bottom:8px;">
        
        {{ number_format($item->price) }}円
      </div>
      @if ($item->orders_count > 0)
  <span style="margin-left:8px; padding:4px 8px; background:#111; color:#fff; border-radius:6px; font-weight:bold;">
    SOLD
  </span>
@endif

      <div style="margin-bottom:6px;">ブランド：{{ $item->brand }}</div>
      <div style="margin-bottom:6px;">出品者：{{ $item->user->name }}</div>
      @auth
<form action="{{ route('items.favorite', ['item_id' => $item->id]) }}" method="POST">
    @csrf
    <button type="submit"
        style="border:none; background:none; font-size:18px; cursor:pointer;
        color: {{ $isFavorited ? '#e11d48' : '#999' }};">
        ♥ {{ $item->favorites_count }}
    </button>
</form>
@else
<a href="/login" style="color:#999; font-weight:bold;">
    ♥ {{ $item->favorites_count }}
</a>
@endauth
    </div>
  </div>

  <hr style="margin:16px 0;">

  <h3>商品説明</h3>
  <p>{{ $item->description }}</p>

  <hr style="margin:16px 0;">

  <h3>コメント</h3>
@forelse ($item->comments as $comment)
  <div style="border-top:1px solid #eee; padding:8px 0;">

    <div style="font-weight:bold;">
      {{ $comment->user->name }}
      <span style="font-weight:normal; color:#777; font-size:12px;">
        {{ $comment->created_at->format('Y/m/d H:i') }}
      </span>
    </div>

    <div>{{ $comment->comment }}</div>

  </div>
@empty
  <p>コメントはまだありません。</p>
@endforelse
    @auth
<form action="{{ route('items.comment', ['item_id' => $item->id]) }}" method="POST">
  @csrf
  <textarea name="comment" rows="3" placeholder="コメントを書く"></textarea>
  <button type="submit">送信</button>
</form>
@else
<p><a href="/login">ログインしてコメントする</a></p>
@endauth
</div>

<p style="margin-top:16px;">
  <a href="/">← 一覧に戻る</a>
</p>
