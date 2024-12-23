# Altados_Announcements Magento 2 Module

## Module Overview
The `Altados_Announcements` module is a custom Magento 2 extension designed to manage announcements in the admin panel and display them on the frontend. This module allows administrators to create, edit, and delete announcements, assign them to specific categories, and control their visibility based on start and end dates.

## Features
- **Admin Grid**: View all announcements in a sortable and filterable grid in the admin panel.
- **CRUD Operations**: Create, edit, and delete announcements directly from the admin panel.
- **Category Assignment**: Assign announcements to one or more categories using a multi-select field.
- **Frontend Display**: Display announcements on category pages based on the assigned categories and their start/end dates.
- **Mass Actions**: Perform bulk delete operations on announcements in the admin grid.
- **Declarative Schema**: Uses Magento's declarative schema for database table creation and management.
- **ACL Support**: Includes access control for managing announcements in the admin panel.

## Installation Instructions
Follow these steps to install the module:

1. **Download the Module**:
   Clone or download the module into the `app/code/Altados/Announcements` directory of your Magento installation.

2. **Enable the Module**:
   Run the following commands in the Magento root directory:
   ```bash
   bin/magento module:enable Altados_Announcements
   bin/magento setup:upgrade
   ```

3. **Compile and Deploy Static Content**:
   If you are in production mode, run:
   ```bash
   bin/magento setup:di:compile
   bin/magento setup:static-content:deploy -f
   ```

4. **Clear Cache**:
   Clear the Magento cache to ensure the module is loaded:
   ```bash
   bin/magento cache:flush
   ```

The module is now installed and ready to use.

## Usage Instructions

### Admin Panel
1. **Access the Announcements Grid**:
   - Navigate to **Admin Panel  > Announcements > Manage Announcements**.
   - View all announcements in a grid format.

2. **Create a New Announcement**:
   - Click the **Add New Announcement** button.
   - Fill in the required fields:
     - **Label**: Name of the announcement.
     - **Content**: Text content of the announcement.
     - **Redirect URL**: Optional link for redirection.
     - **Categories**: Select one or more categories.
     - **Start Date** and **End Date**: Define the visibility period.

3. **Edit or Delete Announcements**:
   - Use the **Edit** or **Delete** actions in the grid to manage announcements.

4. **Mass Delete**:
   - Select multiple announcements and use the **Mass Delete** action to remove them.

5. **Enable/Disable Announcements**:
   - Go to **Stores > Configuration > General > Altados > Announcements**.
   - Toggle the "Enable Announcements" setting.

### Frontend
- Announcements will automatically display on the assigned category pages during the specified date range.

## Compatibility
- **Magento Versions**: Compatible with Magento 2.4.x and above.
- **PHP Versions**: Supports PHP 7.4+ and 8.0+.

---

## License
This module is licensed under the following terms:
- **OSL-3.0** (Open Software License 3.0)
- **AFL-3.0** (Academic Free License 3.0)
