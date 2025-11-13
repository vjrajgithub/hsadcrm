# 🧪 Testing Snapshot Functionality - Complete Guide

## Current Issue You're Facing

**Problem:** When you delete a product/service or category, the PDF doesn't show that data anymore.

**Root Cause:** The system needs to:
1. ✅ Have snapshot columns in database (migration)
2. ✅ Capture snapshots when creating quotations (model)
3. ✅ Use snapshots when displaying/generating PDFs (fixed now!)

---

## ✅ What I Just Fixed

### Fixed Issues:
1. **Wrong table name in JOINs** - Was using `products` instead of `products_services`
2. **Snapshot merge not working** - Objects weren't being modified by reference
3. **Multiple methods not using snapshots** - Fixed `get_items()`, `getQuotationItems()`, `get_by_id()`

### Files Modified:
- `application/models/Quotation_model.php` - Fixed all product/category JOINs and snapshot merging

---

## 🚀 Step-by-Step Testing

### Step 1: Run the Migration (If Not Done)

```
http://localhost/crm/run_quotation_snapshot_migration.php
```

This adds snapshot columns to your database.

### Step 2: Test Current Setup

```
http://localhost/crm/test_snapshot_functionality.php
```

This will show:
- ✅ Which columns exist
- ✅ If snapshots are being captured
- ✅ If snapshot data is being used

### Step 3: Create a NEW Quotation

**Important:** Only NEW quotations (created after migration) will have snapshots!

1. Go to: `http://localhost/crm/quotation/add`
2. Fill in all details:
   - Select Company
   - Select Client
   - Select Bank
   - Add items with products/categories
3. Save the quotation
4. **Note the Quotation ID**

### Step 4: Verify Snapshots Were Captured

Run this SQL in phpMyAdmin:

```sql
-- Replace 999 with your quotation ID
SELECT 
    id,
    company_id,
    CASE WHEN company_snapshot IS NOT NULL THEN '✅ YES' ELSE '❌ NO' END as has_company_snapshot,
    CASE WHEN client_snapshot IS NOT NULL THEN '✅ YES' ELSE '❌ NO' END as has_client_snapshot,
    CASE WHEN bank_snapshot IS NOT NULL THEN '✅ YES' ELSE '❌ NO' END as has_bank_snapshot
FROM quotations 
WHERE id = 999;

-- Check items
SELECT 
    id,
    quotation_id,
    product_id,
    category_id,
    CASE WHEN product_snapshot IS NOT NULL THEN '✅ YES' ELSE '❌ NO' END as has_product_snapshot,
    CASE WHEN category_snapshot IS NOT NULL THEN '✅ YES' ELSE '❌ NO' END as has_category_snapshot
FROM quotation_items 
WHERE quotation_id = 999;
```

**Expected Result:** All should show "✅ YES"

### Step 5: Test PDF BEFORE Deletion

1. Go to quotation list
2. Click "View PDF" or "Download PDF"
3. **Verify:** All data shows correctly
4. **Note:** Product names, category names, company, client, bank all visible

### Step 6: Delete the Product/Category

1. Go to Product/Service management
2. Delete one of the products used in your quotation
3. Or delete a category used in your quotation

### Step 7: Test PDF AFTER Deletion

1. Go back to quotation list
2. Click "View PDF" or "Download PDF" for the SAME quotation
3. **Expected Result:** 
   - ✅ Product name still shows (from snapshot)
   - ✅ Category name still shows (from snapshot)
   - ✅ All other data intact
   - ✅ PDF generates successfully

---

## 🔍 Troubleshooting

### Issue 1: PDF Still Shows Empty After Deletion

