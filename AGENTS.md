# Heartful WordPress 作業ルール

作業前に本ファイルを確認してください。

- Repository: Taiju-h/heart-wp
- Branch: master
- Git root: /home/heartf/heartf.com/public_html/uranai/wp-content
- Production URL: https://uranai.heartf.com/

## Git対象外

- uploads
- ai1wm-backups
- cache
- languages
- upgrade
- ログとDBバックアップ
- themes/test/inc/secret.inc.php
- APIキー、パスワード、秘密鍵

修正はGitへコミットしてからデプロイしてください。
CakePHP2の親GitとWordPressの子Gitを混同しないでください。
