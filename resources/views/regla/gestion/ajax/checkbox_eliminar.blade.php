
@if ($sw == 1) 
<div class="text-center be-checkbox be-checkbox-sm has-danger">
  <input  type="checkbox"
    class="e{{$indexp}}{{$index}} input_eliminar"
    id="e{{$indexp}}{{$index}}" >

  <label  for="e{{$indexp}}{{$index}}"
        data-atr = "ver"
        class = "checkbox checkbox_eliminar"                    
        name="e{{$indexp}}{{$index}}"
  ></label>
</div>
@endif
