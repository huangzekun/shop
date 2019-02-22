<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>微信永久素材上传</title>
</head>
<body>
<form action="/form/test" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <h2>微信素材上传</h2>
    <input type="file" name="media">
    <input type="submit" value="上传">
</form>
</body>
</html>