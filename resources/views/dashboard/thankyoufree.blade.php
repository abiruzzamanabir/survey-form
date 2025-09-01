@php
    use App\Models\Theme;
    $theme = Theme::findOrFail(1);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Thank you - {{ $name }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/img/' . $theme->favicon) }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <style>
        body {
            background-color: #e9ebee;
            background-image: url('{{ asset('assets/img/' . $theme->background) }}');
        }

        .container {
            margin-top: 30px;
        }

        .card {
            background: #f1f3f6;
            border: none;
            border-radius: 15px;
        }

        .card-header {
            background: #e9ebee;
            border-bottom: none;
            border-radius: 15px 15px 0 0;
        }

        .card-body {
            background: #f1f3f6;
            border-top: none;
        }

        .card-footer {
            background: #e9ebee;
            border-top: none;
            border-radius: 0 0 15px 15px;
        }

        .text-muted {
            color: #8792a1;
        }

        .btn-primary {
            background: #d1d9e6;
            color: #5e6687;
            border: none;
            box-shadow: 4px 4px 8px #b7bfcf, -4px -4px 8px #ffffff;
        }

        .btn-primary:hover {
            background: #b1b9c6;
            color: #4d5470;
        }

        .btn-primary:focus {
            box-shadow: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center g-2 mt-3">
            <div class="col-md-8">
                @include('validate')

                <div>
                    <div class="card shadow">
                        <div class="card-header">
                            <h2 class="text-center">
                                Thank You <span class="text-muted text-capitalize">{{ $name }}</span>!
                            </h2>
                        </div>

                        <div class="card-body">
                            <h5 class="text-center text-muted">
                                Your survey submission with ID: <b class="text-dark">{{ $ukey }}</b> has been
                                received
                                successfully.
                            </h5>
                            <p class="text-center">
                                We appreciate your time and input.
                            </p>
                        </div>

                        <div class="d-flex justify-content-around my-3">
                            <a href="{{ route('form.index') }}" class="btn btn-md w-50 btn-primary mx-2">
                                Submit Another Survey
                            </a>
                        </div>

                        <div class="card-footer text-center">
                            @include('footer')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
