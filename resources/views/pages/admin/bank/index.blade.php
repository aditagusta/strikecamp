@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Bank')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <button class="btn btn-sm btn-primary" onclick="tambah()"><i class="fa fa-plus"></i> Bank</button>
        <table id="table" class="table table-striped table-bordered table-responsive" style="width:100%;">
            <thead>
                <tr style="text-align: center;">
                    <th style="width: 2%;">No</th>
                    <th>Nama Bank</th>
                    <th>Rekening</th>
                    <th>Aksi</th>
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
<div class="modal fade" id="addModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="judul"></h5>
          <button type="button" class="close" data-dismiss="modal" onclick="bersih()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="">Nama Bank</label>
            <input type="hidden" class="form-control" id="id_bank" name="id_bank">
            <input type="text" class="form-control" id="nama_bank" name="nama_bank">
            <label for="">Rekening</label>
            <input type="text" class="form-control" id="rekening" name="rekening">
            <input type="hidden" class="form-control" id="id_cabang" name="id_cabang">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="simpan" type="button">Simpan</button>
          <button type="button" class="btn btn-primary" id="update" type="button">Ubah</button>
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
          url: "{{ url('/api/bank/datatable') }}/" + id_cabang,
        },
        columns:[
        {
            data: null,
            render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
        data: 'nama_bank'
        },
        {
        data:'rekening'
        },
        {
        data: null,
        render: function(data, type, row, meta) {
        return "<div>" +
            "<button type='button' onclick='edited(" + data.id_bank + ")' class='btn btn-warning btn-sm'>Ubah</button> " +
            "<button type='button' onclick='deleted(" + data.id_bank + ")' class='btn btn-danger btn-sm'>Hapus</button> " +
        "</div>" ;
        }
        }
        ]
      });
    });

    function tambah()
    {
        document.getElementById('judul').innerHTML = "Form Tambah Data";
        $('#addModal').modal('show')
        $('#update').hide()
        $('#simpan').show()
    }

    $('#simpan').click(function(e){
        e.preventDefault();
        var nama_bank = $('#nama_bank').val();
        var rekening = $('#rekening').val();
        var id_cabang = {{Auth::guard('pusat')->user()->id_cabang}};

        axios.post("{{url('/api/bank/')}}",{
            nama_bank: nama_bank,
            rekening: rekening,
            id_cabang: id_cabang,
        })
        .then(function (res) {
            var data = res.data
            console.log(data.status)
            if(data.status == 200)
            {
                tables.ajax.reload()
                toastr.info(data.message)
                $('#addModal').modal('hide')
                bersih()
            }else{
                toastr.info(data.message)
            }
        })
    })

    function edited(id)
    {
        axios.get("{{url('/api/bank')}}/"+ id)
        .then(function(res) {
            var isi = res.data
            console.log(isi.data)
            $('#id_bank').val(isi.data.id_bank);
            $('#nama_bank').val(isi.data.nama_bank);
            $('#rekening').val(isi.data.rekening);
            $('#id_cabang').val(isi.data.id_cabang);
            document.getElementById('judul').innerHTML = "Form Ubah Data";
            $('#addModal').modal('show');
            $('#simpan').hide()
            $('#update').show()
        })
    }

    $('#update').click(function (e) {
        e.preventDefault();
        var id_bank = $('#id_bank').val();
        var nama_bank = $('#nama_bank').val();
        var rekening = $('#rekening').val();
        var id_cabang = $('#id_cabang').val();
        axios.put("{{url('/api/bank')}}", {
            'id_bank': id_bank,
            'nama_bank': nama_bank,
            'rekening': rekening,
            'id_cabang': id_cabang,
        }).then(function (res) {
            var data = res.data
            toastr.info(data.message)
            $('#addModal').modal('hide')
            tables.ajax.reload()
            bersih()
        })
    });

    function deleted(id)
    {
        axios.delete("{{url('/api/bank/')}}/"+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    function bersih()
    {
        $('#id_bank').val('')
        $('#nama_bank').val('')
        $('#rekening').val('')
        $('#id_cabang').val('')
    }
</script>
@endsection
