@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Laporan Order Paket')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <table id="myTable" class="table table-striped table-bordered table-responsive" style="width: 100%">
            <button class="btn btn-warning" id="edit">Edit Tanggal</button>
            <thead>
                <tr>
                    <th >No</th>
                    <th style="width: 15%">Nama Member</th>
                    <th style="width: 15%">Cabang</th>
                    <th style="width: 10%">Nama Paket</th>
                    <th style="width: 25%">Tanggal Pembelian</th>
                    <th style="width: 25%">Tanggal Expired</th>
                    <th style="width: 15%">Harga</th>
                    <th style="width: 15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0 ;?>
                @foreach ($data as $no => $i)
                <?php $total += $i->harga ;?>
                <tr>
                    <td>{{$no+1}}</td>
                    <td>{{$i->nama_member}}</td>
                    <td>{{$i->nama_cabang}}</td>
                    <td>{{$i->nama_paket}}</td>
                    <td>{{tanggal_indonesia($i->tanggal_order)}}</td>
                    <td>{{tanggal_indonesia($i->tanggal_exp)}}</td>
                    <td>Rp. {{number_format($i->harga)}}</td>
                    <td>
                        <?php
                        $hari = date("Y-m-d");
                        if($i->tanggal_exp > $hari)
                        {
                        ?>
                            <input type="checkbox" name="id[]" id="tes" value="{{$i->id_order}}">
                        <?php }
                        ?>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <th colspan="6">Total Order</th>
                <td colspan="2" align=left>Rp. {{number_format($total)}}</td>
            </tfoot>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="Label">Edit Tanggal Order</h5>
          <button type="button" class="close" data-dismiss="modal" onclick="bersih()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{url('edit/tanggal')}}" method="post">
                @csrf
                <div id="isi">

                </div>
                <div class="form-group">
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" id="simpan">Save changes</button>
            </form>
        </div>
      </div>
    </div>
  </div>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable()
    });

    $('#edit').click(function () {
        var id_order = new Array();
            $("input:checked").each(function() {
            id_order.push($(this).val());
        });
        axios.post("{{url('/api/edit/tanggal')}}",{
            id_order: id_order
        })
        .then(function (res) {
            var data = res.data
            console.log(data);
            $('#editModal').modal('show')
            let dataTanggal = res.data.data
            console.log(dataTanggal);
            dataTanggal.forEach(function (pecah,index){
                console.log(index);
                let htmljam = `
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Tanggal Expired</label>
                                <input class="form-control" type="hidden" name="id[]" value="${pecah.id_order}">
                                <input class="form-control" type="date" value="${pecah.tanggal_exp}" disabled>
                            </div>
                            <div class="col-md-6">
                                <strong>- ${pecah.nama_member}</strong><br>
                                <strong>- ${pecah.nama_paket}</strong>
                            </div>
                        </div>
                        <br>
                    </div>
                `
                $('#isi').append(htmljam)
            });
        })
    });

    function bersih() {
        $('#isi').empty();
    }
</script>

@endsection
