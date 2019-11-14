<div>
<b>Hi {{ $content['name'] }}</b><br>
{{ $content['message'] }}
<img src="{{ asset(config('constants.IMAGE_UPLOAD_PATH').'/'.$content['image']) }}" height="300px" width="300px" /> 
</div>