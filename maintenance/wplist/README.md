# ネットワークのブログ一覧を更新する

対象URLは `https://heartf.com/wplist.php`、設置先は `/home/heartf/heartf.com/public_html/wplist.php`。このファイルは `uranai/wp-content` 外にあるため、WordPressテーマの通常デプロイでは更新されない。元ファイルは利用者から提供されたソースを確認済み。

`install.sh` に検証済みPHPを含めている。`wp-load.php` と既存の通常ファイルの存在、ペイロードのSHA-256、PHP 8.4の構文を確認してから、元ファイルをWEBROOT外の `/home/heartf/wplist-backups` へ退避し、同一ディレクトリ内の一時ファイルから置き換える。元の権限を保持し、再実行時に同一内容なら変更しない。DB、接続設定、テーマ、CakePHPは変更しない。

サーバーにこのスクリプトを置き、所有者ユーザー `heartf` で実行する。

```bash
bash install.sh
```

CLIや設置先が異なる検証環境では `HEARTFUL_WPLIST_ROOT`、`HEARTFUL_WPLIST_PHP_BIN`、`HEARTFUL_WPLIST_BACKUP_DIR` で明示できる。通常の本番実行は指定不要。

修正は、全公開ブログの取得（100件の上限を撤廃）、同時刻のブログを上書きしない整列、空記事・例外を含めたブログ切替の復元、公開記事の最大5件・30日以内の表示。従来の `s` で始まる学校ブログの除外を維持し、タイトル・リンクをエスケープする。

PHP 8.4.25で126ブログ・同時刻・件数・30日と5日の境界・除外対象・空表示・例外時の復元を模擬検証済み。インストーラーは `python3 maintenance/wplist/test_install.py` で、バックアップ、再実行、構文エラー、リンク先保護、改変検知、未初期化先を検証する。

反映後は公開一覧とHPでSUBROSA先生の「明るい未来の確信犯」を確認する。まだ出ない場合は本番の公開・アーカイブ状態と実際の取得対象順を確認し、100件制限が原因だったと断定しない。
