@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endsection

@section('content')

<div class="profile page">

    <h1 class="profile-title">
        プロフィール設定
    </h1>

    <form
        action="{{ route('mypage.profile.update') }}"
        method="POST"
        class="profile-form"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="profile-photo">

            <div class="profile-avatar">

                <img
                    id="profile-preview"
                    class="profile-avatar__img"
                    src="{{ optional($profile)->profile_image ? asset('storage/'.$profile->profile_image) : '' }}"
                    alt="プロフィール画像"
                    style="{{ optional($profile)->profile_image ? '' : 'display:none;' }}"
                >

            </div>

            <label class="profile-photo__btn">
                画像を選択する

                <input
                    id="profile-image-input"
                    type="file"
                    name="profile_image"
                    class="profile-file"
                    accept="image/*"
                >
            </label>

            @error('profile_image')
                <p class="profile-error">
                    {{ $message }}
                </p>
            @enderror

        </div>


        <div class="profile-field">

            <label class="profile-label">
                ユーザー名
            </label>

            <input
                class="profile-input"
                type="text"
                name="name"
                value="{{ old('name', $user->name) }}"
            >

            @error('name')
                <p class="profile-error">
                    {{ $message }}
                </p>
            @enderror

        </div>


        <div class="profile-field">

            <label class="profile-label">
                郵便番号
            </label>

            <input
                class="profile-input"
                type="text"
                name="postal_code"
                placeholder="123-4567"
                value="{{ old('postal_code', optional($profile)->postal_code) }}"
            >

            @error('postal_code')
                <p class="profile-error">
                    {{ $message }}
                </p>
            @enderror

        </div>


        <div class="profile-field">

            <label class="profile-label">
                住所
            </label>

            <input
                class="profile-input"
                type="text"
                name="address"
                value="{{ old('address', optional($profile)->address) }}"
            >

            @error('address')
                <p class="profile-error">
                    {{ $message }}
                </p>
            @enderror

        </div>


        <div class="profile-field">

            <label class="profile-label">
                建物名
            </label>

            <input
                class="profile-input"
                type="text"
                name="building"
                value="{{ old('building', optional($profile)->building) }}"
            >

            @error('building')
                <p class="profile-error">
                    {{ $message }}
                </p>
            @enderror

        </div>


        <button
            type="submit"
            class="profile-submit"
        >
            更新する
        </button>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', () => {

    const input = document.getElementById('profile-image-input');
    const preview = document.getElementById('profile-preview');

    if (!input || !preview) return;

    input.addEventListener('change', (e) => {

        const file = e.target.files?.[0];

        if (!file) return;

        if (!file.type.startsWith('image/')) return;

        const url = URL.createObjectURL(file);

        preview.src = url;
        preview.style.display = 'block';

        preview.onload = () => URL.revokeObjectURL(url);

    });

});

</script>

@endsection