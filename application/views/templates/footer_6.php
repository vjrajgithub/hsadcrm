<footer class="main-footer text-center">
    <strong>&copy; <?= date('Y') ?> Invoice Management System</strong>
</footer>
</div> <!-- wrapper -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
  const products = <?= json_encode($products) ?>;

  function addItem() {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>
            <select class="form-control" name="items[][product_id]" onchange="fillRate(this)">
                <option value="">Select</option>
                ${products.map(p => `<option value="${p.id}" data-rate="${p.rate}" data-gst="${p.tax_percent}">${p.name}</option>`).join('')}
            </select>
        </td>
        <td><input type="number" step="0.01" name="items[][rate]" class="form-control rate" onchange="calcTotal()"></td>
        <td><input type="number" name="items[][qty]" class="form-control qty" value="1" onchange="calcTotal()"></td>
        <td><input type="number" name="items[][gst]" class="form-control gst" readonly></td>
        <td><input type="text" class="form-control item_total" readonly></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove(); calcTotal();"><i class="fas fa-trash"></i></button></td>
    `;
      document.querySelector("#itemTable tbody").appendChild(row);
  }

  function fillRate(select) {
      const opt = select.options[select.selectedIndex];
      const tr = select.closest('tr');
      tr.querySelector('.rate').value = opt.getAttribute('data-rate');
      tr.querySelector('.gst').value = opt.getAttribute('data-gst');
      calcTotal();
  }

  function calcTotal() {
      let subtotal = 0, total_gst = 0, grand_total = 0;
      document.querySelectorAll('#itemTable tbody tr').forEach(row => {
          const rate = parseFloat(row.querySelector('.rate').value || 0);
          const qty = parseFloat(row.querySelector('.qty').value || 0);
          const gst = parseFloat(row.querySelector('.gst').value || 0);

          const total = rate * qty;
          const gst_amt = total * (gst / 100);
          const final = total + gst_amt;

          row.querySelector('.item_total').value = final.toFixed(2);

          subtotal += total;
          total_gst += gst_amt;
          grand_total += final;
      });

      document.getElementById('subtotal').value = subtotal.toFixed(2);
      document.getElementById('total_gst').value = total_gst.toFixed(2);
      document.getElementById('grand_total').value = grand_total.toFixed(2);
  }
</script>

