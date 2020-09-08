<?php

//namespace lib\mylib;

class Validation
{
    public $rule_set = [];
    public $errors = [];
    public $old = [];

    protected $error_messages = [
        'required_form' => ':labelは、必須です。',
        'valid_email' => ':labelは、正しいメールアドレスの形式ではありません。',
        'valid_tel' => ':labelは、正しい電話番号の形式ではありません。',
        'valid_zip' => ':labelは、正しい郵便番号の形式ではありません。',
        'valid_img' => ':labelは、正しい画像の形式ではありません。',
        'valid_url' => ':labelは、有効なURLではありません。',
        'valid_ip' => ':labelは、有効なIPアドレスではありません。',
        'min_length' => ':labelは、:param文字以上で入力して下さい。',
        'max_length' => ':labelは、:param文字以下で入力して下さい。',
        'numeric_min' => ':labelは、:param以上の数値を入力して下さい。',
        'numeric_max' => ':labelは、:param以下の数値を入力して下さい。',
        'valid_kana' => ':labelは、半角で入力して下さい。',
    ];
    //バリデーションのルール設定
    public function __construct($name = [])
    {
        session_start();
        $this->rule_set = $name;
        return $this->rule_set;
    }


    //実行
    public function run($success_url, $failure_url)
    {
        $_SESSION['s_valid'] = $this->errors;

        $_SESSION['old'] = $this->old;
        $_SESSION['s_valid_decision'] = true;
        if ($this->errors == []) {
            //echo "バリデーション成功";
            header("Location: " . $success_url);
            exit();
        } else {
            header("Location: " . $failure_url);
            exit();
        }
    }

    public function checkValidation($input_val = [])
    {
        for ($i = 0; $i < count($this->rule_set); $i++) {
            $this->filterRule($this->rule_set[$i][0], $this->rule_set[$i][1], $input_val[$i]);
        }
    }

    protected function filterRule($rule_name, $rule_label, $input_val)
    {
        //ここで値を取得
        //配列から文字列に戻してチェック
        $input_val = implode($input_val);
        $input_box = $input_val;
        if (preg_match('/:get/', $input_box)) {
            $input_box = str_replace(':get', '', $input_box);
            if (isset($_GET[$input_box])) {
                $input_box = $_GET[$input_box];
            }
        } else {
            if (isset($_POST[$input_box])) {
                $input_box = $_POST[$input_box];
            }
        }
        //ここで値を取得

        $null_form = false;

        if (preg_match('/null_form\|/', $rule_name)) {
            // |はエスケープ
            $rule_name = str_replace('null_form|', '', $rule_name);
            $null_form = true;
        }



        $rule_name = explode("|", $rule_name);
        foreach ($rule_name as $rule) {
            if ($null_form == false) {
                $this->filterRule_switch($input_box, $rule, $rule_label, $input_val);
            }

            if ($null_form == true && $this->NovalidationRequireForm($input_box, $rule)) {
                $this->filterRule_switch($input_box, $rule, $rule_label, $input_val);
            }
            if ($rule == "valid_img") {
                //ファイルアップロード時
                $this->old[$input_val][0] = $_FILES[$input_val]['name'];
                $this->old[$input_val][1] = $_FILES[$input_val]['tmp_name'];
            } else {
                $this->old[$input_val][0] = htmlspecialchars($input_box, ENT_QUOTES, "UTF-8");;
            }
        }

        //$this->old[$input_val][0] = $input_box;
    }


    public function filterRule_switch($input_box, $rule, $rule_label, $input_val)
    {
        switch ($rule) {
            case 'required_form':
                if ($this->validationRequireForm($input_box)) {
                    $this->setError($rule, $rule_label, $input_val);
                }
                break;
            case 'valid_email':
                if ($this->validationValidEmail($input_box)) {
                    $this->setError($rule, $rule_label, $input_val);
                }
                break;
            case 'valid_tel':
                if ($this->validationValidTel($input_box)) {
                    $this->setError($rule, $rule_label, $input_val);
                }
                break;

            case 'valid_zip':
                if ($this->validationValidZip($input_box)) {
                    $this->setError($rule, $rule_label, $input_val);
                }
                break;

            case 'valid_img':
                //$input_box = $_FILES[$input_val]['name'];
                $input_box = $_FILES[$input_val]['name'];

                if ($this->validationValidImg($input_box)) {
                    $this->setError($rule, $rule_label, $input_val);
                }
                break;
            case 'valid_url':
                if ($this->validationValidUrl($input_box)) {
                    $this->setError($rule, $rule_label, $input_val);
                }
                break;
            case 'valid_ip':
                if ($this->validationValidIp($input_box)) {
                    $this->setError($rule, $rule_label, $input_val);
                }
                break;
            case preg_match("/^min_length:[0-9]+$/", $rule) === 1:
                $num = str_replace('min_length:', '', $rule);
                //echo mb_strlen($input_box);
                if ($this->validationMinLength($input_box, $num)) {
                    $this->setError("min_length", $rule_label, $input_val, $num);
                }
                break;
            case preg_match("/^max_length:[0-9]+$/", $rule) === 1:
                $num = str_replace('max_length:', '', $rule);
                if ($this->validationMaxLength($input_box, $num)) {
                    $this->setError("max_length", $rule_label, $input_val, $num);
                }
                break;
            case preg_match("/^numeric_min:[0-9]+$/", $rule) === 1:
                $num = str_replace('numeric_min:', '', $rule);
                //echo mb_strlen($input_box);
                if ($this->validationNumericMin($input_box, $num)) {
                    $this->setError("numeric_min", $rule_label, $input_val, $num);
                }
                break;
            case preg_match("/^numeric_max:[0-9]+$/", $rule) === 1:
                $num = str_replace('numeric_max:', '', $rule);
                if ($this->validationNumericMax($input_box, $num)) {
                    $this->setError("numeric_max", $rule_label, $input_val, $num);
                }
                break;
            case 'valid_kana':
                if ($this->validationKana($input_box)) {
                    $this->setError($rule, $rule_label, $input_val);
                }
                break;
        }
    }



