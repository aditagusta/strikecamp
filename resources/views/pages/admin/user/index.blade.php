@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Member Cabang')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <button class="btn btn-sm btn-primary" onclick="tambah()"><i class="fa fa-plus"></i> User</button>
        <table id="table" class="table table-striped table-bordered table-responsive" style="width:100%; font-size:9px">
            <thead>
                <tr style="text-align: center; font-size:10px">
                    <th style="width: 2%;">No</th>
                    <th style="10%">Nama Member</th>
                    <th style="20%">Telepon</th>
                    <th style="58%">Nama Cabang</th>
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
            <label for="">Username</label>
            <input type="hidden" class="form-control" id="id_user">
            <input type="text" class="form-control" id="username" name="username">
            <label for="">Password</label>
            <input type="password" class="form-control" id="password" name="password">
            <label for="">Ulangi Password</label>
            <input type="password" class="form-control" id="password1" name="password1">
            <label for="">Nama Member</label>
            <input type="text" class="form-control" id="nama_user" name="nama_user">
            <label for="">Telepon</label>
            <input type="text" class="form-control" id="telepon" name="telepon">
            <label for="">Cabang</label>
            <select name="cabang" id="cabang" class="form-control">
                <option value="">-- Silahkan Pilih --</option>
                @foreach ($data as $item)
                    <option value="{{$item->id_cabang}}">{{$item->nama_cabang}}</option>
                @endforeach
            </select>
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
      tables = $('#table').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/user/datatable') }}",
        },
        columns:[
        {
            data: null,
            render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
        data: 'nama_user'
        },
        {
        data:'telepon'
        },
        {
        data:'nama_cabang'
        },
        {
        data: null,
        render: function(data, type, row, meta) {
        return "<div>" +
            "<button type='button' onclick='edited(" + data.id_user + ")' class='btn btn-warning btn-sm'>Ubah</button> " +
            "<button type='button' onclick='deleted(" + data.id_user + ")' class='btn btn-danger btn-sm'>Hapus</button> " +
        "</div>" ;
        }
        }
        ]
      });
    });

    function tambah() {
        document.getElementById('judul').innerHTML = "Form Tambah Data";
        $('#addModal').modal('show')
        $('#update').hide()
        $('#simpan').show()
    }

    $('#simpan').click(function(e){
        e.preventDefault();
        var username = $('#username').val();
        var password = $('#password').val();
        var password1 = $('#password1').val();
        var nama_user = $('#nama_user').val();
        var telepon = $('#telepon').val();
        var cabang = $('#cabang').val();

        axios.post("{{url('/api/user/')}}",{
            username: username,
            password: password,
            password1: password1,
            nama_user: nama_user,
            telepon: telepon,
            id_cabang: cabang,
        })
        .then(function (res) {
            var data = res.data
            console.log(data.status)
            if(data.status == 200)
            {
                tables.ajax.reload()
                toastr.info(data.message)
                bersih()
                $('#addModal').modal('hide')
            }else{
                toastr.info(data.message)
            }
        })
    })

    function edited(id)
    {
        axios.get("{{url('/api/user')}}/"+ id)
        .then(function(res) {
            var isi = res.data
            console.log(isi.data)
            $('#id_user').val(isi.data.id_user);
            $('#username').val(isi.data.username);
            $('#password').val(isi.data.password1);
            $('#password1').val(isi.data.password1);
            $('#nama_user').val(isi.data.nama_user);
            $('#telepon').val(isi.data.telepon);
            $('#cabang').val(isi.data.id_cabang);
            document.getElementById('judul').innerHTML = "Form Ubah Data";
            $('#addModal').modal('show');
            $('#simpan').hide()
            $('#update').show()
        })
    }

    $('#update').click(function (e) {
        e.preventDefault();
        var id_user = $('#id_user').val();
        var username = $('#username').val();
        var password = $('#password').val();
        var password1 = $('#password1').val();
        var nama_user = $('#nama_user').val();
        var telepon = $('#telepon').val();
        var cabang = $('#cabang').val();
        axios.put("{{url('/api/user')}}", {
            'id_user': id_user,
            'username': username,
            'password': password,
            'password1': password1,
            'nama_user': nama_user,
            'telepon': telepon,
            'id_cabang': cabang,
        }).then(function (res) {
            var data = res.data
            toastr.info(data.message)
            $('#addModal').modal('hide')
            tables.ajax.reload()
        })
    });

    function deleted(id)
    {
        axios.delete("{{url('/api/user/')}}/"+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    function bersih()
    {
        $('#id_user').val('');
        $('#username').val('');
        $('#password').val('');
        $('#password1').val('');
        $('#nama_user').val('');
        $('#telepon').val('');
        $('#cabang').val('');
    }
</script>
@endsection
