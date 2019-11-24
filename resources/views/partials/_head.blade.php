  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  {{-- <title>{{ $title or config('app.name')}}</title> --}}


  <!-- Fonts -->
  <style>
      @font-face {
          font-family: 'Roboto';
          src: {{ public_path('fonts\roboto') }};

          font-family: 'Open Sans';
          src: {{ public_path('fonts\open_sans') }};

          font-family: 'Montserrat';
          src: {{ public_path('fonts\montserrat') }};
      }
    </style>
  <!-- Styles -->
  @include('partials._stylesheets')


  <script>
      window.Laravel = {!! json_encode([
          'csrfToken' => csrf_token(),
      ]) !!};
  </script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
