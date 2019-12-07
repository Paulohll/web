
@if ($sw == 0) 
<div class="text-center be-checkbox be-checkbox-sm has-primary">
  <input  type="checkbox"
    class="{{$indexp}}{{$index}} input_asignar"
    id="{{$indexp}}{{$index}}" >

  <label  for="{{$indexp}}{{$index}}"
        data-atr = "ver"
        class = "checkbox checkbox_asignar"                    
        name="{{$indexp}}{{$index}}"
  ></label>
</div>
@endif
