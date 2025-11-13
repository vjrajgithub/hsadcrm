<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">System Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- Settings Categories Tabs -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="settings-tabs" role="tablist">
                            <?php
                            $first = true;
                            foreach ($categories as $category):
                              ?>
                              <li class="nav-item">
                                  <a class="nav-link <?= $first ? 'active' : '' ?>"
                                     id="<?= strtolower(str_replace(' ', '-', $category)) ?>-tab"
                                     data-toggle="pill"
                                     href="#<?= strtolower(str_replace(' ', '-', $category)) ?>"
                                     role="tab">
                                      <i class="fas fa-<?= get_category_icon($category) ?>"></i>
                                      <?= ucfirst($category) ?>
                                  </a>
                              </li>
                              <?php
                              $first = false;
                            endforeach;
                            ?>
                            <!--                            <li class="nav-item">
                                                            <a class="nav-link" id="manage-tab" data-toggle="pill" href="#manage" role="tab">
                                                                <i class="fas fa-cogs"></i>
                                                                Manage
                                                            </a>
                                                        </li>-->
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="settings-tabContent">

                            <!-- Settings Categories Content -->
                            <?php
                            $first = true;
                            foreach ($categories as $category):
                              $category_settings = array_filter($settings, function ($s) use ($category) {
                                return $s->category === $category;
                              });
                              ?>
                              <div class="tab-pane fade <?= $first ? 'show active' : '' ?>"
                                   id="<?= strtolower(str_replace(' ', '-', $category)) ?>"
                                   role="tabpanel">

                                  <div class="row">
                                      <div class="col-md-8">
                                          <h4><?= ucfirst($category) ?> Settings</h4>
                                          <p class="text-muted">Configure <?= strtolower($category) ?> related settings for your CRM system.</p>

                                          <form id="<?= strtolower(str_replace(' ', '-', $category)) ?>-form">
                                              <?php foreach ($category_settings as $setting): ?>
                                                <div class="form-group">
                                                    <label for="<?= $setting->setting_key ?>"><?= ucwords(str_replace('_', ' ', $setting->setting_key)) ?></label>

                                                    <?php if ($setting->input_type === 'textarea'): ?>
                                                      <textarea name="<?= $setting->setting_key ?>"
                                                                id="<?= $setting->setting_key ?>"
                                                                class="form-control setting-input"
                                                                data-id="<?= $setting->id ?>"
                                                                rows="3"><?= htmlspecialchars($setting->setting_value) ?></textarea>

                                                    <?php elseif ($setting->input_type === 'select'): ?>
                                                      <select name="<?= $setting->setting_key ?>"
                                                              id="<?= $setting->setting_key ?>"
                                                              class="form-control setting-input"
                                                              data-id="<?= $setting->id ?>">
                                                                  <?php
                                                                  if ($setting->options) {
                                                                    $options = explode(',', $setting->options);
                                                                    foreach ($options as $option) {
                                                                      list($value, $label) = explode(':', $option);
                                                                      $selected = ($value === $setting->setting_value) ? 'selected' : '';
                                                                      echo "<option value='$value' $selected>$label</option>";
                                                                    }
                                                                  }
                                                                  ?>
                                                      </select>

                                                    <?php elseif ($setting->input_type === 'checkbox'): ?>
                                                      <div class="custom-control custom-switch">
                                                          <input type="checkbox"
                                                                 class="custom-control-input setting-input"
                                                                 id="<?= $setting->setting_key ?>"
                                                                 name="<?= $setting->setting_key ?>"
                                                                 data-id="<?= $setting->id ?>"
                                                                 value="1"
                                                                 <?= $setting->setting_value == '1' ? 'checked' : '' ?>>
                                                          <label class="custom-control-label" for="<?= $setting->setting_key ?>"></label>
                                                      </div>

                                                    <?php else: ?>
                                                      <input type="<?= $setting->input_type ?>"
                                                             name="<?= $setting->setting_key ?>"
                                                             id="<?= $setting->setting_key ?>"
                                                             class="form-control setting-input"
                                                             data-id="<?= $setting->id ?>"
                                                             value="<?= htmlspecialchars($setting->setting_value) ?>">
                                                           <?php endif; ?>

                                                    <?php if ($setting->description): ?>
                                                      <small class="form-text text-muted"><?= $setting->description ?></small>
                                                    <?php endif; ?>
                                                </div>
                                              <?php endforeach; ?>

                                              <button type="button" class="btn btn-primary save-category-settings" data-category="<?= $category ?>">
                                                  <i class="fas fa-save"></i> Save <?= ucfirst($category) ?> Settings
                                              </button>
                                          </form>
                                      </div>

                                      <div class="col-md-4">
                                          <div class="info-box bg-info">
                                              <span class="info-box-icon"><i class="fas fa-<?= get_category_icon($category) ?>"></i></span>
                                              <div class="info-box-content">
                                                  <span class="info-box-text"><?= ucfirst($category) ?> Settings</span>
                                                  <span class="info-box-number"><?= count($category_settings) ?> Settings</span>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <?php
                              $first = false;
                            endforeach;
                            ?>

                            <!-- Manage Settings Tab -->
                            <div class="tab-pane fade" id="manage" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4>Manage All Settings</h4>
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addSettingModal">
                                                <i class="fas fa-plus"></i> Add New Setting
                                            </button>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <table id="settingsTable" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Category</th>
                                                            <th>Key</th>
                                                            <th>Value</th>
                                                            <th>Description</th>
                                                            <th>Type</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($settings as $setting): ?>
                                                          <tr>
                                                              <td><span class="badge badge-primary"><?= $setting->category ?></span></td>
                                                              <td><code><?= $setting->setting_key ?></code></td>
                                                              <td>
                                                                  <?php if (strlen($setting->setting_value) > 50): ?>
                                                                    <?= substr(htmlspecialchars($setting->setting_value), 0, 50) ?>...
                                                                  <?php else: ?>
                                                                    <?= htmlspecialchars($setting->setting_value) ?>
                                                                  <?php endif; ?>
                                                              </td>
                                                              <td><?= $setting->description ?></td>
                                                              <td><span class="badge badge-secondary"><?= $setting->input_type ?></span></td>
                                                              <td>
                                                                  <button type="button" class="btn btn-sm btn-info edit-setting" data-id="<?= $setting->id ?>">
                                                                      <i class="fas fa-edit"></i>
                                                                  </button>
                                                                  <button type="button" class="btn btn-sm btn-danger delete-setting" data-id="<?= $setting->id ?>">
                                                                      <i class="fas fa-trash"></i>
                                                                  </button>
                                                              </td>
                                                          </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Setting Modal -->
