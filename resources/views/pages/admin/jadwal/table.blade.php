<button class="btn btn-sm btn-primary" onclick="tambah()"><i class="fa fa-plus"></i> Jadwal</button>
<table id="myTable" class="table table-striped table-bordered table-responsive">
    <thead>
        <tr style="text-align: center;">
            <th style="width: 2%;">No</th>
            <th style="width: 20%;">Tanggal</th>
            <th style="width: 30%;">Trainer</th>
            <th style="width: 10%;">Jam</th>
            <th style="width: 30%;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $no => $item)
            <tr>
                <td>{{$no+1}}</td>
                <td>{{tanggal_indonesia($item['jadwal'])}}</td>
                <td>
                    <?php foreach ($item['id_trainer'] as $i => $a){
                        $trainer = DB::table('tbl_trainer')->join('jadwal_trainer','tbl_trainer.id_trainer','jadwal_trainer.id_trainer')->where('tbl_trainer.id_trainer',$a)->where('id_jadwal', $item['id_jadwal'])->get();
                    ?>
                        <table style="width:100%">
                            <tr>
                                <td style="width:35%">{{$trainer[0]->nama_trainer}}</td>
                                <td style="width:5%">-</td>
                                <td style="width:10%"><button class="btn btn-sm btn-warning" onclick="edits('{{$item['id_jadwal']}}','{{$trainer[0]->id_trainer}}','{{$item['jadwal']}}','{{$trainer[0]->id_jadwal_trainer}}')">Ubah</button></td>
                                <td style="width:10%"><button class="btn btn-sm btn-danger" onclick="deletes('{{$trainer[0]->id_jadwal_trainer}}')">Hapus</button></td>
                                <td style="width:10%"><button class="btn btn-sm btn-success" onclick="jam('{{$item['id_jadwal']}}','{{$trainer[0]->id_trainer}}','{{$trainer[0]->nama_trainer}}')">Tambah Jam</button></td>
                            </tr>
                        </table>
                    <?php } ?>
                </td>
                <td>
                    <?php foreach ($item['id_trainer'] as $c => $d)
                        {
                            $jam = DB::table('jadwal_jam')
                                ->join('tbl_jam','jadwal_jam.id_jam','tbl_jam.id_jam')
                                ->where('id_trainer',$d)
                                ->where('id_jadwal', $item['id_jadwal'])
                                ->get();
                            foreach ($jam as $j)
                            {
                                echo " " . $j->jam;
                            }
                            echo"<hr>";
                        }
                    ?>
                </td>
                <td>
                    <div class="col-sm-12 col-md-4">
                        <button type="button" onclick="edited('{{$item['id_jadwal']}}','{{$item['jadwal']}}')" class="btn btn-sm btn-warning">Ubah</button>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <button type="button" onclick="deleted('{{$item['id_jadwal']}}')" class="btn btn-sm btn-danger">Hapus</button>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <button type="button" onclick="trainer('{{$item['id_jadwal']}}','{{$item['jadwal']}}')" class="btn btn-sm btn-success">Tambah Trainer</button>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center">Empty Data (0)</td></tr>
        @endforelse
    </tbody>
</table>
<script>
    $('#myTable').DataTable();
</script>
