@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Booking')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <div class="mb-2">
            <div class="card">
                <div class="card-body">
                    {{-- @dd(session()); --}}

                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif

                    @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif

                    <form action="{{route('addbooking')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Tanggal Latihan</label>
                                    <select name="tanggal" id="tanggal" class="form-control" onchange="hari()">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach ($jadwal as $itemjadwal)
                                        <option value="{{$itemjadwal->id_jadwal}}">{{tanggal_indonesia($itemjadwal->jadwal)}} - {{$itemjadwal->nama_trainer}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="">Member</label>
                                            <select name="member" id="member" class="form-control" onchange="members()">
                                                <option value="">-- Silahkan Pilih --</option>
                                                @foreach ($member as $itemmember)
                                                <option value="{{$itemmember->id_member}}">{{$itemmember->nama_member}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="">Sisa Paket</label>
                                            <input type="text" class="form-control" id="paket" name="paket" readonly>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-success" type="submit">Simpan</button>
                            </div>
                            <div class="col-sm-12 col-md-6" id="agenda">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <table id="myTable" class="table table-striped table-bordered table-responsive">
            <thead>
                <tr style="text-align: center;">
                    <th style="width: 2%">No</th>
                    <th style="width: 20%">Nama Member</th>
                    <th style="width: 20%">Jam</th>
                    <th style="width: 20%">Tanggal Latihan</th>
                    <th style="width: 18%">Aksi</th>
                </tr>
            </thead>
            <tbody>
               @foreach ($booking as $no => $bk)
                <tr>
                    <td>{{$no+1}}</td>
                    <td>{{$bk->nama_member}}</td>
                    <td>{{$bk->id_jam}}
                    <td>{{tanggal_indonesia($bk->jadwal)}}</td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <form method="POST" action="{{ route('removebooking', [$bk->id_booking]) }}">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
               @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    function hari()
    {
        var id = $('#tanggal').val()
        $('#agenda').load("{{route('tableagenda')}}/" + id)
        axios.post("{{url('/api/cekjam')}}",{
            'id_jadwal':id
        }).then(function(res){
            var cek = res.data;
            // console.log(cek[0].id_jam)
            // console.log(cek)
            var tes = cek[0].id_jam.split(",")
            console.log(tes);

        // Cara 1
            for(var i = 0; i < tes.length; i++){
                document.getElementById(tes[i]).checked = true;
                document.getElementById(tes[i]).disabled = true;
            }
        })
    }

    function members()
    {
       var id = $('#member').val()
       axios.get("{{url('/api/cekpaket')}}/"+id)
        .then(function(res){
            var data = res.data
            $('#paket').val(data.paket)
        })
    }

    $('#myTable').DataTable()
</script>
@endsection