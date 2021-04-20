@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Profile Anda')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <div class="row">
            @foreach ($data as $item)
            <div class="col-sm-12 col-md-6 mt-2">
                <div class="card">
                    <div class="card-header"><h4>{{$item->nama_user}}</h4></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="/images/{{$item->gambar_cabang}}" class="img-fluid" alt="">
                            </div>
                            <div class="col-md-8">
                                Informasi User
                                <table>
                                    <tr>
                                        <td><h5>Username</h5></td>
                                        <td><h5>:</h5></td>
                                        <td><h5>{{$item->username}}</h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5>Password</h5></td>
                                        <td><h5>:</h5></td>
                                        <td><h5>{{$item->password1}}</h5></td>
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
