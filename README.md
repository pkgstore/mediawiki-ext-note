# Information

Интеграция пользовательских сообщений в статью.

## Install

1. Загрузите папки и файлы в директорию `extensions/MW_EXT_Note`.
2. В самый низ файла `LocalSettings.php` добавьте строку:

```php
wfLoadExtension( 'MW_EXT_Note' );
```

## Syntax

```html
<note type="[TYPE]">[CONTENT]</note>
```
