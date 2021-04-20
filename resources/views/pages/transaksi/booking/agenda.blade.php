<label for="">Silahkan Pilih Jam</label>
<br>
<div class="row">
    @foreach ($agenda as $a)
    <div class="col-sm-2">
        <input type="checkbox" name="jam[]" id="{{$a->jam}}" value="{{$a->jam}}">&nbsp&nbsp&nbsp{{$a->jam}}
    </div>
    @endforeach
</div>

