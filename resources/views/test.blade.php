
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<form enctype="multipart/form-data" class="form-horizontal" action="http://articles/api/profile/edit" method="post">
    @csrf
    @method("PUT")
    <input class="form-control" type="hidden" name="Authorization" value="Bearer 1|JwhQzbrvJJzDUQ3k5kOU2zgfhEfGHRdFAoWUtTXM" />
    <div class="form-group">
        <input class="form-control" type="file" name="path_photo" accept="image/*" />
    </div>
    <div class="form-group">
        <input class="btn btn-success" type="submit"/>
    </div>
</form>
</body>
</html>
