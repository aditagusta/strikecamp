@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Contact Cabang')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <div class="row">
            @foreach ($data as $item)
            <div class="col-sm-12 col-md-6 mt-2">
                <div class="card">
                    <div class="card-header"><h4>{{$item->nama_cabang}}</h4></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="/images/{{$item->gambar_cabang}}" class="img-fluid" alt="">
                            </div>
                            <div class="col-md-8">
                                Informasi Cabang
                                <table>
                                    <tr>
                                        <td>No. Telepon</td>
                                        <td>:</td>
                                        <td>{{$item->telepon}}</td>
                                    </tr>
                                    <tr>
                                        <td>Lokasi</td>
                                        <td>:</td>
                                        <td>{{$item->lokasi}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
