<?php
require_once "../Validation.php";
//インスタンス作成 第1引数バリデーションタイプ選択、第2引数エラーメッセージタイトル
$check = new Validation([['required_form', '名前'], ['null_form|valid_email', 'メール'], ['required_form|valid_kana|valid_tel', '電話番号'], ['null_form|valid_img', '形式'], ['null_form|valid_url', 'URL'], ['null_form|numeric_min:2|numeric_max:4', '数値'], ['null_form|min_length:5|max_length:10', '文字']]);

//checkValidation inputのname属性指定デフォルトはPOST 頭に:getをつけることでGETで値取得可能
$check->checkValidation([["test01"], ["test02"], ["test03"], ["test04"], ["test05"], ["test06"], ["test07"]]);

//・run 第1引数バリデーション完了のリダイレクト先
//・run 第2引数バリデーション失敗(エラー)のリダイレクト先
$check->run("save.php", "index.php");
