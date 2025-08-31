@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-secondary-dark dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-users"></i>
                            </div>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">
                        {{ $counts['users'] }}
                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                    </span>
                    <p class="mb-0 opacity-50">Total Users</p>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card bg-primary-dark dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-user-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content" id="chart-tab-tabContent">
                        <div class="tab-pane show active" id="chart-tab-home" role="tabpanel"
                            aria-labelledby="chart-tab-home-tab" tabindex="0">
                            <div class="row">
                                <div class="col-6">
                                    <span class="text-white d-block f-34 f-w-500 my-2">
                                        {{ $counts['staff'] }}
                                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                                    </span>
                                    <p class="mb-0 opacity-50">Total Staffs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="card bg-primary-dark dashnum-card dashnum-card-small text-white overflow-hidden">
                <span class="round bg-primary small"></span>
                <span class="round bg-primary big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg">
                            <i class="text-white ti ti-book"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="text-white mb-1">{{ $counts['quran_chapters'] }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Quran Chapters</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card dashnum-card dashnum-card-small overflow-hidden">
                <span class="round bg-warning small"></span>
                <span class="round bg-warning big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg bg-light-warning">
                            <i class="text-warning ti ti-credit-card"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="mb-1">{{ $counts['quran_verses'] }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Quran Verses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="card bg-info dashnum-card dashnum-card-small text-white overflow-hidden">
                <span class="round bg-info small"></span>
                <span class="round bg-info big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg">
                            <i class="text-info ti ti-book"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="text-white mb-1">{{ $counts['hadith_books'] }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Hadith Books</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="card bg-success dashnum-card dashnum-card-small text-white overflow-hidden">
                <span class="round bg-success small"></span>
                <span class="round bg-success big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg">
                            <i class="text-success ti ti-notes"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="text-white mb-1">{{ $counts['hadith_chapters'] }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Hadith Chapters</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="card bg-warning dashnum-card dashnum-card-small overflow-hidden">
                <span class="round bg-warning small"></span>
                <span class="round bg-warning big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg bg-light-warning">
                            <i class="text-warning ti ti-credit-card"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="mb-1 text-white">{{ $counts['hadith_verses'] }}</h4>
                            <p class="mb-0 opacity-75 text-sm text-white">Hadith Verses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
