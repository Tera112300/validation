# validationライブラリ

laravelを意識したバリデーションを組み合わせて使えるライブラリです。

# Note

demoディレクトリより実際に動いているものをご確認できます。

2020/09/09 以下のセキュリティ対策をデモページに施しました。
・セッション固定化攻撃
・クリックジャッキング対策
・CSRF対策(自作ライブラリ導入)

また、sessinon_startをライブラリに記載していましたが、ライブラリから削除して呼び出し側でsessinon_startするように変更

# Author

* 作成者 terao