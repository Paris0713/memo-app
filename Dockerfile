# ベースイメージとしてPHP 8.3.1を使用
FROM php:8.3.1-apache

# mod_rewriteを有効にする
RUN a2enmod rewrite

# 必要なPHP拡張機能をインストール
RUN docker-php-ext-install mysqli pdo pdo_mysql

# エラーログファイルを作成
RUN touch /var/log/php_errors.log

# ログディレクトリを作成し、権限を設定
RUN mkdir -p /var/www/html/logs && chown www-data:www-data /var/www/html/logs

# PHP設定用のカスタムphp.iniを追加
COPY php.ini /usr/local/etc/php/

# 作業ディレクトリを設定
WORKDIR /var/www/html

# ホストのmemo-appフォルダの内容をコンテナにコピー
COPY . /var/www/html

# ポート80を公開
EXPOSE 80

# Apacheを起動
CMD ["apache2-foreground"]