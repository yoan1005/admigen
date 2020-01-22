@extends('admigen::layout')

@section('content')

<div  class="content">
	<div class="container-fluid">
		<div class="row">
		<div class="col-md-12">
				<div class="card">
					<div class="header">
						<h4 class="title">{{class_basename($instance)}}</h4>
					</div>

					<div class="content">
						<form method="post" enctype="multipart/form-data">
						{{ csrf_field() }}

						<input type="hidden" name="id" value="{{ isset($instance->id)? $instance->id : 0 }}">
							<div class="row">
                @foreach ($instance->getFillable() as $key)
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{$key}}</label>
                      <input type="text" class="form-control" placeholder="" value="{{ isset($instance->$key)? $instance->$key : '' }}" name="{{$key}}">
                    </div>
                  </div>
                @endforeach
              </div>
                <div class="clearfix"></div>

							<button type="submit" class="btn btn-info btn-fill pull-right">Enregistrer</button>
							<div class="clearfix"></div>
						</form>

            @if ($instance->id)
              @php
                $arr_key_img = ['visuel', 'image', 'img', 'photo'];
              @endphp
              @foreach ($arr_key_img as $k)
                @if (isset($instance->$k))
                  @php $key_img = $instance->$k; @endphp
                  <div class="row">

                    <div class="col-md-4">
                        <div class="row">
                          <div class="col-sm-10 offset-sm-1">
                            <b class="page-heading">Image</b>

                            @if (isset($instance->$key_img))
                              <div class="clearfix"></div>
                              <div class="" style="">
                                <img src="/public/{{ $instance->$key_img }}" alt="" width="200" class="img-responsive">
                                <div class="clearfix"></div>
                                <a onclick="return confirm('Êtes vous sûr ?')" href="{{ route('deleteImg', ['id' => $instance->id, 'model' => class_basename($instance), 'field' => $key_img]) }}">Supprimer</a>
                                </div>
                            @endif
                            <form method="post" action="{{ url('/admin/image/save') }}"
                            enctype="multipart/form-data" class="dropzone" id="my-dropzone">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ isset($instance->id)? $instance->id : 0 }}">
                            <input type="hidden" name="model" value="{{class_basename($instance)}}">
                            <input type="hidden" name="field" value="{{$key_img}}">
                            <div class="dz-message">
                              <div class="col-xs-8">
                                <div class="message">
                                  <p>Glissez/déposez ici ou clickez</p>
                                </div>
                              </div>
                            </div>
                            <div class="fallback">
                              <input type="file" name="file" multiple>
                            </div>
                          </form>
                        </div>
                      </div>

                      {{--Dropzone Preview Template--}}
                      <div id="preview" style="display: none;">

                        <div class="dz-preview dz-file-preview">
                          <div class="dz-image"><img data-dz-thumbnail /></div>

                          <div class="dz-details">
                            <div class="dz-size"><span data-dz-size></span></div>
                            <div class="dz-filename"><span data-dz-name></span></div>
                          </div>
                          <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                          <div class="dz-error-message"><span data-dz-errormessage></span></div>



                          <div class="dz-success-mark">

                            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                              <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                              <title>Check</title>
                              <desc>Created with Sketch.</desc>
                              <defs></defs>
                              <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>
                              </g>
                            </svg>

                          </div>
                          <div class="dz-error-mark">

                            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                              <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                              <title>error</title>
                              <desc>Created with Sketch.</desc>
                              <defs></defs>
                              <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">
                                  <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>
                                </g>
                              </g>
                            </svg>
                          </div>
                        </div>
                      </div>
                      {{--End of Dropzone Preview Template--}}
                      </div>

                  </div>
                @endif
              @endforeach

            @endif

					</div>
				</div>
			</div>

		</div>


	</div>
</div>
@endsection

@section('styles')
<style>
	.page-heading {
    margin: 20px 0;
    color: #666;
    -webkit-font-smoothing: antialiased;
    font-family: "Segoe UI Light", "Arial", serif;
    font-weight: 600;
    letter-spacing: 0.05em;
}

#my-dropzone .message {
    font-family: "Segoe UI Light", "Arial", serif;
    font-weight: 600;
    color: #0087F7;
    font-size: 1.5em;
    letter-spacing: 0.05em;
}

.dropzone {
    border: 2px dashed #0087F7;
    background: white;
    border-radius: 5px;
    min-height: 300px;
    padding: 90px 0;
    vertical-align: baseline;
}
</style>
@endsection

@section('scripts')
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript">
	// reordonner Catégories
	var photos_array = [];
	var model = "";
	$( "#v-pills-tab" ).sortable( {
	  stop: function(e, ui) {
	    $('.sortable').each(function(i) {
	      var id_photo = parseInt($(this).attr('data-pageId'));
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
	</script>
<script>

 var total_photos_counter = 0;
Dropzone.options.myDropzone = {
    uploadMultiple: false,
    parallelUploads: 2,
    maxFilesize: 16,
    previewTemplate: document.querySelector('#preview').innerHTML,
    addRemoveLinks: false,
    dictFileTooBig: 'Image is larger than 16MB',

    init: function () {
        this.on("removedfile", function (file) {
            $.post({
                url: '/admin/{{mb_strtolower(class_basename($instance))}}/images-delete',
                data: {id:{{isset($instance->id)? $instance->id : 0}}, name: file.name, _token: $('[name="_token"]').val()},
                dataType: 'json',
                success: function (data) {
                    total_photos_counter--;
                    $("#counter").text("# " + total_photos_counter);
                }
            });
        });
    },
    success: function (file, data) {

    	$('input[name="id"]').val(data.model_id)
    }
};

</script>
@endsection
