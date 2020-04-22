<table class="table table-hover table-striped">
<?php if (count($datas) > 0): ?>

    <thead>
        <tr>
         @php $headers = collect($datas->first()->toArray())->only($fields) @endphp
         @foreach($headers as $key => $v)
         @if (array_key_exists($key, $trad))
         <th>{{$trad[$key]}}</th>
         @endif
         @endforeach
         <th>Actions</th>
     </tr>
 </thead>
 <tbody>
    @foreach($datas as $data)
    <tr class="line" id="{{$data->id}}" data-model="{{class_basename($data)}}">
     @if (isset($data))
     @php $data = collect($data->toArray())->only($fields) @endphp
     @foreach($data as $k => $value)
     @if($k != "user_id")
     @if (isset($value))
     @if ($k == 'user_name')
     <td><a href="{{$data['user_id']}}">{{$value}}</a></td>

     @elseif ($k == 'status')
     <td>
        @if($value == 0)
        <i class="pe-7s-key"></i>

        @elseif($value == 1)
        <i class="pe-7s-users"></i>

        @else
        <i class="pe-7s-global"></i>
        @endif
    </td>
  @elseif ($k == 'picture' || $k == 'visuel' || $k == 'logo' || $k == 'photo')
   <td><img src="{{ url($value) }}" alt="" height="50" ></td>

    @elseif ($k == 'color')
    <td><span style="height: 20px; width: 60px; background: {{ $value }}; display: inline-block;"></span></td>

    @elseif ($k == 'required' || $k == 'avaible')
     <td>
       @if($value == 1)
        <i class="pe-7s-check"></i>
        @else
        <i class="pe-7s-close-circle"></i>
        @endif
    </td>
    @elseif ($k == 'moderate')
    <td>
      @if($value == 1)
       <i onclick="changeState({{$data['id']}}, '{{strtolower(class_basename($datas->first()))}}', this)" class="pe-7s-check" style="font-size: 1.5em;color: green"></i>
       @else
       <i onclick="changeState({{$data['id']}}, '{{strtolower(class_basename($datas->first()))}}', this)" class="pe-7s-close-circle" style="font-size: 1.5em;color: red"></i>
       @endif
   </td>
    @else
    <td>{{ str_limit($value, 50) }}</td>
    @endif
    @else
    <td>&nbsp;</td>
    @endif
    @endif
    @endforeach
    @endif
    <td>
        <?php if (isset($can) && $can['show']): ?>
        <a href="{{ route('admin.show', ['model' => strtolower(class_basename($datas->first())),'id' => $data['id'] ]) }}">
            <i class="pe-7s-look"></i>
        </a>
        <?php endif ?>
        <?php if (isset($can) && $can['edit']): ?>
        <a href="{{ route('admin.edit', ['model' => strtolower(class_basename($datas->first())),'id' => $data['id'] ]) }}">
            <i class="pe-7s-edit"></i>
        </a>
        <?php endif ?>
        <?php if (isset($can) && $can['delete']): ?>
        <a onclick="return confirm('Êtes vous sûr ?')" href="{{ route('admin.delete', ['model' => strtolower(class_basename($datas->first())),'id' => $data['id'] ]) }}">
            <i class="pe-7s-trash"></i>
        </a>
        <?php endif ?>

    </td>
</tr>
@endforeach
</tbody>
<?php endif ?>

</table>
