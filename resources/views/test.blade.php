<form enctype="multipart/form-data" class="form-horizontal" action="http://articles/api/Test1" method="post">
    @csrf
    <div class="form-group">
        <input class="form-control" type="file" name="xxx" accept="image/*" />
    </div>
    <div class="form-group">
        <input class="btn btn-success" type="submit"/>
    </div>
</form>
