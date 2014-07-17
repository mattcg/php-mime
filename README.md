# MIME #

MIME type and file extension utilities for PHP. Powered by [`finfo`](http://php.net/manual/en/book.fileinfo.php) and the Apache-provided public domain [mime.types](https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types) map of media types to unique file extension(s).

## Examples ##

Get the MIME type of an uploaded file. The original file name is used before falling back to running `finfo` if the file has no extension or if the extension is unlisted.

```php
use Karwana\Mime;

$mime_type = Mime::guessType($_FILES['my_file']['tmp_name'], $_FILES['my_file']['name']);

// Now get the canonical extension.
$extension = Mime::getExtensionForType(mime_type);
```

Add an extension to an extensionless file.

```php
file_path = 'path/to/extensionless_file';

rename($file_path, $file_path . '.' . Mime::guessExtension($file_path));
```

## Development ##

Run tests using `$ vendor/bin/phing test`.

Use the provided script to update the MIME type list to the latest version from Apache's tracker.

```bash
curl https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types | \
bin/mime_types2json > Mime/Resources/mime_types.json
```

