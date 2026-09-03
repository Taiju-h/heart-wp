# Heartful WordPress 作業ルール

作業前に本ファイルを確認してください。

共通のURL振り分け・Git運用ルールは `HEARTFUL_PROJECT.md` にあります。
作業対象を決める前に、同ファイルも必ず確認してください。

- Repository: Taiju-h/heart-wp
- Branch: master
- Git root: /home/heartf/heartf.com/public_html/uranai/wp-content
- Production URL: https://uranai.heartf.com/
- Deployment dashboard: https://uranai.heartf.com/deploy-dashboard-uranai.php
- Heartful common IP management: https://uranai.heartf.com/deployment/deploy-access-admin.php

## URLからGitを選ぶルール

URLは、最も具体的に一致するルールから判定してください。

| 対象URL | システム | 使用するGit |
| --- | --- | --- |
| `https://uranai.heartf.com/Public` 以下 | CakePHP2 | Cake2 Git |
| `https://uranai.heartf.com/`（上記 `/Public` を除く） | WordPress | `Taiju-h/heart-wp` |
| `https://test-vps.heartful.work/` | CakePHP4 | Cake4 Git |
| `https://sys-web.heartful.work/` | CakePHP4 | Cake4 Git |
| `https://sys-vps.heartful.work/` | CakePHP4 | Cake4 Git |

- 編集前に、対象URL・システム・Gitリポジトリ・ブランチを確認してください。
- 対象が曖昧な場合は推測で編集せず、ユーザーへ確認してください。
- CakePHP2の親Git、WordPressの子Git、CakePHP4のGitを混同しないでください。
- 現在のリポジトリはWordPress専用です。CakePHP2／CakePHP4のファイルを追加しないでください。

## Git対象外

- uploads
- ai1wm-backups
- cache
- languages
- upgrade
- ログとDBバックアップ
- themes/test/inc/secret.inc.php
- APIキー、パスワード、秘密鍵

## 作業と引き継ぎ

- 修正はGitへコミットしてからデプロイしてください。
- 本番反映を依頼された場合は、`master` へのpush成功を確認してからDeployment dashboardを使用してください。
- Deployment dashboardがアクセス承認待ち画面へ移動した場合は回避せず、管理者の承認後にデプロイを続行してください。
- デプロイ後は対象の公開URLを開き、変更内容と表示崩れの有無を確認してください。
- 作業完了時は、対象Git、ブランチ、変更ファイル、検証結果、コミット、pushの成否を報告してください。
- 切り戻しやすいように、目的の異なる変更を同じコミットへ混ぜないでください。
- URL振り分け、リポジトリ、ブランチ、配置先、デプロイ、テスト方法などの恒久的な新情報が判明したら、同じ作業内で `AGENTS.md` を更新してください。
- 複数Gitに共通する情報が変わった場合は `HEARTFUL_PROJECT.md` も更新し、利用可能な各Gitの `AGENTS.md` に同じ要点を反映してください。
- APIキー、パスワード、秘密鍵、DB接続情報などはドキュメントやコミットへ記載しないでください。

## デプロイ許可IP

WordPress本番デプロイはCakePHP4・CakePHP2と同じ共通IP台帳を使用します。IPの追加・削除は、Cake4のsys管理者ダッシュボードにある「共通IP管理」から、ID・パスワード認証後に行います。WordPress Git内へ独自のIP一覧や認証情報を追加しないでください。
