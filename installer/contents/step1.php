<div class="row pt-5">
    <div class="col-12">
        <ol class="text-dark stepBar step3">
            <li class="step current">STEP1</li>
            <li class="step">STEP2</li>
            <li class="step">STEP3</li>
        </ol>
    </div>
</div>

<div class="jumbotron mt-5 text-dark">
    <h1 class="display-4">STEP1</h1>
    <p class="lead">インストールや初期設定を行います。</p>
    <br>
    <div class="form-group">
        <label for="php_version">使用するPHPのバージョン</label>
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text bg-white">PHP</span></div>
            <select id="php_version" class="form-control select_php_version">
                <option value="7.2">7.2</option>
                <option value="7.3">7.3</option>
                <option value="7.4">7.4</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="app_name">アプリケーション名</label>
        <input type="text" class="form-control input_app_name" id="app_name" placeholder="本アプリケーションの名前（例：○○○○システム）">
    </div>
    <div class="form-group">
        <label for="app_url">アプリケーションURL</label>
        <input type="text" class="form-control input_app_url" id="app_url" placeholder="本アプリケーションのURL（例：https://example.com）">
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
                'php_version' : $('.select_php_version').val(),
                'app_name' : $('.input_app_name').val(),
                'app_url' : $('.input_app_url').val(),
                'submit' : 1,
            };

            // ステップ1の実行
            installer_api.step1( params, function ( response ) {
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
