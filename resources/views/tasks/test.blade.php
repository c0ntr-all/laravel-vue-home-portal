<form action="{{ route('store') }}">
    <div class="form-group">
        <label for="list_id">list_id</label>
        <input type="text" name="list_id">
    </div>
    <div class="form-group">
        <label for="name">title</label>
        <input type="text" name="title">
    </div>
    <button type="submit">Submit</button>
</form>