<div class="modal fade" id="addSettingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Setting</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="addSettingForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_category">Category</label>
                                <input type="text" name="category" id="add_category" class="form-control" list="categories" required>
                                <datalist id="categories">
                                    <?php foreach ($categories as $cat): ?>
                                      <option value="<?= $cat ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_setting_key">Setting Key</label>
                                <input type="text" name="setting_key" id="add_setting_key" class="form-control" required>
                                <small class="text-muted">Use lowercase with underscores (e.g., smtp_host)</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="add_setting_value">Setting Value</label>
                        <input type="text" name="setting_value" id="add_setting_value" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="add_description">Description</label>
                        <textarea name="description" id="add_description" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_input_type">Input Type</label>
                                <select name="input_type" id="add_input_type" class="form-control" required>
                                    <option value="text">Text</option>
                                    <option value="number">Number</option>
                                    <option value="email">Email</option>
                                    <option value="password">Password</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="select">Select</option>
                                    <option value="checkbox">Checkbox</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_sort_order">Sort Order</label>
                                <input type="number" name="sort_order" id="add_sort_order" class="form-control" value="999">
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="add_options_group" style="display: none;">
                        <label for="add_options">Options (for select/checkbox)</label>
                        <textarea name="options" id="add_options" class="form-control" rows="2" placeholder="value1:Label 1,value2:Label 2"></textarea>
                        <small class="text-muted">Format: value1:Label 1,value2:Label 2</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Setting</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Setting Modal -->
