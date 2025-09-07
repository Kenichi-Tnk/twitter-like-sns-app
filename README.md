# twitter-sns-app

> ⚠️ **このプロジェクトは Nuxt2 を使用しています。**
> Nuxt3 ではなく Nuxt2 のインストールを行ってください。

## Nuxt2 のインストール方法

```bash
# Nuxt2 を明示的にインストールする場合
$ yarn add nuxt@2
# または npm を使う場合
$ npm install nuxt@2
```

## セットアップ手順

```bash
# 依存パッケージのインストール
$ yarn install

# 開発サーバーを起動（ホットリロード対応、localhost:3000）
$ yarn dev

# 本番用ビルド & サーバー起動
$ yarn build
$ yarn start

# 静的サイトの生成
$ yarn generate
```

詳細な使い方や設定については [Nuxt.js 公式ドキュメント](https://nuxtjs.org) をご参照ください。

## 主なディレクトリ構成

以下のディレクトリは用途ごとに分かれています。`pages` ディレクトリは必須ですが、他は必要に応じて追加・削除できます。

### `assets`

未加工の CSS（Stylus/Sass 等）、画像、フォントなどを格納します。
詳しくは [公式ドキュメント](https://nuxtjs.org/docs/2.x/directory-structure/assets) を参照。

### `components`

Vue.js コンポーネントを格納します。ページやレイアウト、他のコンポーネントから再利用できます。
詳しくは [公式ドキュメント](https://nuxtjs.org/docs/2.x/directory-structure/components) を参照。

### `layouts`

アプリ全体のレイアウト（サイドバーやモバイル/デスクトップ用など）を定義します。
詳しくは [公式ドキュメント](https://nuxtjs.org/docs/2.x/directory-structure/layouts) を参照。

### `pages`

アプリの画面・ルーティングを定義します。`*.vue` ファイルを配置すると自動で Vue Router に登録されます。
詳しくは [公式ドキュメント](https://nuxtjs.org/docs/2.x/get-started/routing) を参照。

### `plugins`

Vue.js のプラグインや、アプリ起動前に実行したい JS ファイルを格納します。`Vue.use()` したい場合はここにファイルを作成し、`nuxt.config.js` の plugins にパスを追加します。
詳しくは [公式ドキュメント](https://nuxtjs.org/docs/2.x/directory-structure/plugins) を参照。

### `static`

静的ファイル（画像や robots.txt など）を格納します。各ファイルは `/` パスにマッピングされます。
例: `/static/robots.txt` → `/robots.txt` でアクセス可能。
詳しくは [公式ドキュメント](https://nuxtjs.org/docs/2.x/directory-structure/static) を参照。

### `store`

Vuex ストア関連ファイルを格納します。ファイルを作成すると自動的に Vuex が有効化されます。
詳しくは [公式ドキュメント](https://nuxtjs.org/docs/2.x/directory-structure/store) を参照。
