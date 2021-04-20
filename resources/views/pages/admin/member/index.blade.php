@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Member')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <button class="btn btn-sm btn-primary" onclick="tambah()"><i class="fa fa-plus"></i> Member</button>
        <table id="table" class="table table-striped table-bordered table-responsive" style="width:100%; font-size:9px">
            <thead>
                <tr style="text-align: center; font-size:10px">
                    <th style="width: 2%;">No</th>
                    <th style="20%">Nama Member</th>
                    <th style="20%">Telepon</th>
                    <th style="38%">Foto Member</th>
                    <th style="20%">Aksi</th>
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
          <h5 class="modal-title" id="judul">Form Tambah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="">Username</label>
            <input type="text" class="form-control" id="username" name="username">
            <label for="">Password</label>
            <input type="password" class="form-control" id="password" name="password">
            <label for="">Ulangi Password</label>
            <input type="password" class="form-control" id="password1" name="password1">
            <label for="">Nama Member</label>
            <input type="text" class="form-control" id="nama_member" name="nama_member">
            <label for="">Telepon</label>
            <input type="text" class="form-control" id="telepon" name="telepon">
            <label for="">Foto</label>
            <input type="file" class="form-control" id="gambar_member" name="gambar_member">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="simpan" type="button">Simpan</button>
        </div>
      </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="judul">Form Ubah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('editmember')}}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <label for="">Username</label>
            <input type="hidden" class="form-control" id="id_member" name="id_member">
            <input type="text" class="form-control" id="username_" name="username">
            <label for="">Password</label>
            <input type="password" class="form-control" id="password_" name="password">
            <label for="">Ulangi Password</label>
            <input type="password" class="form-control" id="password1_" name="password1">
            <label for="">Nama Member</label>
            <input type="text" class="form-control" id="nama_member_" name="nama_member">
            <label for="">Telepon</label>
            <input type="text" class="form-control" id="telepon_" name="telepon">
            <label for="">Foto</label>
            <input type="file" class="form-control"  name="gambar_">
            <br>
            <button type="submit" class="btn btn-primary">Ubah</button>
          </form>
        </div>
      </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var id = '{{Auth::guard('pusat')->user()->id_cabang}}'
      tables = $('#table').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/member/datatable') }}/"+id,
        },
        columns:[
        {
            data: null,
            render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
        data: 'nama_member'
        },
        {
        data:'telepon'
        },
        { data: 'gambar_member',
                    render: function( data, type, full, meta ) {
                        return "<img src=\"/images/" + data + "\" height=\"100%\" width=\"100%\"/>";
                    }
                },
        {
        data: null,
        render: function(data, type, row, meta) {
        return "<div>" +
            "<button type='button' onclick='edited(" + data.id_member + ")' class='btn btn-warning btn-sm'>Ubah</button> " +
            "<button type='button' onclick='deleted(" + data.id_member + ")' class='btn btn-danger btn-sm'>Hapus</button> " +
        "</div>" ;
        }
        }
        ]
      });
    });

    function tambah()
    {
        $('#addModal').modal('show')
    }

    $('#simpan').click(function (e) {
        // Membuat Form Data Baru
        var data = new FormData(),
        inputGambar = document.getElementById('gambar_member'),
        dataFile = inputGambar.files[0];
        var username = $('#username').val();
        var password = $('#password').val();
        var password1 = $('#password1').val();
        var nama_member = $('#nama_member').val();
        var telepon = $('#telepon').val();
        var id_cabang = {{ Auth::guard('pusat')->user()->id_cabang }}

        // Tambahkan data video ke Form Data
        data.append('gambar_member', dataFile);
        data.append('username', username);
        data.append('password', password);
        data.append('password1', password1);
        data.append('nama_member', nama_member);
        data.append('telepon', telepon);
        data.append('id_cabang', id_cabang);
        console.log(data);

        // Kirim,
        axios.post('api/member', data)
            .then(function (res) {
                var data = res.data
                console.log(data.message);
                // Cek Berhasil
                if (data.status == 200) {
                    toastr.info(data.message)
                    $('#addModal').modal('hide')
                    tables.ajax.reload()
                } else {
                    toastr.info(data.message)
                }
            })
    });

    function edited(id)
    {
        axios.get("{{url('/api/member')}}/"+ id)
        .then(function(res) {
            var isi = res.data
            console.log(isi.data)
            $('#id_member').val(isi.data.id_member);
            $('#username_').val(isi.data.username);
            $('#password_').val(isi.data.password1);
            $('#password1_').val(isi.data.password1);
            $('#nama_member_').val(isi.data.nama_member);
            $('#telepon_').val(isi.data.telepon);
            $('#editModal').modal('show');
        })
    }

    // $('#update').click(function (e) {
    //     e.preventDefault();
    //     var id_member = $('#id_member').val();
    //     var username = $('#username').val();
    //     var password = $('#password').val();
    //     var password1 = $('#password1').val();
    //     var nama_member = $('#nama_member').val();
    //     var telepon = $('#telepon').val();
    //     var cabang = '{{session()->get('cabang')}}';
    //     axios.put("{{url('/api/member')}}", {
    //         'id_member': id_member,
    //         'username': username,
    //         'password': password,
    //         'password1': password1,
    //         'nama_member': nama_member,
    //         'telepon': telepon,
    //         'id_cabang': cabang,
    //     }).then(function (res) {
    //         var data = res.data
    //         toastr.info(data.message)
    //         $('#addModal').modal('hide')
    //         tables.ajax.reload()
    //         bersih()
    //     })
    // });

    function deleted(id)
    {
        axios.delete("{{url('/api/member/')}}/"+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }
</script>
@endsection
