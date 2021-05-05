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
                    @if ($message = Session::get('status'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    @if ($message = Session::get('error'))
                    <div class="alert alert-success alert-block">
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
                    <td>{{$bk['nama_member']}}</td>
                    <td>
                        {{-- @php
                            $tes = explode(",",$bk->id_jam);
                        @endphp
                        @foreach ($tes as $b)
                            @php
                                $datass = DB::table('tbl_jam')->where('id_jam', $b)->first();
                                echo  "-".$datass->jam;
                            @endphp
                        @endforeach --}}
                        {{$bk['jam']}}
                    </td>
                    <td>{{tanggal_indonesia($bk['jadwal'])}}</td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <form method="POST" action="{{ route('removebooking', [$bk['id_booking']]) }}">
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
            // console.log(cek);
            // if(cek == '')
            // {
            //     var tes = ''
            // }else{
            //     var tes = cek.id_jam.split(",")
            // }
            // console.log(tes);

            // if(tes != '')
            // {
            //     for(var i = 0; i < tes.length; i++){
            //         document.getElementById(tes[i]).checked = true;
            //         document.getElementById(tes[i]).disabled = true;
            //     }
            // } else {
                var list_jam = document.getElementsByName("jam[]");
                // reset centang jam
                for (var x = 0; x < list_jam.length; x++) {
                    list_jam[x].checked = false;
                }
            // }
        })
    }

    function members()
    {
       var id = $('#member').val()
       axios.get("{{url('/api/cekpaket')}}/"+id)
        .then(function(res){
            var data = res.data
            console.log(data)
            $('#paket').val(data.sisa_paket)
        })
    }

    $('#myTable').DataTable()
</script>
@endsection
