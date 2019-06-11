# WordPress Multisite Subdirectory Valet Driver
A custom driver for [Laravel Valet](https://laravel.com/docs/master/valet) that adds compatibility for WordPress multisite installs that use the subdirectory configuration.

## Installation
1. `git clone https://github.com/Objectivco/WordPressMultisiteSubdirectoryValetDriver.git`
2. `cd WordPressMultisiteSubdirectoryValetDriver`
3. `cp WordPressMultisiteSubdirectoryValetDriver.php ~/.config/valet/Drivers`
4. Make sure your wp-config.php file has at least one of WP_ALLOW_MULTISITE or MULTISITE constants defined.
5. Celebrate the pain you just avoided!

## Installs with WordPress root files in a subdirectory
If your install has WordPress root files in a subdirectory (such as a submodule), simply change the class property `$wp_root` from false to the root directory name.

## Caveats
This only works with the subdirectory URL scheme. If you have a subdomain site setup with Valet, this driver will probably break it. You'll need to modify the `serves()` function to prevent this driver from handling the request.
