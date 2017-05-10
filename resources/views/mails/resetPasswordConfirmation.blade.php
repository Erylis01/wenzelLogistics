@component('mail::message')
    Hello {{$lastname}} {{$firstname}},

We would like to inform you that you have updated your password online.
If it wasn't you, click on the button

@component('mail::button', ['url' => ''])
Reset password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
<img src="{{url('/image/wenzel_logistics.png')}}">
@endcomponent
