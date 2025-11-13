# 🚀 Quotation Snapshot Migration - Step by Step Instructions

## ⚠️ If You Got "Duplicate Column" Error

This means some columns already exist. Follow these instructions:

---

## ✅ EASIEST METHOD: Use the Browser Tool

### Step 1: Open the Migration Tool
```
http://localhost/crm/run_quotation_snapshot_migration.php
```

### Step 2: Click "Go"
- The tool now checks each column before adding
- It will skip columns that already exist
- Shows clear success/skip messages
- No errors!

### Step 3: Done!
- Verify the statistics shown
- Delete the migration file for security

---

## 📋 ALTERNATIVE: Check What You Have First

### Step 1: Check Current Structure
```
http://localhost/crm/check_current_structure.php
```

This shows:
- ✅ Which columns already exist (green)
- ❌ Which columns are missing (yellow)

### Step 2: Add Only Missing Columns

Open `add_snapshot_columns_simple.sql` and run each statement **one by one** in phpMyAdmin.

**If you get "Duplicate column" error:**
- ✓ That's OK! It means the column already exists
- ✓ Just skip that statement and continue with the next one

---

## 🔧 MANUAL METHOD: Run SQL Commands One by One

Copy and paste these commands **one at a time** in phpMyAdmin SQL tab:

### For QUOTATIONS Table:

```sql
-- Run each separately, skip if error
ALTER TABLE `quotations` 
ADD COLUMN `company_snapshot` JSON NULL AFTER `company_id`;

ALTER TABLE `quotations` 
ADD COLUMN `client_snapshot` JSON NULL AFTER `client_id`;

ALTER TABLE `quotations` 
ADD COLUMN `bank_snapshot` JSON NULL AFTER `bank_id`;

ALTER TABLE `quotations` 
ADD COLUMN `mode_snapshot` JSON NULL AFTER `mode_id`;
```

### For QUOTATION_ITEMS Table:

```sql
-- Run each separately, skip if error
ALTER TABLE `quotation_items` 
ADD COLUMN `use_dropdown` TINYINT(1) DEFAULT 1 AFTER `quotation_id`;

-- SKIP THIS if you already have 'description' column
ALTER TABLE `quotation_items` 
ADD COLUMN `description` TEXT NULL AFTER `use_dropdown`;

ALTER TABLE `quotation_items` 
ADD COLUMN `category_snapshot` JSON NULL AFTER `category_id`;

ALTER TABLE `quotation_items` 
ADD COLUMN `product_snapshot` JSON NULL AFTER `product_id`;
```

---

## ✅ Verify Migration Success

Run this query to check:

```sql
-- Check quotations table
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'quotations' 
  AND COLUMN_NAME LIKE '%snapshot%';

-- Check quotation_items table
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'quotation_items' 
  AND COLUMN_NAME IN ('use_dropdown', 'description', 'category_snapshot', 'product_snapshot');
```

**Expected Results:**
- quotations: 4 snapshot columns
- quotation_items: 4 columns (use_dropdown, description, category_snapshot, product_snapshot)

---

## 🎯 What to Do Now

### Option 1: Browser Tool (RECOMMENDED)
```
http://localhost/crm/run_quotation_snapshot_migration.php
```
✅ Automatically handles everything  
✅ Skips existing columns  
✅ Shows clear results  

### Option 2: Check First, Then Migrate
```
1. http://localhost/crm/check_current_structure.php (see what's missing)
2. Run only the missing ALTER TABLE statements manually
```

### Option 3: Simple SQL File
```
1. Open: add_snapshot_columns_simple.sql
2. Run each statement one by one
3. Skip any that give "duplicate" error
```

---

## 💡 Common Issues

### Issue: "Duplicate column name 'description'"
**Solution:** The `description` column already exists. Skip that ALTER TABLE statement.

### Issue: "Table doesn't exist"
**Solution:** Make sure you're connected to the `crm_db` database.

### Issue: Multiple errors
**Solution:** Use the browser tool - it handles all edge cases automatically.

---

## 📞 Need Help?

1. **First:** Try the browser tool: `http://localhost/crm/run_quotation_snapshot_migration.php`
2. **Check:** Run `check_current_structure.php` to see current state
3. **Manual:** Use `add_snapshot_columns_simple.sql` one statement at a time

---

## ✨ After Migration

Once complete:
1. ✅ Create a new quotation - snapshots will be captured automatically
2. ✅ Delete a company used in that quotation
3. ✅ View the quotation - data still shows!
4. ✅ Download PDF - complete information!

**The system now preserves all historical data forever!**
