# aofinance — WordPress テーマ 導入マニュアル

## 概要

このフォルダ（`wordpress-theme/`）は、静的サイト **aofinance** を  
**WordPress カスタムテーマ**として再現したものです。  
デザイン・CSS・JavaScript はオリジナルをそのまま流用しています。

---

## フォルダ構成（完成後）

```
wp-content/themes/aofinance/
├── style.css               ← テーマ情報（WordPress認識用）
├── functions.php           ← CSS/JS読み込み・カスタム機能
├── header.php              ← ヘッダー・ナビゲーション
├── footer.php              ← フッター
├── front-page.php          ← トップページ
├── single.php              ← 記事詳細ページ
├── archive.php             ← 記事一覧ページ
├── index.php               ← フォールバックテンプレート
└── assets/
    ├── css/
    │   ├── style.css       ← 静的サイトの css/style.css をコピー
    │   └── article.css     ← 静的サイトの css/article.css をコピー
    └── js/
        ├── main.js         ← 静的サイトの js/main.js をコピー
        └── article.js      ← 静的サイトの js/article.js をコピー
```

---

## 導入手順

### ステップ 1：WordPress をインストール

レンタルサーバー（エックスサーバー・ConoHa WING等）の  
「WordPress かんたんインストール」機能を使うのが最も簡単です。

---

### ステップ 2：テーマフォルダを作成してアップロード

#### 方法A：FTP ソフトを使う（推奨）
FTPソフト（例：FileZilla）でサーバーに接続し、以下のパスへアップロード：

```
wp-content/themes/aofinance/
```

#### 方法B：管理画面からZIPアップロード
1. `wordpress-theme/` フォルダを ZIP 圧縮して `aofinance.zip` に名前変更
2. WordPress管理画面 →「外観」→「テーマ」→「新規追加」
3. 「テーマのアップロード」→ ZIP ファイルを選択 → 「今すぐインストール」

---

### ステップ 3：CSS / JS ファイルをコピー

静的サイトの CSS・JS ファイルをテーマフォルダの `assets/` にコピーします。

| コピー元（静的サイト） | コピー先（WordPressテーマ） |
|---|---|
| `css/style.css` | `assets/css/style.css` |
| `css/article.css` | `assets/css/article.css` |
| `js/main.js` | `assets/js/main.js` |
| `js/article.js` | `assets/js/article.js` |

---

### ステップ 4：テーマを有効化

WordPress管理画面 →「外観」→「テーマ」→ **aofinance** →「有効化」

---

### ステップ 5：カテゴリを作成

管理画面 →「投稿」→「カテゴリー」→「新規カテゴリーを追加」

| カテゴリ名 | スラッグ |
|---|---|
| 株式 | `stocks` |
| 債券 | `bonds` |
| 投資信託 | `funds` |
| コモディティ | `commodity` |
| 税金 | `tax` |
| 金融知識 | `knowledge` |
| 投資のヒント | `tips` |

> ⚠️ **スラッグは必ず半角英字で設定してください。** アイコン・カラーの自動割り当てに使用されます。

---

### ステップ 6：パーマリンク設定

管理画面 →「設定」→「パーマリンク」→「投稿名」を選択 → 保存

```
例：https://yourdomain.com/sample-post/
```

---

### ステップ 7：固定ページを作成

| ページ名 | スラッグ | 用途 |
|---|---|---|
| 記事一覧 | `articles` | 記事一覧ページ（archive.phpが自動適用） |
| 金融クイズ | `quiz` | クイズページ（今後実装） |

---

### ステップ 8：記事を投稿する

管理画面 →「投稿」→「新規追加」

| 項目 | 設定方法 |
|---|---|
| タイトル | 記事タイトルを入力 |
| 本文 | ブロックエディタで入力（グラフはHTMLブロックで追加） |
| カテゴリ | 右サイドバーで選択 |
| タグ | 右サイドバーで追加 |
| 読了時間 | 右サイドバー「記事メタ情報」に入力（例：約5分） |
| 抜粋 | 右サイドバー「抜粋」に記事カード用の説明文を入力 |

---

### ステップ 9：グラフを記事に追加する

Chart.js グラフは **カスタムHTMLブロック** で追加します。

1. ブロックエディタで「+」→「カスタムHTML」を選択
2. 以下のように貼り付け：

```html
<div class="chart-card">
  <div class="chart-card-header">
    <h3 class="chart-title">
      <i class="fa-solid fa-chart-pie"></i> グラフタイトル
    </h3>
    <p class="chart-subtitle">サブタイトル</p>
  </div>
  <div class="chart-wrap" style="height:300px;">
    <canvas id="myChart"></canvas>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  new Chart(document.getElementById('myChart'), {
    type: 'doughnut',
    data: {
      labels: ['投資', '宝飾品', '中央銀行', '産業'],
      datasets: [{
        data: [43.5, 32.7, 17.3, 6.5],
        backgroundColor: ['#f59e0b', '#ec4899', '#3b82f6', '#10b981'],
        borderWidth: 3,
        borderColor: '#fff',
      }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });
});
</script>
```

---

## よくある質問

### Q. トップページが記事一覧になってしまう
**A.** 管理画面 →「設定」→「表示設定」→「ホームページの表示」を  
「固定ページ」に変更 → ホームページに「フロントページ」用の固定ページを設定

### Q. カテゴリのアイコン・色が反映されない
**A.** カテゴリのスラッグが正しく設定されているか確認してください（ステップ5参照）

### Q. CSSが反映されない
**A.** `assets/css/` フォルダにファイルが正しくコピーされているか確認してください

### Q. ハンバーガーメニューが動かない
**A.** `assets/js/main.js` が読み込まれているか確認。  
管理画面 →「外観」→「テーマエディター」で `functions.php` のパスを確認してください

---

## 推奨プラグイン

| プラグイン | 用途 | 優先度 |
|---|---|---|
| **Yoast SEO** | SEO・OGP設定 | 高 |
| **WP Super Cache** | 表示速度改善 | 高 |
| **Wordfence Security** | セキュリティ対策 | 高 |
| **Contact Form 7** | お問い合わせフォーム | 中 |
| **UpdraftPlus** | バックアップ | 中 |

---

## 今後の実装予定

- [ ] 金融クイズページ（`page-quiz.php`）
- [ ] 検索結果ページ（`search.php`）
- [ ] プライバシーポリシーページ
- [ ] OGP画像のデフォルト設定（`assets/images/og-default.png`）
