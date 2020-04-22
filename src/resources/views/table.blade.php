@extends('admigen::layout')

@section('content')
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">

                             <?php if (isset($can) && $can['add']): ?>
                                    <a style="float:right" href="{{ route('admin.add' , ['model' => $model]) }}"><i class="pe-7s-plus"></i> Nouveau</a>
                             <?php endif ?>
                                <h4 class="title">Liste des {{ (isset($titleShow)) ? $titleShow : $canAddName }}</h4>

                            </div>
                            <div class="content table-responsive table-full-width">
                                @include('admigen::partials.table')
                                @if ($datas instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                  {{$datas->links()}}
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


@endsection

@section('scripts')
  <script>
      function changeState(id, model, el) {
        $.post('{{ route('admin.state') }}', {
          "_token": "{{csrf_token()}}",
          model: model,
          id: id
        }).done(function(data) {
          if (data.moderate == true) {
            $(el).removeClass('pe-7s-check pe-7s-close-circle').addClass('pe-7s-check').css('color', 'green')
          } else {
            $(el).removeClass('pe-7s-check pe-7s-close-circle').addClass('pe-7s-close-circle').css('color', 'red')


          }
          console.log('success', data);
        })
      }
  </script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
<?php if (isset($can) && $can['order']): ?>
// reordonner Catégories
var photos_array = [];
var model = "";
$( "table tbody" ).sortable( {
  stop: function(e, ui) {
    $('.line').each(function(i) {
      var id_photo = parseInt($(this).attr('id'));
      model = $(this).attr('data-model');
      var ordre_photo = i+1;
      photos_array.push({'id': id_photo, 'ordre': ordre_photo});
    });
    //ajax
    $.post('{{ route('admin.ordre') }}', {
      model: model,
      photos: photos_array
    }).done(function(data) {
      console.log('success', data);
    })
  }
}).disableSelection();
<?php endif ?>
</script>
@endsection
