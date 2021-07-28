<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <title>インストーラー</title>

    <!-- Fonts -->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>

    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Styles -->
    <link href="/installer/css/installer.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="//code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="/installer/js/all.js"></script>
    <script src="/installer/js/installer_api.js"></script>
</head>
<body class="bg-dark text-light">

    <div class="loader-wrapper fade" style="display: none;">
        <div class="loader-body">
            <div class="loader">Loading...</div>
            <p>完了までしばらくお待ち下さい。</p>
        </div>
    </div>

    <div class="container">

        <div class="row pt-5 success-message-wrapper" style="display: none">
            <div class="col-12">
                <div class="alert alert-success" role="alert"><i class="c-icon cil-check"></i><span class="txt-success-message"></span></div>
            </div>
        </div>

        <div class="row pt-5 error-message-wrapper" style="display: none">
            <div class="col-12">
                <div class="alert alert-danger" role="alert"><i class="c-icon cil-warning"></i><span class="txt-error-message"></span></div>
            </div>
        </div>
