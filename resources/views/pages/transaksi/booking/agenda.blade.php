<label for="">Silahkan Pilih Jam</label>
<br>
<div class="row">
    @foreach ($agenda as $item)
        <div class="col-sm-12 col-md-4">
        {{-- @foreach ($agenda[0]['id_jam'] as $a)
            @php
                $datass = DB::table('tbl_jam')->where('id_jam', $a)->first();
                echo  "<div class='col-sm-2'>
                        <input type='checkbox' name='jam[]' id=". $datass->id_jam ." value=". $datass->id_jam .">&nbsp&nbsp&nbsp". $datass->jam ."
                       </div>";
            @endphp
        @endforeach --}}
            <input type="checkbox" name="jam[]" id="{{$item->id_jadwal_jam}}" value="{{$item->id_jadwal_jam}}">&nbsp;&nbsp;{{$item->jam}}&nbsp;&nbsp;&nbsp;{{$item->nama_trainer}}
        </div>
    @endforeach
</div>

