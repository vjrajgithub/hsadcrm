<h4>Role Permissions</h4>

<form method="post">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Module</th>
                <?php foreach ($roles as $role): ?>
                  <th><?= ucfirst($role) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modules as $module): ?>
              <tr>
                  <td><?= ucfirst($module) ?></td>
                  <?php foreach ($roles as $role): ?>
                    <td>
                        <input type="checkbox"
                               name="permissions[<?= $role ?>][<?= $module ?>]"
                               <?= isset($permissions[$role][$module]) ? 'checked' : '' ?>>
                    </td>
                  <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button class="btn btn-primary">💾 Save Permissions</button>
</form>
