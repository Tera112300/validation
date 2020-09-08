<?php
require_once "../Csrf.php";
session_start();
session_regenerate_id(true);
//クリックジャッキングの対策
header("X-FRAME-OPTIONS: DENY");

$s_valid = "";
$old = "";

if (empty($_SESSION['s_valid_decision'])) {
    //リロード時削除処理
    unset($_SESSION["s_valid"]);
    unset($_SESSION["olds"]);
}
if (!empty($_SESSION['s_valid']) && !empty($_SESSION['s_valid_decision'])) {
    $s_valid = $_SESSION['s_valid'];
    $old =  $_SESSION['old'];
    //s_valid_decisionを削除してリロード時はエラー文出させない
    unset($_SESSION["s_valid_decision"]);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォームデモ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="form.css" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <form action="check.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?php echo Csrf::generate(); ?>">
            <div class="form-group">
                <label>必須項目</label>
                <input type="text" class="form-control <?php if (!empty($s_valid['test01'])) echo "is-invalid"; ?>" name="test01" placeholder="Text input" value="<?php if (!empty($old['test01'][0])) echo $old['test01'][0]; ?>">
                <div class="invalid-feedback">
                    <?php
                    if (!empty($s_valid['test01'])) {
                        foreach ($s_valid['test01'] as $val) {
                            echo $val . '<br>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">空白チェックなし|メールアドレス</label>
                <input type="text" class="form-control <?php if (!empty($s_valid['test02'])) echo "is-invalid"; ?>" name="test02" placeholder="メールアドレスをご入力下さい" value="<?php if (!empty($old['test02'][0])) echo $old['test02'][0]; ?>">
                <div class="invalid-feedback">
                    <?php
                    if (!empty($s_valid['test02'])) {
                        foreach ($s_valid['test02'] as $val) {
                            echo $val . '<br>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">必須項目|半角入力|電話番号</label>
                <input type="text" class="form-control <?php if (!empty($s_valid['test03'])) echo "is-invalid"; ?>" name="test03" placeholder="電話番号をご入力下さい" value="<?php if (!empty($old['test03'][0])) echo $old['test03'][0]; ?>">
                <div class="invalid-feedback">
                    <?php
                    if (!empty($s_valid['test03'])) {
                        foreach ($s_valid['test03'] as $val) {
                            echo $val . '<br>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="inputFile">空白チェックなし|画像形式</label>
                <input type="file" class="form-control-file <?php if (!empty($s_valid['test04'])) echo "is-invalid"; ?>" name="test04" id="inputFile">
                <div class="invalid-feedback">
                    <?php
                    if (!empty($s_valid['test04'])) {
                        foreach ($s_valid['test04'] as $val) {
                            echo $old['test04'][0] . 'の' . $val . '<br>';
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="exampleFormControlSelect1">空白チェックなし|URL</label>
                <input type="text" class="form-control <?php if (!empty($s_valid['test05'])) echo "is-invalid"; ?>" name="test05" placeholder="URLをご入力下さい" value="<?php if (!empty($old['test05'][0])) echo $old['test05'][0]; ?>">
                <div class="invalid-feedback">
                    <?php
                    if (!empty($s_valid['test05'])) {
                        foreach ($s_valid['test05'] as $val) {
                            echo $val . '<br>';
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="mb-3">
                <p class="mb-1">空白チェックなし|2以上の数値|4以下の数値</p>
                <div class="form-group form-check form-check-inline mb-0">
                    <!-- ラジオボタン初期値がないのでhiddenで初期設定 -->
                    <input class="form-check-input" type="hidden" value="" name="test06" <?php if (empty($old['test06'][0])) echo 'checked'; ?>>
                    <input class="form-check-input" type="radio" id="inlineRadio1" value="1" name="test06" <?php if (!empty($old['test06'][0])) if ($old['test06'][0] == 1) echo 'checked'; ?>>
                    <label class="form-check-label" for="inlineRadio1">1番</label>
                </div>
                <div class="form-group form-check form-check-inline mb-0">
                    <input class="form-check-input" type="radio" id="inlineRadio2" value="2" name="test06" <?php if (!empty($old['test06'][0])) if ($old['test06'][0] == 2) echo 'checked'; ?>>
                    <label class="form-check-label" for="inlineRadio2">2番</label>
                </div>
                <div class="form-group form-check form-check-inline mb-0">
                    <input class="form-check-input" type="radio" id="inlineRadio3" value="3" name="test06" <?php if (!empty($old['test06'][0])) if ($old['test06'][0] == 3) echo 'checked'; ?>>
                    <label class="form-check-label" for="inlineRadio3">3番</label>
                </div>
                <div class="form-group form-check form-check-inline mb-0">
                    <input class="form-check-input" type="radio" id="inlineRadio4" value="4" name="test06" <?php if (!empty($old['test06'][0])) if ($old['test06'][0] == 4) echo 'checked'; ?>>
                    <label class="form-check-label" for="inlineRadio4">4番</label>
                </div>
                <div class="form-group form-check form-check-inline mb-0">
                    <input class="form-check-input" type="radio" id="inlineRadio5" value="5" name="test06" <?php if (!empty($old['test06'][0])) if ($old['test06'][0] == 5) echo 'checked'; ?>>
                    <label class="form-check-label" for="inlineRadio5">5番</label>
                </div>
                <div class="<?php if (!empty($s_valid['test06'])) echo "is-invalid"; ?>"></div>

                <div class="invalid-feedback mt-0">
                    <?php
                    if (!empty($s_valid['test06'])) {
                        foreach ($s_valid['test06'] as $val) {
                            echo $val . '<br>';
                        }
                    }
                    ?>
                </div>
            </div>




            <div class="form-group">
                <label for="exampleFormControlTextarea1">空白チェックなし|5文字以上|10文字以下</label>
                <textarea class="form-control <?php if (!empty($s_valid['test07'])) echo "is-invalid"; ?>" rows="5" placeholder="Textarea" name="test07"><?php if (!empty($old['test07'][0])) echo $old['test07'][0]; ?></textarea>
                <div class="invalid-feedback">
                    <?php
                    if (!empty($s_valid['test07'])) {
                        foreach ($s_valid['test07'] as $val) {
                            echo $val . '<br>';
                        }
                    }
                    ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">送信</button>
        </form>
    </div>
</body>

</html>