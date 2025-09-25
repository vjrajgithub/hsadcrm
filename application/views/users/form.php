<div class="modal-body">
    <input type="hidden" name="id" value="<?= @$user->id ?>">

    <div class="form-group">
        <label>Name *</label>
        <input type="text" name="name" class="form-control" value="<?= @$user->name ?>" required>
    </div>

    <div class="form-group">
        <label>Email *</label>
        <input type="email" name="email" class="form-control" value="<?= @$user->email ?>" required>
    </div>

    <div class="form-group">
        <label>Role *</label>
        <select name="role" class="form-control" required>
            <option value="">-- Select Role --</option>
            <option value="super admin" <?= @$user->role == 'super admin' ? 'selected' : '' ?>>Super Admin</option>
            <option value="admin" <?= @$user->role == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="viewer" <?= @$user->role == 'viewer' ? 'selected' : '' ?>>Viewer</option>
        </select>
    </div>

    <div class="form-group" id="passwordGroup" style="display: none;">
        <label>Password *</label>
        <input type="password" name="password" class="form-control">
    </div>
</div>
