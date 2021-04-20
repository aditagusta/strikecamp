@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Banner')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <button class="btn btn-sm btn-primary" onclick="tambah()"><i class="fa fa-plus"></i> Banner</button>
        <table id="table" class="table table-striped table-bordered table-responsive" style="width:100%; font-size:9px">
            <thead>
                <tr style="text-align: center; font-size:10px">
                    <th style="width: 2%;">No</th>
                    <th style="width: 10%;">Nama </th>
                    <th style="width: 78%;">Foto</th>
                    <th style="width: 10%;">Aksi</th>
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
            {{-- <label for="">Nama Banner</label>
            <input type="text" class="form-control" id="nama" name="nama"> --}}
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
          url: "{{ url('/api/banner/datatable') }}",
        },
        columns:[
        {
            data: null,
            render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
          {
            data: 'foto'
          },
          { data: 'foto',
                    render: function( data, type, full, meta ) {
                        return "<img src=\"/images/" + data + "\" height=\"100%\" width=\"100%\"/>";
                    }
                },
          {
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='deleted(" + data.id_banner + ")' class='btn btn-danger btn-sm'>Hapus</button> " +
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

            // Tambahkan data video ke Form Data
            data.append('foto', dataFile);

            // Kirim,
            axios.post('api/banner', data)
            .then(function (res) {
                var data = res.data
                console.log(data.message);
                // Cek Berhasil
                if(data.status == 200)
                {
                    toastr.info(data.message)
                    $('#addModal').modal('hide')
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
        axios.delete("{{url('/api/banner/')}}/"+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }
</script>
@endsection
