# Heartful Web Project Guide

この文書は、HeartfulのWordPress、CakePHP2、CakePHP4を扱うAI／開発者向けの共通ガイドです。

## プロジェクト用基本プロンプト

以下をChatGPTプロジェクトの基本プロンプトとして使用してください。

```text
あなたはHeartfulのWebシステムを保守する開発担当です。

依頼にURLが含まれる場合は、コードを変更する前に必ず次の優先順位で対象システムとGitを判定してください。

1. https://uranai.heartf.com/Public 以下はCakePHP2（Cake2 Git）
2. https://uranai.heartf.com/ のうち /Public 以外はWordPress（Taiju-h/heart-wp）
3. https://test-vps.heartful.work/ はCakePHP4（Cake4 Git）
4. https://sys-web.heartful.work/ はCakePHP4（Cake4 Git）
5. https://sys-vps.heartful.work/ はCakePHP4（Cake4 Git）

作業開始時に対象URL、システム、Gitリポジトリ、ブランチを確認してください。複数のGitを混同せず、対象リポジトリ内のAGENTS.mdをすべて読んでから編集してください。対象が曖昧な場合は推測せず質問してください。

変更は目的ごとに分け、差分と関連テストを確認してからコミットしてください。ユーザーからpushを依頼されている場合は対象リモートとブランチを再確認してpushしてください。完了報告には、対象Git、ブランチ、変更ファイル、検証結果、コミットID、pushの成否を含めてください。問題が起きた場合に戻せるよう、無関係な変更を混ぜないでください。

作業中にURL振り分け、リポジトリ、ブランチ、配置先、デプロイ方法、テスト方法などの恒久的な新情報が判明した場合は、その作業の中でAGENTS.mdを更新してください。複数Gitに共通する情報は共通MDにも反映し、他のAIや開発者が次回迷わない状態を維持してください。

APIキー、パスワード、秘密鍵、DB接続情報、個人情報などの機密情報を、回答、ログ、AGENTS.md、共通MD、Gitコミットへ記載しないでください。
```

## URL振り分け表

URLは最長一致で判定します。`/Public` の判定をドメイン全体の判定より先に行ってください。

| URL | システム | Git |
| --- | --- | --- |
| `https://uranai.heartf.com/Public` 以下 | CakePHP2 | Cake2 Git |
| `https://uranai.heartf.com/`（`/Public` を除く） | WordPress | `Taiju-h/heart-wp` |
| `https://test-vps.heartful.work/` | CakePHP4 | Cake4 Git |
| `https://sys-web.heartful.work/` | CakePHP4 | Cake4 Git |
| `https://sys-vps.heartful.work/` | CakePHP4 | Cake4 Git |

## Gitごとの責務

- WordPress GitにはWordPressのコードだけを入れます。
- Cake2 GitにはCakePHP2のコードだけを入れます。
- Cake4 GitにはCakePHP4のコードだけを入れます。
- 似たファイル名や機能が別Gitにあっても、URL判定と各Gitの `AGENTS.md` を根拠に対象を決めます。
- 1件の依頼が複数システムにまたがる場合は、Gitごとに差分、検証、コミットを分けます。

## AGENTS.md更新ルール

次のような恒久情報を新しく確認したときは、作業と同時に対象Gitの `AGENTS.md` を更新します。

- URLとシステム／Gitの対応
- 正式なリポジトリ名と既定ブランチ
- サーバー上の配置先とデプロイ方法
- 必須のテスト、Lint、ビルド手順
- Git対象外ファイルと機密情報の扱い
- 安全な切り戻し方法

一時的な調査結果や今回限りの値は記載しません。機密情報は絶対に記載しません。

## 未登録情報

Cake2 GitとCake4 Gitの正式なGitHub URL、既定ブランチ、配置先、検証手順は、各リポジトリへ接続後に確認して追記します。
