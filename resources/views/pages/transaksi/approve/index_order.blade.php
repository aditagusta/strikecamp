@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Approve Order Paket')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-12 col-md-12" id="table">

    </div>
</div>
<script>
    $(document).ready(function () {
        $('#table').load("{{route('table_order_approve')}}")
    });

    function simpan(id)
    {
        var status = $('#approve').val()
        var idorder = id
        axios.post("{{url('/api/approve/order')}}",{
            id_order:id,
            status:status,
        }).then(function(res){
            var data = res.data
            console.log(data.status)
            if(data.status == 200)
            {
                toastr.info(data.message)
                $('#table').load("{{route('table_order_approve')}}")
            }else{
                toastr.info("Pemeriksaan Tidak Valid")
            }
        })
    }
</script>
@endsection
