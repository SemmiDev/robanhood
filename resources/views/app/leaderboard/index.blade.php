@extends('layouts.master')
@section('title')
    @lang('translation.list-view')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="contactList">
                <div class="card-header">
                    <div class="d-flex align-items center">
                        <h5 class="mb-0 flex-grow-1">Leaderboard</h5>
                        <p class="text-muted mb-0">Terakhir di update: {{ now() }}</p>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('leaderboard') }}" id="leaderboard" class="row justify-content-start g-3">
                        @csrf
                        <div class="col-xxl-3 col-sm-6 d-flex gap-2">
                            <div class="search-box">
                                <input type="text" class="form-control search" name="q"
                                    value="{{ request()->get('q') }}" placeholder="Pencarian ...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                            <button type="submit" id="cari" class="mb-2 btn btn-primary">Cari</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-nowrap table-hover" id="customerTable">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th scope="col">Peringkat</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Total Poin</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all">
                                @forelse ($leaderboard as $lead)
                                    <tr style="{{auth()->user()->id == $lead->id ? 'background-color: #f7efd2' : ''}}">
                                        <td class="ranking fw-bold">
                                            <h4 class="{{ $rankings[$lead->id] <= 3 ? 'text-info' : '' }}">
                                                #{{ $rankings[$lead->id] }}
                                            </h4>
                                        </td>
                                        @php
                                            $profilePhoto = \App\Http\Controllers\Helpers\ProfilePhoto::get(
                                                $lead->avatar,
                                                $lead->name,
                                            );
                                        @endphp
                                        <td class="collection">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $profilePhoto }}" alt=""
                                                    class="avatar-xs rounded-circle object-fit-cover me-2">
                                                <a href="#" class="text-body">{{ $lead->name }}</a>
                                            </div>
                                        </td>
                                        <td>
                                            <h4 class="text-success mb-1 24h">
                                                ⭐️ {{ $lead->total_poin }}
                                            </h4>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/tasks-list.init.js') }}"></script>
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