    public function validationEmpty($val)
    {
        $decision_strlen = false;
        if (empty($val)) {
            $decision_strlen = true;
        }

        return $decision_strlen;
    }

    public function NovalidationEmpty($val, $rule)
    {
        $decision_strlen = true;
        if (empty($val)) {
            $decision_strlen = false;
        }
        if ($rule == "valid_img") {
            if (empty($_FILES[$val]['tmp_name'])) {
                //ファイルがアップロードされていないとき
                $decision_strlen = false;
            }
        }

        return $decision_strlen;
    }

    public function validationRequireForm($input)
    {
        //$input get post スーパーグローバル変数
        return $this->validationEmpty($input);
    }
    public function NovalidationRequireForm($input, $rule)
    {
        //$input get post スーパーグローバル変数
        return $this->NovalidationEmpty($input, $rule);
    }

    public function validationValidEmail($input)
    {
        //メールアドレス判定
        $pattern = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/";
        $decision = "";

        if (preg_match($pattern, $input)) {
            //メールアドレスが正しいとき時エラー表示しない
            $decision = false;
        } else {
            $decision = true;
        }

        return $decision;
        //return filter_var($input, FILTER_VALIDATE_EMAIL);
    }

    public function validationValidTel($input)
    {
        //電話番号判定
        $pattern = "/^(0{1}\d{1,4}-{0,1}\d{1,4}-{0,1}\d{4})$/";

        $decision = "";

        if (preg_match($pattern, $input)) {
            //メールアドレスが正しいとき時エラー表示しない
            $decision = false;
        } else {
            $decision = true;
        }
        return $decision;
    }
    public function validationValidZip($input)
    {
        //$zip = mb_convert_kana($input, 'a', 'utf-8');
        $pattern01 = "/^\d{3}\-\d{4}$/";
        $pattern02 = "/^\d{7}$/";
        $decision = "";
        if (preg_match($pattern01, $input) || preg_match($pattern02, $input)) {
            $decision = false;
        } else {
            $decision = true;
        }
        return $decision;
    }

    public function validationValidUrl($input)
    {
        return !filter_var($input, FILTER_VALIDATE_URL);
    }

    public function validationValidIp($input)
    {
        return !filter_var($input, FILTER_VALIDATE_IP);
    }

    public function validationValidImg($input)
    {
        $pattern = "/\.gif$|\.png$|\.jpg$|\.jpeg$/i";

        $decision = "";

        if (preg_match($pattern, $input)) {
            //画像が正しいとき時エラー表示しない
            $decision = false;
        } else {
            $decision = true;
        }
        return $decision;
    }

    public function validationMinLength($input, $rule)
    {
        return mb_strlen($input) < $rule;
    }

    public function validationMaxLength($input, $rule)
    {
        return mb_strlen($input) > $rule;
    }


    public function validationNumericMin($input, $rule)
    {
        return $input < $rule;
    }

    public function validationNumericMax($input, $rule)
    {
        return $input > $rule;
    }

    public function validationKana($input)
    {
        $decision = "";
        if ($input != mb_convert_kana($input, "ah", "UTF-8")) {
            //半角ではない
            $decision = true;
        } else {
            $decision = false;
        }
        return $decision;
    }

    protected function setError($rule_name, $rule_label, $input_val, $param = "")
    {
        $error_message = $this->error_messages[$rule_name];


        //置換
        $error_message = str_replace(':label', $rule_label, $error_message);
        if (!empty($param)) {
            // echo $param;
            $error_message = str_replace(':param', $param, $error_message);
        }
        $this->errors[$input_val][] = $error_message;
    }
}
