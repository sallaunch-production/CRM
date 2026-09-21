@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $hasPrimary = ($jum ?? 0) > 0;
    $hasSecondary = ($ques ?? 0) > 0;
    $hasResult = $hasPrimary || $hasSecondary;
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Cek Nopend</h1>
                <p class="crm-page-sub">Cari kantor pos / agent berdasarkan awalan Nopend atau Id Pos.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            @if($levelnya == 'compliance')
                <form class="crm-search-form" action="{{ route('cari_nopend') }}" method="POST">
            @elseif($levelnya == 'operasional')
                <form class="crm-search-form" action="{{ route('cari_nopend_operasional') }}" method="POST">
            @endif
                @csrf
                <div class="crm-search-field">
                    <label for="nopend">Nopend / Id Pos</label>
                    <input
                        type="text"
                        id="nopend"
                        name="nopend"
                        class="form-control"
                        required
                        maxlength="7"
                        minlength="2"
                        placeholder="Masukkan 2 sampai 5 karakter awal nopend"
                    >
                </div>
                <div class="crm-search-actions">
                    <button type="submit" class="crm-btn crm-btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        @if(!$hasResult)
            <div class="crm-empty">
                <div class="crm-empty-icon" aria-hidden="true">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h2 class="crm-empty-title">Mulai dengan Nopend</h2>
                <p class="crm-empty-text">
                    Masukkan 2–5 karakter awal Nopend, lalu tekan <strong>Cari</strong>
                    untuk menampilkan data kantor pos.
                </p>
            </div>
        @elseif($hasPrimary)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Hasil Nopend</h2>
                        <div class="crm-table-meta">Nopend <strong>{{ $nopend }}</strong> ada</div>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nopend</th>
                                <th>Agent ID</th>
                                <th>Nama Kantor Pos</th>
                                <th>Legacy</th>
                                <th>Alamat</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($query as $key => $transactions)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transactions->id_pos }}</td>
                                    <td>{{ $transactions->agent_id }}</td>
                                    <td>{{ $transactions->nama }}</td>
                                    <td>{{ $transactions->legacy }}</td>
                                    <td>{{ $transactions->alamat }}</td>
                                    <td>{{ $transactions->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($hasSecondary)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Hasil Nopend (KPRK)</h2>
                        <div class="crm-table-meta">Pencarian terkait KPRK</div>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example2" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nopend</th>
                                <th>Agent ID</th>
                                <th>Nama Kantor Pos</th>
                                <th>Legacy</th>
                                <th>Alamat</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($query_nopend as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->kpc }}</td>
                                    <td>Termasuk dalam KPRK : {{ $data->kprk }} . Agent Id : {{ $data->agent_id }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->legacy }}</td>
                                    <td>{{ $data->alamat }}</td>
                                    <td>{{ $data->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@stop

@section('js')
@include('partials.crm.datatable-boot')
<script>
    $(document).ready(function () {
        CrmDataTable.mount('#example');
        CrmDataTable.mount('#example2');
    });
</script>
@stop
