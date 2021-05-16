@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Jadwal')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12" id="table">

    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="addModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="judul"></h5>
          <button type="button" class="close" onclick="bersih()" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" method="POST">
            @csrf
            <div class="form-group">
                <label for="">Tanggal</label>
                <input type="hidden" class="form-control" id="id_jadwal" name="id_jadwal">
                <input type="date" class="form-control" id="jadwal" name="jadwal">
            </div>
            <button type="button" class="btn btn-primary" id="simpan">Simpan</button>
            <button type="button" class="btn btn-primary" id="update">Ubah</button>
          </form>
        </div>
      </div>
    </div>
</div>

<!-- Modal Tambah Trainer -->
<div class="modal fade" id="addTrainer" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="juduls"></h5>
          <button type="button" class="close" onclick="bersihs()" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" method="POST">
            @csrf
            <div class="form-group">
                <label for="">Tanggal</label>
                <input type="hidden" class="form-control" id="id_jadwals" name="id_jadwals">
                <input type="hidden" class="form-control" id="id_jadwal_trainer" name="id_jadwal_trainer">
                <input type="hidden" class="form-control" id="id_trainer" name="id_trainer">
                <input type="date" class="form-control" id="jadwal_trainer" name="jadwal_trainer" readonly>
            </div>
            <div class="form-group">
                <label for="">Pilih Trainer</label>
                <div class="row">
                    @foreach ($trainer as $t)
                    <div class="col-md-3">
                        <input type="checkbox" name="trainer[]" id="{{$t->id_trainer}}" value="{{$t->id_trainer}}"> &nbsp;&nbsp;&nbsp;{{$t->nama_trainer}}
                    </div>
                    @endforeach
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="simpans">Simpan</button>
            <button type="button" class="btn btn-primary" id="updates">Ubah</button>
          </form>
        </div>
      </div>
    </div>
</div>

