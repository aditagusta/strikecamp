@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Data Booking')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12">
        <div class="mb-2">
            <div class="card">
                <div class="card-body">
                    @if ($message = Session::get('status'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    @if ($message = Session::get('error'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    <form action="{{route('addbooking')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Tanggal Latihan</label>
                                    <select name="tanggal" id="tanggal" class="form-control">
                                        <option value="">-- Silahkan Pilih --</option>
                                        @foreach ($jadwal as $itemjadwal)
                                        <option value="{{$itemjadwal->id_jadwal}}">{{tanggal_indonesia($itemjadwal->jadwal)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="form-group" id="data_trainer">
                                    <label for="">Pilih Trainer</label>
                                </div> --}}
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="">Member</label>
                                            <select name="member" id="member" class="form-control" onchange="members()">
                                                <option value="">-- Silahkan Pilih --</option>
                                                @foreach ($member as $itemmember)
                                                <option value="{{$itemmember->id_member}}">{{$itemmember->nama_member}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="">Sisa Paket</label>
                                            <input type="text" class="form-control" id="paket" name="paket" readonly>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-success" type="submit">Simpan</button>
                            </div>
                            <div class="col-sm-12 col-md-6" id="agenda">
                                {{-- <div class="row" id="data_jam"> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <table id="myTable" class="table table-striped table-bordered table-responsive">
            <thead>
                <tr style="text-align: center;">
                    <th style="width: 2%">No</th>
                    <th style="width: 20%">Nama Member</th>
                    <th style="width: 20%">Jam</th>
                    <th style="width: 20%">Tanggal Latihan</th>
                    <th style="width: 18%">Aksi</th>
                </tr>
            </thead>
            <tbody>
               @foreach ($booking as $no => $bk)
                <tr>
                    <td>{{$no+1}}</td>
                    <td>{{$bk['nama_member']}}</td>
                    <td>
                        @foreach ($bk['jam'] as $j)
                        <li>{{$j}}</li>
                        @endforeach
                    </td>
                    <td>{{tanggal_indonesia($bk['jadwal'])}}</td>
                    <td>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <form method="POST" action="{{ route('removebooking', [$bk['id_booking']]) }}">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
               @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    // function hari()
    // {
    //     var id = $('#tanggal').val()
    //     $('#agenda').load("{{route('tableagenda')}}/" + id)
    //     axios.post("{{url('/api/cek/trainer')}}",{
    //         'id_jadwal':id
    //     }).then(function(res){
    //         var cek = res.data;
    //         return cek;
    //         console.log(cek);
    //         if(cek == '')
    //         {
    //             var tes = ''
    //         }else{
    //             var tes = cek.id_jam.split(",")
    //         }
    //         console.log(tes);

    //         if(tes != '')
    //         {
    //             for(var i = 0; i < tes.length; i++){
    //                 document.getElementById(tes[i]).checked = true;
    //                 document.getElementById(tes[i]).disabled = true;
    //             }
    //         } else {
    //             var list_jam = document.getElementsByName("jam[]");
    //             // reset centang jam
    //             for (var x = 0; x < list_jam.length; x++) {
    //                 list_jam[x].checked = false;
    //             }
    //         }
    //     })
    // }

    $('#tanggal').change(function () {
        var id = $('#tanggal').val()
        $('#agenda').load("{{route('tableagenda')}}/" + id)
    });

    function members()
    {
       var id = $('#member').val()
       axios.get("{{url('/api/cekpaket')}}/"+id)
        .then(function(res){
            var data = res.data
            console.log(data)
            $('#paket').val(data.sisa_paket)
        })
    }

    $('#myTable').DataTable()

    // let tanggal = document.getElementById("tanggal");
    // tanggal.addEventListener("change", async (e) => {
    //     let latihanValue = e.target.value;
    //     let trainerValue = null;
    //     let trainer = document.getElementById("data_trainer")
    //     let select = document.createElement("select")
    //     var id = $('#tanggal').val()

    //     let data =  await axios.post("{{url('/api/cek/trainer')}}",{
    //         'id_jadwal':id
    //     })

    //     let trainerOption = document.getElementById("id_trainer")
    //     trainerOption === null ? "" : trainerOption.remove()

    //     let dataTrainer = ""
    //     dataTrainer += `<option value="">Silahkan Pilih</option>`
    //     data.data.forEach(d => {
    //         dataTrainer += `<option value="${d.id_trainer}">${d.nama_trainer}</option>`
    //     });

    //     select.classList.add("form-control");
    //     select.id = "id_trainer"
    //     select.name = "id_trainer"
    //     select.innerHTML = dataTrainer;
    //     trainer.appendChild(select);

    //     let trainer_option = document.getElementById("id_trainer");
    //     trainer_option.addEventListener("change", async (e) => {
    //         $('#data_jam').empty()
    //         trainerValue = e.target.value
    //         let response = await axios.post("api/cek/jam",{
    //             id_jadwal:latihanValue,
    //             id_trainer:trainerValue,
    //         })
    //             let dataJam = response.data.data
    //             dataJam.forEach(function (pecah,index){
    //                 console.log(index);
    //                 let htmljam = `
    //                 <div class="col-sm-2">
    //                     <input type="checkbox" id="${pecah.id_jam}" value="${pecah.id_jam}" name="jam_[]">&nbsp;&nbsp;&nbsp;${pecah.jam}
    //                 </div>
    //                 `
    //                 $('#data_jam').append(htmljam)
    //             });
    //     })
    // })
</script>
@endsection