**Possible Causes:**
1. Migration not run (snapshot columns don't exist)
2. Testing with OLD quotation (created before migration)
3. Snapshots not captured (model issue)

**Solution:**
```
1. Run migration: http://localhost/crm/run_quotation_snapshot_migration.php
2. Create a BRAND NEW quotation
3. Test with the new quotation
```

### Issue 2: Snapshot Columns Don't Exist

**Check:**
```sql
SHOW COLUMNS FROM quotations LIKE '%snapshot%';
SHOW COLUMNS FROM quotation_items LIKE '%snapshot%';
```

**Should Return:** 4 columns each

**If Not:**
```
Run: http://localhost/crm/run_quotation_snapshot_migration.php
```

### Issue 3: New Quotations Don't Capture Snapshots

**Check Model:**
```php
// In Quotation_model.php, insert() method should have:
if (isset($data['company_id'])) {
    $data['company_snapshot'] = $this->capture_company_snapshot($data['company_id']);
}
// ... similar for client, bank, mode
```

**If Missing:** The model file wasn't updated correctly. Re-download or manually add.

### Issue 4: PDF Shows Data But Not From Snapshot

**This is actually OK!** It means:
- Master data still exists (not deleted)
- System uses live data from database
- Snapshot is backup for when you delete

**To Test Snapshot:**
- Delete the product/category
- Then check PDF again

---

## 📊 Understanding the Flow

### When Creating Quotation:
```
User saves quotation
    ↓
Model captures snapshots:
├─ Company data → JSON
├─ Client data → JSON
├─ Bank data → JSON
├─ Mode data → JSON
└─ For each item:
    ├─ Category data → JSON
    └─ Product data → JSON
    ↓
Saves to database
```

### When Viewing/Generating PDF:
```
User requests PDF
    ↓
Model fetches quotation:
├─ Try JOIN with companies table
│   └─ If NULL (deleted) → Use company_snapshot
├─ Try JOIN with clients table
│   └─ If NULL (deleted) → Use client_snapshot
├─ Try JOIN with banks table
│   └─ If NULL (deleted) → Use bank_snapshot
└─ For each item:
    ├─ Try JOIN with products_services
    │   └─ If NULL (deleted) → Use product_snapshot
    └─ Try JOIN with categories
        └─ If NULL (deleted) → Use category_snapshot
    ↓
Generate PDF with complete data
```

---

## ✅ Verification Checklist

- [ ] Migration completed successfully
- [ ] Snapshot columns exist in database
- [ ] Created a NEW quotation (after migration)
- [ ] Verified snapshots captured (SQL check)
- [ ] PDF works BEFORE deletion
- [ ] Deleted product/category
- [ ] PDF still works AFTER deletion
- [ ] Product/category name still shows in PDF
- [ ] ✅ Everything working!

---

## 🎯 Quick Test Commands

### Check if migration is complete:
```sql
SELECT COUNT(*) as snapshot_columns 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'crm_db' 
  AND TABLE_NAME = 'quotations' 
  AND COLUMN_NAME LIKE '%snapshot%';
-- Should return 4
```

### Check if quotation has snapshots:
```sql
SELECT 
    id,
    company_snapshot IS NOT NULL as has_company,
    client_snapshot IS NOT NULL as has_client,
    bank_snapshot IS NOT NULL as has_bank
FROM quotations 
ORDER BY id DESC 
LIMIT 5;
```

### View snapshot data:
```sql
SELECT 
    id,
    JSON_EXTRACT(company_snapshot, '$.name') as company_name,
    JSON_EXTRACT(client_snapshot, '$.name') as client_name
FROM quotations 
WHERE id = YOUR_QUOTATION_ID;
```

---

## 📞 Still Not Working?

### Debug Steps:

1. **Check Model File:**
   - Open: `application/models/Quotation_model.php`
   - Search for: `products_services` (should find 3 occurrences)
   - Search for: `merge_item_snapshot_data` (should be pass by reference: `&$item`)

2. **Check Database:**
   ```sql
   -- See actual snapshot data
   SELECT id, company_snapshot, client_snapshot 
   FROM quotations 
   WHERE id = YOUR_ID;
   ```

3. **Check Controller:**
   - Open: `application/controllers/Quotation.php`
   - Find `view_pdf()` and `generate_pdf()` methods
   - Should use: `getQuotationWithDetails()` and `getQuotationItems()`

4. **Test with Browser Tool:**
   ```
   http://localhost/crm/test_snapshot_functionality.php
   ```

---

## 💡 Important Notes

1. **Old quotations won't have snapshots** - Only new ones (created after migration)
2. **Snapshots are automatic** - No manual action needed after setup
3. **Live data is preferred** - Snapshots only used when master data deleted
4. **Safe to delete** - Once snapshots captured, safe to delete master data
5. **PDF always works** - Even if all master data deleted

---

## ✨ Success Criteria

Your system is working correctly when:

✅ New quotations capture snapshots automatically  
✅ PDFs generate with complete data  
✅ Deleting products/categories doesn't break PDFs  
✅ Historical quotations remain intact  
✅ No errors in PDF generation  

**Status:** All fixes applied. Test with a NEW quotation!