<!-- Modal Tambah Jam -->
<div class="modal fade" id="addJam" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="judul_"></h5>
          <button type="button" class="close" onclick="bersih_()" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" method="POST">
            @csrf
            <div class="form-group">
                <label for="">Nama Trainer</label>
                <input type="hidden" class="form-control" id="id_jadwal_" name="id_jadwal_">
                <input type="hidden" class="form-control" id="id_trainer_" name="id_trainer_">
                <input type="text" class="form-control" id="nama_trainer" name="nama_trainer" readonly>
            </div>
            <div class="form-group">
                <label for="">Pilih Jam</label>
                <div class="row">
                    @foreach ($jam as $j)
                    <div class="col-md-3">
                        <input type="checkbox" name="jam[]" id="{{$j->id_jam}}" value="{{$j->id_jam}}"> &nbsp;&nbsp;&nbsp;{{$j->jam}}
                    </div>
                    @endforeach
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="simpan_">Simpan</button>
            {{-- <button type="button" class="btn btn-primary" id="update">Ubah</button> --}}
          </form>
        </div>
      </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#table').load("{{route('tablejadwal')}}")
    });

    function tambah()
    {
        document.getElementById('judul').innerHTML = "Form Tambah Data";
        $('#update').hide()
        $('#simpan').show()
        $('#addModal').modal('show')
    }

    $('#simpan').click(function () {
        var jadwal = $('#jadwal').val()
        axios.post("{{url('/api/tambah/jadwal/')}}",{
            jadwal: jadwal,
        }).then(function(res) {
            var isi = res.data
            toastr.info(isi.message)
            $('#addModal').modal('hide')
            bersih()
            $('#table').load("{{route('tablejadwal')}}")
        })
    });

    function edited(idjadwal,jadwal)
    {
        $('#id_jadwal').val(idjadwal)
        $('#jadwal').val(jadwal)
        $('#addModal').modal('show')
        $('#simpan').hide()
        $('#update').show()
        document.getElementById('judul').innerHTML = "Form Ubah Data"
    }

    $('#update').click(function () {
        var id_jadwal = $('#id_jadwal').val()
        var jadwal = $('#jadwal').val()
        axios.put("{{url('/api/jadwal/')}}",{
            id_jadwal: id_jadwal,
            jadwal: jadwal,
        }).then(function(res) {
            var isi = res.data
            toastr.info(isi.message)
            $('#addModal').modal('hide')
            bersih()
            $('#table').load("{{route('tablejadwal')}}")
        })
    });

    function trainer(idjadwal,jadwal) {
        $('#id_jadwals').val(idjadwal)
        $('#jadwal_trainer').val(jadwal)
        $('#addTrainer').modal('show')
        $('#simpans').show()
        $('#updates').hide()
        document.getElementById('juduls').innerHTML = "Form Tambah Trainer"
    }

    $('#simpans').click(function () {
        var id_jadwal = $('#id_jadwals').val()
        var id_trainer = new Array();
            $("input:checked").each(function() {
            id_trainer.push($(this).val());
        });
        axios.post("{{url('/api/tambah/trainer/')}}",{
            id_jadwal: id_jadwal,
            id_trainer: id_trainer,
        }).then(function(res) {
            var isi = res.data
            toastr.info(isi.message)
            $('#addTrainer').modal('hide')
            bersihs()
            $('#table').load("{{route('tablejadwal')}}")
        })
    });

    function edits(idjadwal,idtrainer,jadwal,idjadwaltrainer) {
        $('#id_jadwals').val(idjadwal)
        $('#id_jadwal_trainer').val(idjadwaltrainer)
        $('#id_trainer').val(idtrainer)
        $('#jadwal_trainer').val(jadwal)
        $('#simpans').hide()
        $('#updates').show()
        $('#addTrainer').modal('show')
        document.getElementById('juduls').innerHTML = "Form Ubah Trainer"
        axios.post("{{url('/api/cek/trainer/')}}",{
            id_jadwal: idjadwal,
            id_trainer: idtrainer,
        }).then(function(res){
            var isi = res.data
            if(isi != '')
            {
                for(var i = 0; i < isi.length; i++){
                    document.getElementById(isi[i].id_trainer).checked = true;
                }
            }
        })
    }

    $('#updates').click(function () {
        var idt = $('#id_trainer').val()
        var idjt = $('#id_jadwal_trainer').val()
        var idj = $('#id_jadwals').val()
        var trainer = new Array();
            $("input:checked").each(function() {
            trainer.push($(this).val());
        });
        axios.put("{{url('/api/edit/trainer')}}",{
            id_jadwal_trainer: idjt,
            id_trainer: idt,
            id_jadwal: idj,
            trainer:trainer,
        }).then(function(res) {
            var isi = res.data
            toastr.info(isi.message)
            bersihs()
            $('#addTrainer').modal('hide')
            $('#table').load("{{route('tablejadwal')}}")
        })
    });

    function jam(idjadwal,idtrainer,nama)
    {
        $('#id_jadwal_').val(idjadwal)
        $('#id_trainer_').val(idtrainer)
        $('#nama_trainer').val(nama)
        $('#addJam').modal('show')
        document.getElementById('judul_').innerHTML = "Form Tambah Jam"
        axios.get("{{url('api/agenda/trainer')}}/"+idjadwal+"/"+idtrainer)
        .then(function(res){
            var data = res.data
            if(data != '')
            {
                for(var i = 0; i < data.length; i++){
                    document.getElementById(data[i].id_jam).checked = true;
                }
            }
        })
        // axios.get('{{url("/api/trainer")}}/' + id)
        //     .then(function (res) {
        //         var isi = res.data
        //         console.log(isi.data)
        //         $('[name="id_trainer"]').val(isi.data.id_trainer)
        //         $('[name="nama_trainer"]').val(isi.data.nama_trainer)
        //         $('#editModal').modal('show');
        //     })
    }

    $('#simpan_').click(function () {
        var id_jadwal = $('#id_jadwal_').val()
        var id_trainer = $('#id_trainer_').val()
        var id_jam = new Array();
            $("input:checked").each(function() {
            id_jam.push($(this).val());
        });
        axios.post("{{url('/api/tambah/jam/')}}",{
            id_jadwal: id_jadwal,
            id_trainer: id_trainer,
            id_jam: id_jam,
        }).then(function(res) {
            var isi = res.data
            toastr.info(isi.message)
            $('#addJam').modal('hide')
            $('#table').load("{{route('tablejadwal')}}")
        })
    });

    function deleted(id)
    {
        axios.delete("{{url('/api/jadwal/')}}/"+id)
            .then(function(res){
            var data = res.data
            console.log(data);
            $('#table').load("{{route('tablejadwal')}}")
            toastr.info(data.message)
        })
    }

    function deletes(id)
    {
        axios.delete("{{url('/api/jadwal/trainer')}}/"+id)
            .then(function(res){
            var data = res.data
            console.log(data);
            $('#table').load("{{route('tablejadwal')}}")
            toastr.info(data.message)
        })
    }

    function bersih()
    {
        $('#id_jadwal').val('')
        $('#jadwal').val('')
        // $('[name="jam[]"]').prop('checked',false)
    }

    function bersihs()
    {
        $('#id_jadwals').val('')
        $('#id_jadwal_trainer').val('')
        $('#id_trainer').val('')
        $('#jadwal_trainer').val('')
        $('[name="trainer[]"]').prop('checked',false)
    }

    function bersih_()
    {
        $('#id_jadwal_').val('')
        $('#id_trainer_').val('')
        $('[name="jam[]"]').prop('checked',false)
    }
</script>
@endsection
