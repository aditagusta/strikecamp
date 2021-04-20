@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Order Paket')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <button class="btn btn-sm btn-primary" onclick="tambah()"><i class="fa fa-plus"></i> Order Paket</button>
        <div id="table">

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="addModal" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <div class="form-group">
                <label for="">Nama Member</label>
                <input type="hidden" class="form-control" id="id_order">
                <input type="hidden" class="form-control" id="status">
                <select name="id_member" id="id_member" class="form-control">
                    <option value="">-- Silahkan Pilih --</option>
                    @foreach ($member as $itemmember)
                    <option value="{{$itemmember->id_member}}">{{$itemmember->nama_member}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Jumlah Paket</label>
                {{-- <input type="number" class="form-control" id="jumlah" name="jumlah"> --}}
                <select name="jumlah" id="jumlah" class="form-control">
                    <option value="">-- Silahkan Pilih --</option>
                    @foreach ($paket as $itempaket)
                    <option value="{{$itempaket->paket}}">{{$itempaket->paket}} Paket</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Tanggal Order</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal">
            </div>
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
        $('#table').load("{{route('tableorder')}}")
    });

    function tambah()
    {
        $('#addModal').modal('show')
        $('#update').hide()
        $('#simpan').show()
        document.getElementById('judul').innerHTML = "Form Tambah Data"
    }

    $('#simpan').click(function(e){
        e.preventDefault();
        var id_member = $('#id_member').val();
        var jumlah = $('#jumlah').val();
        var tanggal = $('#tanggal').val();

        axios.post("{{url('/api/order/')}}",{
            id_member: id_member,
            jumlah: jumlah,
            tanggal: tanggal,
        })
        .then(function (res) {
            var data = res.data
            console.log(data.status)
            if(data.status == 200)
            {
                toastr.info(data.message)
                $('#addModal').modal('hide')
                $('#table').load("{{route('tableorder')}}")
                bersih()
            }else{
                toastr.info(data.message)
            }
        })
    })

    function edited(id)
    {
        axios.get("{{url('/api/order')}}/"+ id)
        .then(function(res) {
            var isi = res.data
            console.log(isi.data)
            $('#id_order').val(isi.data.id_order);
            $('#id_member').val(isi.data.id_member);
            $('#jumlah').val(isi.data.jumlah_paket);
            $('#tanggal').val(isi.data.tanggal_order);
            $('#status').val(isi.data.status);
            document.getElementById('judul').innerHTML = "Form Ubah Data";
            $('#addModal').modal('show');
            $('#simpan').hide()
            $('#update').show()
        })
    }

    $('#update').click(function () {
        var id_order = $('#id_order').val();
        var id_member = $('#id_member').val();
        var jumlah_paket = $('#jumlah').val();
        var tanggal_order = $('#tanggal').val();
        var status = $('#status').val();
        var id_user = "{{Auth::guard('pusat')->user()->id_user}}";
        var id_cabang = "{{Auth::guard('pusat')->user()->id_cabang}}";
        axios.put("{{url('/api/order')}}", {
            'id_order': id_order,
            'id_user': id_user,
            'id_member': id_member,
            'jumlah': jumlah_paket,
            'tanggal': tanggal_order,
            'status': status,
            'id_cabang': id_cabang,
        }).then(function (res) {
            var data = res.data
            toastr.info(data.message)
            $('#addModal').modal('hide')
            $('#table').load("{{route('tableorder')}}")
            bersih()
        })
    });

    function deleted(id)
    {
        axios.delete("{{url('/api/order/')}}/"+id)
            .then(function(res){
            var data = res.data
            $('#table').load("{{route('tableorder')}}")
            toastr.info(data.message)
        })
    }

    function bersih()
    {
        $('#id_order').val('');
        $('#id_member').val('');
        $('#jumlah').val('');
        $('#tanggal').val('');
        $('#status').val('');
    }
</script>
@endsection
