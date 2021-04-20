@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Trainer')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        @if ($message = Session::get('status'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
        <button class="btn btn-sm btn-primary" onclick="tambah()"><i class="fa fa-plus"></i> Trainer</button>
        <table id="table" class="table table-striped table-bordered table-responsive" style="width:100%; font-size:9px">
            <thead>
                <tr style="text-align: center; font-size:10px">
                    <th style="width: 2%;">No</th>
                    <th style="20%">Nama Trainer</th>
                    <th style="68%">Foto</th>
                    <th style="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="">Form Tambah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="">Nama Trainer</label>
            <input type="text" class="form-control" id="nama_trainer" name="nama_trainer">
            <label for="">Foto</label>
            <input type="file" class="form-control" id="foto_trainer" name="foto_trainer">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="simpan" type="button">Simpan</button>
        </div>
      </div>
    </div>
</div>

<!-- Modal  Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="judul">Form Ubah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('edittrainer')}}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <label for="">Nama Trainer</label>
            <input type="hidden" class="form-control" id="id_trainer" name="id_trainer">
            <input type="text" class="form-control" id="nama_trainer" name="nama_trainer">
            <label for="">Foto</label>
            <input type="file" class="form-control" id="foto_trainer" name="foto_trainer">
            <button type="submit" class="btn btn-primary" id="update">Ubah</button>
          </form>
        </div>
      </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var id_cabang = {{Auth::guard('pusat')->user()->id_cabang}}
      tables = $('#table').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/trainer/datatable') }}/" + id_cabang,
        },
        columns:[
        {
            data: null,
            render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
        data: 'nama_trainer'
        },
        { data: 'foto_trainer',
                render: function( data, type, full, meta ) {
                    return "<img src=\"/images/" + data + "\" class=\"img-fluid\"/>";
                }
        },
        {
        data: null,
        render: function(data, type, row, meta) {
        return "<div>" +
            "<button type='button' onclick='deleted(" + data.id_trainer + ")' class='btn btn-danger btn-sm'>Hapus</button> " +
            "<button type='button' onclick='edited(" + data.id_trainer + ")' class='btn btn-warning btn-sm'>Ubah</button> " +
        "</div>" ;
        }
        }
        ]
      });
    });

    function tambah() {
        $('#addModal').modal('show')
    }

    $('#simpan').click(function (e) {
        // Membuat Form Data Baru
        var data = new FormData(),
            inputGambar = document.getElementById('foto_trainer'),
            dataFile = inputGambar.files[0];
            var nama_trainer = $('#nama_trainer').val()
            var id_cabang = {{Auth::guard('pusat')->user()->id_cabang}}
            // Tambahkan data video ke Form Data
            data.append('nama_trainer', nama_trainer);
            data.append('id_cabang', id_cabang);
            data.append('foto_trainer', dataFile);

            // Kirim,
            axios.post('api/trainer', data)
            .then(function (res) {
                var data = res.data
                console.log(data.message);
                // Cek Berhasil
                if(data.status == 200)
                {
                    toastr.info(data.message)
                    $('#addModal').modal('hide')
                    $('#nama_trainer').val('')
                    $('#foto').val('')
                    tables.ajax.reload()
                }
                else
                {
                    toastr.info(data.message)
                }
            })
    });

    function edited(id) {
        axios.get('{{url("/api/trainer")}}/' + id)
            .then(function (res) {
                var isi = res.data
                console.log(isi.data)
                $('[name="id_trainer"]').val(isi.data.id_trainer)
                $('[name="nama_trainer"]').val(isi.data.nama_trainer)
                $('#editModal').modal('show');
            })
    }

    // $('#update').click(function () {
    //     // e.preventDefault();
    //     // let myForm = document.getElementById('myForm');
    //     var formData = new FormData();
    //     var inputGambar = document.getElementById('foto_trainer');
    //     var dataFile = inputGambar.files[0];

    //     var id_trainer = $('#id_trainer').val()
    //     var nama_trainer = $('#nama_trainer').val()
    //     var foto_lama = $('#foto_lama').val()
    //     var id_cabang = {{session()->get('cabang')}}

    //     // Tambahkan data video ke Form Data
    //     formData.append('id_trainer', id_trainer);
    //     formData.append('nama_trainer', nama_trainer);
    //     formData.append('foto_trainer', dataFile);
    //     formData.append('foto_lama', foto_lama);
    //     formData.append('id_cabang', id_cabang);


    //     axios.post("/api/trainerr",formData)
    //     .then(function (res) {
    //         var data = res.data
    //         console.log(data);
    //         toastr.info(data.message)
    //         $('#addModal').modal('hide')
    //         tables.ajax.reload()
    //     })
    // });

    function deleted(id)
    {
        axios.delete("{{url('/api/trainer/')}}/"+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }
</script>
@endsection
