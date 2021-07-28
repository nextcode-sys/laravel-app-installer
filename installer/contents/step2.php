<div class="row pt-5">
    <div class="col-12">
        <ol class="text-dark stepBar step3">
            <li class="step">STEP1</li>
            <li class="step current">STEP2</li>
            <li class="step">STEP3</li>
        </ol>
    </div>
</div>

<div class="jumbotron mt-5 text-dark">
    <h1 class="display-4">STEP2</h1>
    <p class="lead">データベース、メールの設定を行います。</p>

    <br>

    <h5>データベース（MySQL）</h5>
    <div class="form-group">
        <label for="mysql_host">ホスト</label>
        <input type="text" class="form-control input_mysql_host" id="mysql_host" placeholder="example.com">
    </div>
    <div class="form-group">
        <label for="mysql_port">ポート</label>
        <input type="number" class="form-control input_mysql_port" id="mysql_port" value="3306" placeholder="3306">
    </div>
    <div class="form-group">
        <label for="mysql_database">データベース</label>
        <input type="text" class="form-control input_mysql_database" id="mysql_database" placeholder="database">
    </div>
    <div class="form-group">
        <label for="mysql_username">ユーザ名</label>
        <input type="text" class="form-control input_mysql_username" id="mysql_username" placeholder="root">
    </div>
    <div class="form-group">
        <label for="mysql_password">パスワード</label>
        <input type="password" class="form-control input_mysql_password" id="mysql_password">
    </div>

    <br>

    <h5>メール</h5>
    <div class="form-group">
        <label for="mail_driver">ドライバー</label>
        <select class="form-control select_mail_driver" id="mail_driver">
            <option value="sendmail">sendmail</option>
            <option value="smtp">SMTP</option>
        </select>
    </div>
    <div class="smtp_show_box">
        <div class="form-group">
            <label for="mail_host">ホスト</label>
            <input type="text" class="form-control input_mail_host" id="mail_host" placeholder="example.com">
        </div>
        <div class="form-group">
            <label for="mail_port">ポート</label>
            <input type="number" class="form-control input_mail_port" id="mail_port" value="587" placeholder="587">
        </div>
        <div class="form-group">
            <label for="mail_username">ユーザ名</label>
            <input type="text" class="form-control input_mail_username" id="mail_username">
        </div>
        <div class="form-group">
            <label for="mail_password">パスワード</label>
            <input type="password" class="form-control input_mail_password" id="mail_password">
        </div>
        <div class="form-group">
            <label for="mail_encryption">暗号化方式</label>
            <select class="form-control select_mail_encryption" id="mail_encryption">
                <option value="">指定なし</option>
                <option value="tls">TLS</option>
                <option value="ssl">SSL</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="mail_from_addr">送り元メールアドレス</label>
        <input type="email" class="form-control input_mail_from_addr" id="mail_from_addr" placeholder="test@example.com">
    </div>
    <div class="form-group">
        <label for="mail_from_name">送り元名</label>
        <input type="text" class="form-control input_mail_from_name" id="mail_from_name" placeholder="山田　太郎">
    </div>
    <div class="form-group">
        <label for="mail_test_addr">テストメール送信先アドレス</label>
        <input type="text" class="form-control input_mail_test_addr" id="mail_test_addr" placeholder="test@example.com">
    </div>

    <hr class="my-4">
    <input type="button" class="btn btn-primary btn-lg btn_run" value="実行する">
</div>


<script>
    $(function() {

        $('.select_mail_driver').on('change', function() {
            if ( $(this).val() === 'smtp' ) {
                $('.smtp_show_box').show();
            } else {
                $('.smtp_show_box').hide();
            }
        });
        $('.select_mail_driver').trigger('change');

        $('.btn_run').on('click', function() {
            // ローダーの表示
            showLoader();

            let params = {
                'mysql_host' : $('.input_mysql_host').val(),
                'mysql_port' : $('.input_mysql_port').val(),
                'mysql_database' : $('.input_mysql_database').val(),
                'mysql_username' : $('.input_mysql_username').val(),
                'mysql_password' : $('.input_mysql_password').val(),
                'mail_driver' : $('.select_mail_driver').val(),
                'mail_host' : $('.input_mail_host').val(),
                'mail_port' : $('.input_mail_port').val(),
                'mail_username' : $('.input_mail_username').val(),
                'mail_password' : $('.input_mail_password').val(),
                'mail_encryption' : $('.select_mail_encryption').val(),
                'mail_from_addr' : $('.input_mail_from_addr').val(),
                'mail_from_name' : $('.input_mail_from_name').val(),
                'mail_test_addr' : $('.input_mail_test_addr').val(),
                'submit' : 1,
            };

            // ステップ2の実行
            installer_api.step2( params, function ( response ) {
                // エラーがあれば、エラーメッセージの表示
                if ( response && ! response.result && response.message ) {
                    errorMessage( response.message );
                }
                // 成功したら、成功メッセージの表示
                if ( response && response.result && response.message ) {
                    successMessage( response.message );

                    // 指定秒数後にページ更新
                    setTimeout( function () {
                        location.reload();
                    }, 7000 );
                }

                // ローダーの非表示
                hideLoader();
            } );
        });
    });
</script>
