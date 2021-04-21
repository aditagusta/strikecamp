<link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<table id="myTable" class="table table-striped table-bordered table-responsive">
    <thead>
        <tr style="text-align: center;">
            <th style="width: 2%">No</th>
            <th style="width: 20%">Nama Member</th>
            <th style="width: 10%">Jumlah Paket</th>
            <th style="width: 10%">Nama Cabang</th>
            <th style="width: 20%">Tanggal Order</th>
            <th style="width: 20%">Status</th>
            <th style="width: 18%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $no => $item)
        <tr>
            <td>{{$no+1}}</td>
            <td>{{$item->nama_member}}</td>
            <td>{{$item->nama_paket}}</td>
            <td>{{$item->nama_cabang}}</td>
            <td>{{tanggal_indonesia($item->tanggal_order)}}</td>
            <td>
                @if($item->status == 0)
                <div class="alert alert-warning" align=center role="alert">
                    PENDING
                </div>
                @endif
            </td>
            <td>
                <div class="row">
                    @if ($item->status == 0)
                    <div class="col-md-6 col-sm-12">
                        <button class="btn btn-sm btn-warning" onclick="edited('{{$item->id_order}}')">Ubah</button>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <button class="btn btn-sm btn-danger" onclick="deleted('{{$item->id_order}}')">Hapus</button>
                    </div>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $('#myTable').DataTable();
</script>
