# WordPress Plugin - ImmoSyncWhise

## Description

ImmoSyncWhise is a WordPress plugin that allows you to synchronize real estate listings with the Whise real estate management platform. This plugin provides various features to facilitate seamless integration with Whise, allowing you to manage your real estate properties directly from your WordPress website.

## Installation

1. Download the plugin files and place them in your `wp-content/plugins/` directory, or install the plugin directly from the WordPress plugin repository.

2. Activate the ImmoSyncWhise plugin through the 'Plugins' menu in WordPress.

3. Navigate to the plugin settings page and configure the required options.

## Configuration

The following configuration options must be set in the `.env` file located in the root directory of your WordPress installation:

```
WHISE_USER="<Your_Whise_Username>"
WHISE_PASSWORD="<Your_Whise_Password>"
LANG="nl-BE"
```

Make sure to replace `<Your_Whise_Username>` and `<Your_Whise_Password>` with your actual Whise account credentials. The `LANG` option represents the desired language for the Whise integration (e.g., "nl-BE" for Dutch - Belgium).

## Notes

- The plugin requires a valid Whise account to function correctly. Make sure to provide the correct credentials in the `.env` file.

- For additional support or feature requests, please contact the plugin developer or check the plugin's official WordPress repository page for documentation and updates.

## License

This plugin is licensed under the [MIT License](LICENSE). Feel free to modify and distribute it as needed.