<div class="modal fade" id="editSettingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Setting</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editSettingForm">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <!-- Same form fields as add modal -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_category">Category</label>
                                <input type="text" name="category" id="edit_category" class="form-control" list="categories" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_setting_key">Setting Key</label>
                                <input type="text" name="setting_key" id="edit_setting_key" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_setting_value">Setting Value</label>
                        <input type="text" name="setting_value" id="edit_setting_value" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_input_type">Input Type</label>
                                <select name="input_type" id="edit_input_type" class="form-control" required>
                                    <option value="text">Text</option>
                                    <option value="number">Number</option>
                                    <option value="email">Email</option>
                                    <option value="password">Password</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="select">Select</option>
                                    <option value="checkbox">Checkbox</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_sort_order">Sort Order</label>
                                <input type="number" name="sort_order" id="edit_sort_order" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="edit_options_group">
                        <label for="edit_options">Options (for select/checkbox)</label>
                        <textarea name="options" id="edit_options" class="form-control" rows="2" placeholder="value1:Label 1,value2:Label 2"></textarea>
                        <small class="text-muted">Format: value1:Label 1,value2:Label 2</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Setting</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
  $(document).ready(function () {
      // CSRF setup: read token from cookie on each POST
      const csrfName = '<?= $this->security->get_csrf_token_name() ?>';
      const csrfCookieName = '<?= $this->config->item('csrf_cookie_name') ?>';
      function getCookie(name) {
          const value = `; ${document.cookie}`;
          const parts = value.split(`; ${name}=`);
          if (parts.length === 2)
              return parts.pop().split(';').shift();
          return '';
      }
      function withCsrf(data) {
          data = data || {};
          data[csrfName] = getCookie(csrfCookieName);
          return data;
      }
      // Initialize DataTable
      $('#settingsTable').DataTable({
          responsive: true,
          order: [[0, 'asc'], [1, 'asc']]
      });

      // Show/hide options field based on input type
      $('#add_input_type, #edit_input_type').change(function () {
          const type = $(this).val();
          const optionsGroup = $(this).closest('.modal').find('[id$="_options_group"]');

          if (type === 'select' || type === 'checkbox') {
              optionsGroup.show();
          } else {
              optionsGroup.hide();
          }
      });

      // Save category settings
      $('.save-category-settings').click(function () {
          const category = $(this).data('category');
          const form = $('#' + category.toLowerCase().replace(' ', '-') + '-form');
          const formData = {};

          form.find('.setting-input').each(function () {
              const input = $(this);
              const id = input.data('id');
              let value = input.val();

              if (input.is(':checkbox')) {
                  value = input.is(':checked') ? '1' : '0';
              }

              formData[id] = value;
          });

          // Update each setting
          let promises = [];
          Object.keys(formData).forEach(function (id) {
              promises.push(
                      $.post('settings/quick_update', withCsrf({
                          id: id,
                          value: formData[id]
                      }))
                      );
          });

          Promise.all(promises).then(function () {
              Swal.fire('Success!', category + ' settings updated successfully', 'success');
          }).catch(function () {
              Swal.fire('Error!', 'Failed to update some settings', 'error');
          });
      });

      // Add new setting
      $('#addSettingForm').submit(function (e) {
          e.preventDefault();

          $.post('settings/add', withCsrf($(this).serializeArray().reduce(function (obj, item) {
              obj[item.name] = item.value;
              return obj;
          }, {})))
                  .done(function (response) {
                      const result = JSON.parse(response);
                      if (result.success) {
                          $('#addSettingModal').modal('hide');
                          Swal.fire('Success!', result.message, 'success').then(function () {
                              location.reload();
                          });
                      } else {
                          Swal.fire('Error!', result.message, 'error');
                      }
                  });
      });

      // Edit setting
      $('.edit-setting').click(function () {
          const id = $(this).data('id');

          $.get('settings/get/' + id)
                  .done(function (setting) {
                      $('#edit_id').val(setting.id);
                      $('#edit_category').val(setting.category);
                      $('#edit_setting_key').val(setting.setting_key);
                      $('#edit_setting_value').val(setting.setting_value);
                      $('#edit_description').val(setting.description);
                      $('#edit_input_type').val(setting.input_type).trigger('change');
                      $('#edit_options').val(setting.options);
                      $('#edit_sort_order').val(setting.sort_order);

                      $('#editSettingModal').modal('show');
                  });
      });

      // Update setting
      $('#editSettingForm').submit(function (e) {
          e.preventDefault();
          const id = $('#edit_id').val();

          $.post('settings/edit/' + id, withCsrf($(this).serializeArray().reduce(function (obj, item) {
              obj[item.name] = item.value;
              return obj;
          }, {})))
                  .done(function (response) {
                      const result = JSON.parse(response);
                      if (result.success) {
                          $('#editSettingModal').modal('hide');
                          Swal.fire('Success!', result.message, 'success').then(function () {
                              location.reload();
                          });
                      } else {
                          Swal.fire('Error!', result.message, 'error');
                      }
                  });
      });

      // Delete setting
      $('.delete-setting').click(function () {
          const id = $(this).data('id');

          Swal.fire({
              title: 'Are you sure?',
              text: 'This setting will be permanently deleted!',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
              if (result.isConfirmed) {
                  $.post('settings/delete/' + id, withCsrf({}))
                          .done(function (response) {
                              const result = JSON.parse(response);
                              if (result.success) {
                                  Swal.fire('Deleted!', result.message, 'success').then(function () {
                                      location.reload();
                                  });
                              } else {
                                  Swal.fire('Error!', result.message, 'error');
                              }
                          });
              }
          });
      });
  });

</script>

<?php

// Helper function to get category icons
function get_category_icon($category) {
  $icons = [
      'Application' => 'cog',
      'Email' => 'envelope',
      'Security' => 'shield-alt',
      'Business' => 'building'
  ];
  return $icons[$category] ?? 'cog';
}
?>
