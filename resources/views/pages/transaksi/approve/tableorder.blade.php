<link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<table id="myTable" class="table table-striped table-bordered table-responsive">
    <thead>
        <tr style="text-align: center;">
            <th style="width: 2%">No</th>
            <th style="width: 20%">Nama Member</th>
            <th style="width: 20%">Tanggal Order</th>
            <th style="width: 20%">Jumlah Paket</th>
            <th style="width: 20%">Approve</th>
            <th style="width: 18%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $no => $item)
            <tr>
                <td>{{$no+1}}</td>
                <td>{{$item->nama_member}}</td>
                <td>{{tanggal_indonesia($item->tanggal_order)}}</td>
                <td>{{$item->jumlah_paket}} Paket</td>
                <td>
                    <select name="approve" id="approve" class="form-control">
                        <option value="1">Terima</option>
                        <option value="2">Tolak</option>
                    </select>
                </td>
                <td><button class="btn btn-sm btn-success" onclick="simpan('{{$item->id_order}}')">Simpan</button></td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $('#myTable').DataTable();
</script>
