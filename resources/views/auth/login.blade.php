@extends('layouts.app')

@section('content')

<?php
  $bg = array('img01.jpg', 'img02.jpg', 'img03.jpg', 'img04.jpg'); // array of filenames
  $i = rand(0, count($bg)-1); // generate random number size of the array
  $selectedBg = "$bg[$i]"; // set variable equal to which random filename was chosen
?>

<style type="text/css">
<!--
body{
background: url(images/<?php echo $selectedBg; ?>) no-repeat;
}
-->
</style>

<link rel="stylesheet" href=".\css\login.css"

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card-transparent">
            <br>
                <img src=".\images\almacen01.jpg" alt="" class="rounded mx-auto d-block img-fluid col-md-5">

                <div class="card-body special-card">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="col-md-4 col-form-label text-md-center">{{ __('Correo Electrónico') }}</label>

                            <div class="col-md-6 offset-md-3">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 col-form-label text-md-center">{{ __('Contraseña') }}</label>

                            <div class="col-md-6 offset-md-3">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 offset-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Recuérdame') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                <!--AQUI IBA EL DE RESETEAR LA CONTRASEÑA -->

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php
                if($i == 5){
                    echo('<div class="container credits col-md-6" style="text-align: center;">Artista: <a class="noDecoration"href="https://jackx201.github.io/" target="_blank ID="cred">@Jackx201</a></div>');  
                }
                ?>
        </div>
    </div>
</div>
@endsection
