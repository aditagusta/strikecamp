@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Cabang')
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
        <button class="btn btn-sm btn-primary" onclick="tambah()"><i class="fa fa-plus"></i> Cabang</button>
        <table id="table" class="table table-striped table-bordered table-responsive" style="width:100%; font-size:9px">
            <thead>
                <tr style="text-align: center; font-size:10px">
                    <th style="width: 2%;">No</th>
                    <th style="20%">Nama Cabang</th>
                    <th style="20%">Lokasi</th>
                    <th style="20%">Telepon</th>
                    <th style="20%">Gambar</th>
                    <th style="18%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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
                    <label for="">Nama Cabang</label>
                    <input type="text" class="form-control" id="nama_cabang" name="nama_cabang">
                    <label for="">Lokasi</label>
                    <textarea name="lokasi" id="lokasi" cols="30" rows="10" class="form-control"></textarea>
                    <label for="">Telepon</label>
                    <input type="text" class="form-control" id="telepon" name="telepon">
                    <label for="">Foto</label>
                    <input type="file" class="form-control" id="gambar_cabang" name="gambar_cabang">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="simpan" type="button">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('editcabang')}}" id="myForm" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <label for="">Nama Cabang</label>
                    <input type="hidden" class="form-control" id="id_cabang" name="id_cabang">
                    <input type="text" class="form-control" id="nama_" name="nama_cabang">
                    <label for="">Lokasi</label>
                    <textarea name="lokasi" id="lokasi_" cols="30" rows="10" class="form-control"></textarea>
                    <label for="">Telepon</label>
                    <input type="text" class="form-control" id="telepon_" name="telepon">
                    <label for="">Foto</label>
                    <input type="file" class="form-control" id="gambar_" name="gambar_cabang">
                    <button  class="btn btn-primary" id="update" type="submit">Ubah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        tables = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/api/cabang/datatable') }}",
            },
            columns: [{
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'nama_cabang'
                },
                {
                    data: 'lokasi'
                },
                {
                    data: 'telepon'
                },
                { data: 'gambar_cabang',
                    render: function( data, type, full, meta ) {
                        return "<img src=\"/images/" + data + "\" height=\"100px\" width=\"100%\"/>";
                    }
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return "<div>" +
                            "<button type='button' onclick='edited(" + data.id_cabang +
                            ")' class='btn btn-warning btn-sm'>Ubah</button> " +
                            "<button type='button' onclick='deleted(" + data.id_cabang +
                            ")' class='btn btn-danger btn-sm'>Hapus</button> " +
                            "</div>";
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
        inputGambar = document.getElementById('gambar_cabang'),
        dataFile = inputGambar.files[0];
        var nama_cabang = $('#nama_cabang').val()
        var lokasi = $('#lokasi').val()
        var telepon = $('#telepon').val()


        // Tambahkan data video ke Form Data
        data.append('gambar_cabang', dataFile);
        data.append('nama_cabang', nama_cabang);
        data.append('lokasi', lokasi);
        data.append('telepon', telepon);
        console.log(data);

        // Kirim,
        axios.post('api/cabang', data)
            .then(function (res) {
                var data = res.data
                console.log(data.message);
                // Cek Berhasil
                if (data.status == 200) {
                    toastr.info(data.message)
                    $('#addModal').modal('hide')
                    $('#nama_cabang').val('')
                    $('#lokasi').val('')
                    tables.ajax.reload()
                } else {
                    toastr.info(data.message)
                }
            })
    });

    function edited(id) {
        axios.get('{{url("/api/cabang")}}/' + id)
            .then(function (res) {
                var isi = res.data
                console.log(isi.data)
                $('[name="id_cabang"]').val(isi.data.id_cabang)
                $('[name="nama_cabang"]').val(isi.data.nama_cabang)
                $('[name="lokasi"]').val(isi.data.lokasi)
                $('[name="telepon"]').val(isi.data.telepon)
                $('[name="gambar_lama"]').val(isi.data.gambar_cabang)
                $('#editModal').modal('show');
            })
    }

    // $('#update').click(function () {
    //     // e.preventDefault();
    //     // let myForm = document.getElementById('myForm');
    //     var formData = new FormData();
    //     var inputGambar = document.getElementById('gambar_');
    //     var dataFile = inputGambar.files[0];

    //     var id_cabang = $('#id_cabang').val()
    //     var nama_cabang = $('#nama_').val()
    //     var lokasi = $('#lokasi_').val()
    //     var telepon = $('#telepon_').val()
    //     var gambar_lama = $('#gambar_lama').val()

    //     // Tambahkan data video ke Form Data
    //     formData.append('gambar_cabang', dataFile);
    //     formData.append('nama_cabang', nama_cabang);
    //     formData.append('lokasi', lokasi);
    //     formData.append('telepon', telepon);
    //     formData.append('id_cabang', id_cabang);
    //     formData.append('gambar_lama', gambar_lama);


    //     axios.post("/api/cabangg",formData)
    //     .then(function (res) {
    //         var data = res.data
    //         console.log(data);
    //         toastr.info(data.message)
    //         $('#editModal').modal('hide')
    //         tables.ajax.reload()
    //     })
    // });

    function deleted(id) {
        axios.delete('{{url("/api/cabang/")}}/' + id)
            .then(function (res) {
                var data = res.data
                tables.ajax.reload()
                toastr.info(data.message)
            })
    }

</script>
@endsection
