<label for="">Silahkan Pilih Jam</label>
<br>
<div class="row">
    {{-- @dd($agenda); --}}
    @foreach ($agenda[0]['id_jam'] as $a)
        @php
            $datass = DB::table('tbl_jam')->where('id_jam', $a)->first();
            echo  "<div class='col-sm-2'>
                    <input type='checkbox' name='jam[]' id=". $datass->id_jam ." value=". $datass->id_jam .">&nbsp&nbsp&nbsp". $datass->jam ."
                   </div>";
        @endphp
    @endforeach
</div>

