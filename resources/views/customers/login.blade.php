@extends('auth.master')

@section('content')

    <div class="card">
        <div class="card-body p-4">

            <div class="text-center w-75 m-auto">
                <a href="">
                    <span><img src="/images/insura-logo@2.png"></span>
                </a>
                <p class="text-muted mb-4 mt-3">Enter your Quote Number and Zip Code to access your quote details.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-4 mt-4 font-13">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="post">
                {{ csrf_field() }}
                <div class="form-group mb-3">
                    <label for="quote_no">Quote Number</label>
                    <input class="form-control" type="text" name="quote_no" placeholder="Enter your quote number" value="{{ old('quote_no') }}">
                </div>

                <div class="form-group mb-3">
                    <label for="zip">Zip Code</label>
                    <input class="form-control" type="text" name="zip" placeholder="Enter your zip code" value="{{ old('zip') }}">
                </div>

                <div class="form-group mb-0 text-center">
                    <button class="btn btn-primary btn-block" type="submit"> Find Quote </button>
                </div>

            </form>
        </div>
    </div>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/597f1b25dc0d70602312c868/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
@endsection