# Quotation Items Checkbox Implementation

## Overview
Successfully implemented checkbox functionality in quotation management system that allows users to toggle between:
- **Checked**: Use category and product dropdowns (existing functionality)
- **Unchecked**: Use custom description field for items

## Database Changes

### New Fields Added to `quotation_items` Table:
1. **`description`** (TEXT, NULL) - Stores custom description when checkbox is unchecked
2. **`use_dropdown`** (TINYINT(1), DEFAULT 1) - Flag to determine mode (1 = dropdown, 0 = description)

### Migration Script:
- Created `add_description_to_quotation_items.sql` 
- Created `run_migration.php` for easy database update

## Files Modified

### 1. Database Model (`application/models/Quotation_model.php`)
- **Updated `insert_items()`** - Now handles both dropdown and description modes
- **Updated item retrieval methods** - Include new fields in queries

### 2. Controller (`application/controllers/Quotation.php`)
- **Updated `_prepare_items()`** - Processes checkbox state and handles data accordingly
- **Enhanced validation** - Validates items based on selected mode
- **Updated `store()` method** - Improved item validation logic

### 3. Views

#### Add Form (`application/views/quotation/form.php`)
- Added checkbox column in items table
- Added description textarea (hidden by default)
- Updated JavaScript for dynamic show/hide functionality
- Enhanced form validation for both modes

#### Edit Form (`application/views/quotation/edit.php`)
- Same checkbox functionality as add form
- Properly loads existing data for both modes
- Handles existing items with backward compatibility

#### PDF View (`application/views/quotation/pdf_view.php`)
- Updated to display either product name or description based on `use_dropdown` field

#### Regular View (`application/views/quotation/view.php`)
- Updated to display either product name or description based on `use_dropdown` field

## Functionality

### Checkbox Behavior:
- **✅ Checked (Default)**: 
  - Shows Category and Product/Service dropdowns
  - Hides description field
  - Validates category and product selection
  - Auto-fills rate from product data

- **❌ Unchecked**:
  - Hides Category and Product/Service dropdowns
  - Shows description textarea
  - Validates description field
  - Allows manual rate entry

### JavaScript Features:
- Real-time toggle between modes
- Proper form validation for both modes
- Dynamic row addition with correct checkbox state
- Field clearing when switching modes

### Data Storage:
- **Dropdown Mode**: Stores `category_id`, `product_id`, `use_dropdown=1`
- **Description Mode**: Stores `description`, `use_dropdown=0`, nullifies category/product IDs

## Installation Steps

1. **Run Database Migration**:
   ```bash
   # Navigate to CRM directory
   cd d:/wamp64/www/crm
   
   # Run migration script
   php run_migration.php
   ```

2. **Verify Installation**:
   - Check database for new columns
   - Test quotation add/edit forms
   - Verify PDF generation works with both modes

## Usage Instructions

### For Users:
1. **Adding New Quotation**:
   - Each item row has a checkbox in first column
   - **Checked**: Select category → product → quantity → rate (auto-filled)
   - **Unchecked**: Enter description → quantity → rate (manual)

2. **Editing Existing Quotations**:
   - Existing items maintain their original mode
   - Can switch between modes by toggling checkbox
   - Data is preserved when switching modes

### For Developers:
- All existing quotations remain functional (backward compatible)
- New field `use_dropdown` defaults to 1 for existing records
- PDF and view templates automatically handle both modes

## Technical Details

### Database Schema:
```sql
ALTER TABLE quotation_items 
ADD COLUMN description TEXT NULL AFTER product_id,
ADD COLUMN use_dropdown TINYINT(1) DEFAULT 1 AFTER description;
```

### Data Flow:
1. **Frontend**: Checkbox state controls UI visibility
2. **Controller**: `_prepare_items()` processes form data based on checkbox
3. **Model**: `insert_items()` stores data in appropriate fields
4. **Views**: Display logic checks `use_dropdown` field

## Benefits

1. **Flexibility**: Users can mix dropdown items and custom descriptions
2. **Backward Compatibility**: Existing quotations work unchanged
3. **User Experience**: Intuitive checkbox interface
4. **Data Integrity**: Proper validation for both modes
5. **PDF Support**: Both modes render correctly in PDFs

## Testing Checklist

- [x] Database migration runs successfully
- [x] Add quotation with dropdown items
- [x] Add quotation with description items  
- [x] Add quotation with mixed item types
- [x] Edit existing quotations
- [x] PDF generation works for both modes
- [x] Form validation works correctly
- [x] Backward compatibility maintained

## Future Enhancements

1. **Bulk Mode Toggle**: Option to switch all items to same mode
2. **Description Templates**: Pre-defined description templates
3. **Rich Text**: HTML editor for descriptions
4. **Import/Export**: CSV support for bulk item entry

---

**Implementation Status**: ✅ **COMPLETED**  
**Date**: October 13, 2025  
**Version**: 1.0  
**Tested**: ✅ Ready for production use
