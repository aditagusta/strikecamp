@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Katalog')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <button class="btn btn-sm btn-primary" onclick="tambah()"><i class="fa fa-plus"></i> Katalog</button>
        <table id="table" class="table table-striped table-bordered table-responsive" style="width:100%;">
            <thead>
                <tr style="text-align: center;">
                    <th style="width: 2%;">No</th>
                    <th style="10%">Nama </th>
                    <th style="20%">Deskripsi </th>
                    <th style="58%">Foto</th>
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
          <h5 class="modal-title" id="exampleModalLabel">Form Tambah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="">Nama Katalog</label>
            <input type="text" class="form-control" id="nama_katalog" name="nama_katalog">
            <label for="">Deskripsi</label>
            <input type="text" class="form-control" id="deskripsi" name="deskripsi">
            <label for="">Foto</label>
            <input type="file" class="form-control" id="foto" name="foto">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="simpan" type="button">Simpan</button>
        </div>
      </div>
    </div>
  </div>
<script>
    $(document).ready(function(){
      tables = $('#table').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/katalog/datatable') }}",
        },
        columns:[
        {
            data: null,
            render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
        data: 'nama_katalog'
        },
        {
        data: 'deskripsi'
        },
        { data: 'foto',
                render: function( data, type, full, meta ) {
                    return "<img src=\"/images/" + data + "\" height=\"150px\" width=\"200px\"/>";
                }
        },
        {
        data: null,
        render: function(data, type, row, meta) {
        return "<div>" +
            "<button type='button' onclick='deleted(" + data.id_katalog + ")' class='btn btn-danger btn-sm'>Hapus</button> " +
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
            inputGambar = document.getElementById('foto'),
            dataFile = inputGambar.files[0];
            var nama_katalog = $('#nama_katalog').val()
            var deskripsi = $('#deskripsi').val()
            // Tambahkan data video ke Form Data
            data.append('nama_katalog', nama_katalog);
            data.append('deskripsi', deskripsi);
            data.append('foto', dataFile);

            // Kirim,
            axios.post('api/katalog', data)
            .then(function (res) {
                var data = res.data
                console.log(data.message);
                // Cek Berhasil
                if(data.status == 200)
                {
                    toastr.info(data.message)
                    $('#addModal').modal('hide')
                    $('#nama_katalog').val('')
                    $('#deskripsi').val('')
                    $('#foto').val('')
                    tables.ajax.reload()
                }
                else
                {
                    toastr.info(data.message)
                }
            })
    });

    function deleted(id)
    {
        axios.delete("{{url('/api/katalog/')}}/"+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }
</script>
@endsection
