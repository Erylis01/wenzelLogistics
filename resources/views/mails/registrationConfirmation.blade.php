@component('mail::message')
    Welcome {{$username}} !

    You have been well registered in our website.

Thanks,<br>
{{ config('app.name') }}
    <img src="{{url('/image/wenzel_logistics.png')}}">
@endcomponent