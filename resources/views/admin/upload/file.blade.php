<form action="{{ url('admin/upload/file')  }}" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    {{ csrf_field() }}
    <button type="submit">上传</button>
</form>