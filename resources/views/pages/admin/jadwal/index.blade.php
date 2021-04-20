@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Jadwal')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <button class="btn btn-sm btn-primary" onclick="tambah()"><i class="fa fa-plus"></i> Banner</button>
        <table id="table" class="table table-striped table-bordered table-responsive" style="width:100%; font-size:9px">
            <thead>
                <tr style="text-align: center; font-size:10px">
                    <th style="width: 2%;">No</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Trainer</th>
                    <th style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jadwal as $no => $itemjadwal)
                <tr>
                    <td>{{$no+1}}</td>
                    <td>{{tanggal_indonesia($itemjadwal->jadwal)}}</td>
                    <td>
                        @php
                            $datajam = DB::table('tbl_agenda')
                            ->join('tbl_jam','tbl_agenda.id_jam','tbl_jam.id_jam')
                            ->where('tbl_agenda.id_jadwal', $itemjadwal->id_jadwal)
                            ->get();
                        @endphp
                        @foreach ($datajam as $dj)
                        <li>{{$dj->jam}}</li>
                        @endforeach
                    </td>
                    <td>{{$itemjadwal->nama_trainer}}</td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <form method="POST" action="{{ route('removejadwal', [$itemjadwal->id_jadwal]) }}">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <button type="button" onclick="edited('{{$itemjadwal->id_jadwal}}','{{$itemjadwal->jadwal}}','{{$itemjadwal->id_trainer}}')" class="btn btn-sm btn-warning">Ubah</button>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <button type="button" class="btn btn-sm btn-success"onclick="tekan('{{$itemjadwal->id_jadwal}}')">Tambah Jam</button>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form Tambah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('addjadwal')}}" method="POST">
            @csrf
            <div class="form-group">
                <label for="">Tanggal</label>
                <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{Auth::guard('pusat')->user()->id_user}}">
                <input type="hidden" class="form-control" id="id_cabang" name="id_cabang" value="{{Auth::guard('pusat')->user()->id_cabang}}">
                <input type="date" class="form-control" id="jadwal" name="jadwal">
            </div>
            <div class="form-group">
                <label for="">Trainer</label>
                <select name="trainer" id="trainer" class="form-control">
                    <option value="">-- Silahkan Pilih --</option>
                    @foreach ($trainer as $itemtrainer)
                    <option value="{{$itemtrainer->id_trainer}}">{{$itemtrainer->nama_trainer}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" id="simpan">Simpan</button>
          </form>
        </div>
      </div>
    </div>
</div>

{{-- Modal Tambah Jam --}}
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form Tambah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('addjam')}}" method="POST">
            @csrf
            <div class="row">
                <input type="hidden" class="form-control" name="id_jadwal">
                @foreach ($jam as $itemjam)
                <div class="col-sm-3">
                    <input type="checkbox" name="jam[]"  value="{{$itemjam->id_jam}}">&nbsp&nbsp&nbsp{{$itemjam->jam}}
                </div>
                @endforeach
            </div>
            <button class="btn btn-sm btn-primary" type="submit">Simpan</button>
          </form>
        </div>
      </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form Ubah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('editjadwal')}}" method="POST">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="">Tanggal</label>
                <input type="hidden" class="form-control" id="id_jadwal" name="id_jadwal">
                <input type="hidden" class="form-control" id="user_" name="user_" value="{{Auth::guard('pusat')->user()->id_user}}">
                <input type="hidden" class="form-control" id="cabang_" name="cabang_" value="{{Auth::guard('pusat')->user()->id_cabang}}">
                <input type="date" class="form-control" id="jadwal_" name="jadwal_">
            </div>
            <div class="form-group">
                <label for="">Trainer</label>
                <select name="trainer_" id="trainer_" class="form-control">
                    <option value="">-- Silahkan Pilih --</option>
                    @foreach ($trainer as $itemtrainer)
                    <option value="{{$itemtrainer->id_trainer}}">{{$itemtrainer->nama_trainer}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </form>
        </div>
      </div>
    </div>
</div>
<script>
    $('#table').DataTable();
    function tambah()
    {
        $('#addModal').modal('show')
    }

    function edited(idjadwal,jadwal,idtrainer)
    {
        console.log(idjadwal);
        $('#id_jadwal').val(idjadwal)
        $('#jadwal_').val(jadwal)
        $('#trainer_').val(idtrainer)
        $('#editModal').modal('show')
    }

    function tekan(id)
    {
        $('#id').val(id)
        $('[name="id_jadwal"]').val(id)
        axios.post("{{url('/api/schedule')}}",{
            'id_jadwal':id
        }).then(function(res){
            var cek = res.data;
            console.log(cek)

        // Cara 1
            // for(var i = 0; i < cek.length; i++){
            //     document.getElementById(cek[i].id_jam).checked = true;
            // }

        // Cara 2
            if(cek != ''){
                var list_jam = document.getElementsByName("jam[]");
                console.log(cek[0].id_jam);
                // console.log(list_jam.length);
                // reset centang jam
                for (var x = 0; x < list_jam.length; x++) {
                    list_jam[x].checked = false;
                }
                for (var x = 0; x < list_jam.length; x++) {
                    for (var i = 0; i < cek.length; i++) {
                        console.log(cek[i].id_jam);
                        if (list_jam[x].value == cek[i].id_jam) {
                            list_jam[x].checked = true;
                        }
                    }
                }
            }else{
                var list_jam = document.getElementsByName("jam[]");
                // reset centang jam
                for (var x = 0; x < list_jam.length; x++) {
                    list_jam[x].checked = false;
                }
            }
            $('#scheduleModal').modal('show')
        })
    }
</script>
@endsection
