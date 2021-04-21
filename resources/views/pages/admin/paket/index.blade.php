@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Paket')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <button class="btn btn-sm btn-primary" onclick="tambah()"><i class="fa fa-plus"></i> Paket</button>
        <div id="table">

        </div>
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
          <form action="" method="POST">
            @csrf
            <label for="">Nama Paket</label>
            <input type="hidden" class="form-control" name="id_paket" id="id_paket">
            <input type="text" class="form-control" id="nama_paket" name="nama_paket">
            <label for="">Jumlah Latihan</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah">
            <label for="">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga">
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
    $(document).ready(function () {
        $('#table').load("{{route('tablepaket')}}")
    });

    $('#simpan').click(function(e){
        e.preventDefault();
        var nama_paket = $('#nama_paket').val();
        var jumlah = $('#jumlah').val();
        var harga = $('#harga').val();

        axios.post("{{url('/api/paket/')}}",{
            nama_paket: nama_paket,
            jumlah: jumlah,
            harga: harga,
        })
        .then(function (res) {
            var data = res.data
            console.log(data.status)
            if(data.status == 200)
            {
                toastr.info(data.message)
                bersih()
                $('#addModal').modal('hide')
                $('#table').load("{{route('tablepaket')}}")
            }else{
                toastr.info(data.message)
            }
        })
    })

    function tambah()
    {
        $('#addModal').modal('show')
        document.getElementById('judul').innerHTML = "Form Tambah Data"
        $('#simpan').show()
        $('#update').hide()
    }

    function edited(id)
    {
        axios.get("{{url('/api/paket')}}/"+ id)
        .then(function(res) {
            var isi = res.data
            console.log(isi.data)
            $('#id_paket').val(isi.data.id_paket);
            $('#nama_paket').val(isi.data.nama_paket);
            $('#jumlah').val(isi.data.jumlah);
            $('#harga').val(isi.data.harga);
            document.getElementById('judul').innerHTML = "Form Ubah Data";
            $('#addModal').modal('show');
            $('#simpan').hide()
            $('#update').show()
        })
    }

    $('#update').click(function (e) {
        e.preventDefault();
        var id_paket = $('#id_paket').val();
        var nama_paket = $('#nama_paket').val();
        var jumlah = $('#jumlah').val();
        var harga = $('#harga').val();
        axios.put("{{url('/api/paket')}}", {
            'id_paket': id_paket,
            'nama_paket': nama_paket,
            'jumlah': jumlah,
            'harga': harga,
        }).then(function (res) {
            var data = res.data
            toastr.info(data.message)
            $('#addModal').modal('hide')
            bersih()
            $('#table').load("{{route('tablepaket')}}")
        })
    });

    function deleted(id)
    {
        axios.delete("{{url('/api/paket/')}}/"+id)
            .then(function(res){
            var data = res.data
            $('#table').load("{{route('tablepaket')}}")
            toastr.info(data.message)
        })
    }

    function bersih()
    {
        $('#id_paket').val('')
        $('#nama_paket').val('')
        $('#jumlah').val('')
        $('#harga').val('')
    }
</script>
@endsection
