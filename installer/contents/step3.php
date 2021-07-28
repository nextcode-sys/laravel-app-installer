<div class="row pt-5">
    <div class="col-12">
        <ol class="text-dark stepBar step3">
            <li class="step">STEP1</li>
            <li class="step">STEP2</li>
            <li class="step current">STEP3</li>
        </ol>
    </div>
</div>

<div class="jumbotron mt-5 text-dark">
    <h1 class="display-4">STEP3</h1>
    <p class="lead">管理者アカウントの作成行います。<br><span class="text-danger">※最後にインストーラーは削除されます。</span></p>

    <br>

    <h5>管理者アカウント</h5>
    <div class="form-group">
        <label for="admin_username">ユーザ名</label>
        <input type="text" class="form-control input_admin_username" id="admin_username" placeholder="管理者">
    </div>
    <div class="form-group">
        <label for="admin_email">メールアドレス<span class="text-danger">※ログインに使用します</span></label>
        <input type="text" class="form-control input_admin_email" id="admin_email" placeholder="test@example.com">
    </div>
    <div class="form-group">
        <label for="admin_password">パスワード</label>
        <input type="password" class="form-control input_admin_password" id="admin_password">
    </div>

    <hr class="my-4">
    <input type="button" class="btn btn-primary btn-lg btn_run" value="実行する">
</div>


<script>
    $(function() {

        $('.btn_run').on('click', function() {
            // ローダーの表示
            showLoader();

            let params = {
                'admin_username' : $('.input_admin_username').val(),
                'admin_email' : $('.input_admin_email').val(),
                'admin_password' : $('.input_admin_password').val(),
                'submit' : 1,
            };

            // ステップ3の実行
            installer_api.step3( params, function ( response ) {
                // エラーがあれば、エラーメッセージの表示
                if ( response && ! response.result && response.message ) {
                    errorMessage( response.message );
                }
                // 成功したら、成功メッセージの表示
                if ( response && response.result && response.message ) {
                    successMessage( response.message );

                    // 指定秒数後にページ更新
                    setTimeout( function () {
                        location.href = '/';
                    }, 7000 );
                }

                // ローダーの非表示
                hideLoader();
            } );
        });
    });
</script>
