@extends('layouts.master')
@section('title', 'Chat')

@section('css')
    <style>
        .chat-messages-container {
            height: calc(100vh - 250px);
            overflow-y: auto;
        }

        @media (min-width: 992px) {

            /* Ukuran layar besar (>= 992px) */
            .chat-messages-container {
                height: calc(100vh - 350px);
            }
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-center align-items-center flex-column mx-n2 mx-sm-n4 mt-n4">
        <div class="col-12 col-sm-9 col-md-10 col-lg-10 overflow-hidden">
            <div class="d-flex flex-column h-100">
                <!-- Chat Header -->
                <div class="position-relative bg-white rounded">
                    <div class="p-2 p-sm-3 mt-2">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <div class="d-flex align-items-center border-bottom pb-2">
                                    <!-- Back Button -->
                                    <div class="flex-shrink-0 me-2 me-sm-3">
                                        <a href="{{ route('manajemenKasus.show', ['id' => $kasus->id]) }}"
                                            class="user-chat-remove fs-18 p-1">
                                            <i class="ri-arrow-left-s-line align-bottom"></i>
                                        </a>
                                    </div>
                                    <!-- User Info -->
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="flex-shrink-0 chat-user-img online align-self-center me-2 me-sm-3 d-sm-block">
                                                <i class="ri-chat-1-fill" style="font-size: 25px;"></i>
                                                <span class="user-status"></span>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="text-truncate mb-0 fs-15 fs-sm-16">
                                                    <a class="text-reset username" data-bs-toggle="offcanvas"
                                                        href="#userProfileCanvasExample">
                                                        {{ $kasus->user ? $kasus->user->name : '-' }}

                                                        @foreach ($kasus->anggota_penanganans as $member)
                                                            , {{ $member->user->name }}
                                                        @endforeach
                                                        .
                                                    </a>
                                                </h5>
                                                <p class="text-truncate text-muted fs-12 fs-sm-14 mb-0">
                                                    <small>Warga dan Anggota Penanganan Kasus</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages Area - Made Scrollable -->
                <div class="chat-messages-container bg-white" style="overflow-y: auto;">
                    <div class="chat-conversation p-2 p-sm-3" id="chat-conversation">
                        <ul class="list-unstyled chat-conversation-list" id="channel-conversation">
                            @forelse ($chats as $chat)
                                @php
                                    $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get(
                                        $chat->user->avatar,
                                        $chat->user->name,
                                    );
                                @endphp

                                @if (auth()->user()->id == $chat->pengirim_id)
                                    <!-- Chat Message Right -->
                                    <li class="d-flex align-items-start justify-content-end mb-3">
                                        <div class="text-end me-0 me-sm-2">
                                            <p class="mb-1 fs-12 fs-sm-14"><strong>Saya</strong></p>
                                            <div class="bg-primary text-white p-2 rounded mb-1 chat-bubble">
                                                {{ $chat->pesan }}
                                            </div>
                                            <small class="text-muted fs-10 fst-italic">
                                                {{ \Carbon\Carbon::parse($chat->created_at)->diffForHumans() }}
                                            </small>
                                        </div>
                                    </li>
                                @else
                                    <!-- Chat Message Left -->
                                    <li class="d-flex align-items-start gap-2 mb-3">
                                        <div class="chat-avatar">
                                            <img src="{{ $profilePhoto }}" class="rounded-circle avatar-sm" alt="">
                                        </div>
                                        <div class="chat-message ms-0 ms-sm-2">
                                            <p class="mb-1 fs-12 fs-sm-14"><strong>{{ $chat->user->name }}</strong></p>

                                            <p class="bg-info text-white p-2 rounded mb-1 chat-bubble">{{ $chat->pesan }}
                                            </p>
                                            <small
                                                class="text-muted fs-11">{{ \Carbon\Carbon::parse($chat->created_at)->diffForHumans() }}</small>
                                        </div>
                                    </li>
                                @endif
                            @empty
                                <span class="text-muted">Belum ada obrolan.</span>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Chat Input Section -->
                <div class="chat-input-section p-2 p-sm-3">
                    <form action="{{ route('manajemenKasus.sendChat', ['id' => $kasus->id]) }}" method="POST">
                        @csrf
                        <div class="row g-0 align-items-center">
                            <div class="col">
                                <input type="text" class="form-control pesan bg-light border-light" id="pesan"
                                    required name="pesan" placeholder="Tulis pesan ..." autocomplete="off">
                            </div>
                            <div class="col-auto ms-2">
                                <button type="submit" class="btn btn-success chat-send">
                                    <i class="ri-send-plane-2-fill align-bottom"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Cari elemen chat-messages-container
            var chatContainer = document.querySelector('.chat-messages-container');

            // Pastikan chatContainer ada dan berisi pesan
            if (chatContainer) {
                // Gulir ke bawah setelah konten selesai dimuat
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        });
    </script>
@endsection
