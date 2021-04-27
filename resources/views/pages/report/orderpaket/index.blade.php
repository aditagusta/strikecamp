@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Laporan Order Paket')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <table id="myTable" class="table table-striped table-bordered table-responsive" style="width: 100%">
            <thead>
                <tr>
                    <th >No</th>
                    <th style="width: 25%">Nama Member</th>
                    <th style="width: 15%">Cabang</th>
                    <th style="width: 20%">Nama Paket</th>
                    <th style="width: 25%">Tanggal Pembelian</th>
                    <th style="width: 30%">Harga</th>
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
                    <td>Rp. {{number_format($i->harga)}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <th colspan="5">Total Order</th>
                <td>Rp. {{number_format($total)}}</td>
            </tfoot>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable()
    });
</script>
@endsection
