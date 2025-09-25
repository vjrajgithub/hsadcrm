<h4>Add User</h4>

<form method="post">
    <div class="form-group">
        <label>Username</label>
        <input name="username" class="form-control" required>
    </div>

    <div class="form-group mt-2">
        <label>Password</label>
        <input name="password" type="password" class="form-control" required>
    </div>

    <div class="form-group mt-2">
        <label>Role</label>
        <select name="role" class="form-control">
            <option value="admin">Admin</option>
            <option value="viewer">Viewer</option>
        </select>
    </div>

    <div class="form-group mt-2">
        <label>Status</label>
        <select name="active" class="form-control">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <button class="btn btn-primary mt-3">Save</button>
</form>
