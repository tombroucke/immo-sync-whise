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
LANG="nl-BE"
```

The `LANG` option represents the desired language for the Whise integration (e.g., "nl-BE" for Dutch - Belgium).

To include information on how to call your custom commands in the readme, you can create a dedicated section or subsection for these commands. Here's an example of how you can add this information to your existing readme:

## Custom Commands

The ImmoSyncWhise plugin provides custom commands that can be executed via the WordPress command-line interface (WP-CLI). These commands offer additional functionality to manage your real estate listings. To use these commands, follow the instructions below:

### Fetch All Command

**Command Name:** `iws fetch-all`

To fetch all real estate listings, use the following command:

```bash
wp iws fetch-all handle
```

### Sync Deleted Command

**Command Name:** `iws sync-deleted`

To synchronize deleted real estate listings, use the following command:

```bash
wp iws sync-deleted handle
```

### Sync Today Command

**Command Name:** `iws sync-today`

To synchronize real estate listings updated today, use the following command:

```bash
wp iws sync-today handle
```

These custom commands provide additional control over your real estate listings, allowing you to fetch, sync deleted listings, or sync today's updates as needed.

## Testing (PHPUnit)

The ImmoSyncWhise plugin includes PHPUnit tests to ensure its functionality remains consistent and free from regressions. To run the tests, follow these steps:

1. **Install PHPUnit:** If you haven't installed PHPUnit globally, you can do so using Composer. Run the following command in your terminal or command prompt:
`composer global require phpunit/phpunit`

2. **Set Up Test Environment:** Before running the tests, you need to set up a testing environment. Create a separate database for testing, and configure the testing database credentials in the `phpunit.xml` file located in the plugin's root directory. You can use the `phpunit.xml.dist` file as a template and modify it accordingly.

3. **Run Tests:** Once the testing environment is set up, you can execute the PHPUnit tests using the following command:
`phpunit`


PHPUnit will automatically discover and run all tests within the plugin's `tests` directory. The tests are located in the `tests` directory, following the PHPUnit naming conventions.

4. **Review Test Results:** After running the tests, PHPUnit will display the test results, including the number of tests run, assertions made, and any failures or errors encountered.

It's essential to regularly run these tests to ensure the stability and correctness of the plugin as you make changes or add new features. Always strive to maintain a high test coverage and keep the tests up-to-date with any code changes.

Please note that the above instructions assume you already have PHPUnit and Composer installed on your system. If you need help setting up PHPUnit or Composer, refer to their respective documentation:

- PHPUnit: https://phpunit.de/getting-started.html
- Composer: https://getcomposer.org/doc/00-intro.md
sting section, developers and contributors will have clear instructions on how to test the plugin and can ensure that new changes do not introduce unexpected issues.

## Notes

- The plugin requires a valid Whise account to function correctly. Make sure to provide the correct credentials in the `.env` file.

- For additional support or feature requests, please contact the plugin developer or check the plugin's official WordPress repository page for documentation and updates.

## Development

This package's dependencies are wrapped in our own namespace by a (patched version)[https://github.com/coenjacobs/mozart/pull/136] of (coenjacobs/mozart)[https://github.com/coenjacobs/mozart].

1. `composer global require cweagans/composer-patches`
2. Add patch to `~/.composer/composer.json`:
```json
	...
	"extra": {
		"patches": {
			"coenjacobs/mozart": {
				"allow to skip duplicated files": "https://github.com/coenjacobs/mozart/pull/136.patch"
			}
		}
	},
	...
```
3. `composer global require cweagans/coenjacobs/mozart`
4. In plugin root: `~/.composer/vendor/bin/mozart compose`

## License

This plugin is licensed under the [MIT License](LICENSE). Feel free to modify and distribute it as needed.

## Thanks

Special thanks to fw4, this WordPress plugin leverages [their package](https://github.com/fw4-bvba/whise-api) under the hood.
