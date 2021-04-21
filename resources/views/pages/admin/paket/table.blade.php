<table id="myTable" class="table table-striped table-bordered table-responsive">
    <thead>
        <tr style="text-align: center;">
            <th style="width: 2%;">No</th>
            <th style="width: 20%;">Nama Paket</th>
            <th style="width: 20%;">Jumlah</th>
            <th style="width: 48%;">Harga</th>
            <th style="width: 10%;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $no => $item)
        <tr>
            <td>{{$no+1}}</td>
            <td>{{$item->nama_paket}}</td>
            <td>{{$item->jumlah}}</td>
            <td>Rp. {{number_format($item->harga)}}</td>
            <td>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <button class="btn btn-sm btn-warning" type="button" onclick="edited('{{$item->id_paket}}')">Ubah</button>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <button class="btn btn-sm btn-danger" type="button" onclick="deleted('{{$item->id_paket}}')">Hapus</button>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable()
    });
</script>
