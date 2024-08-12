=== CardanoPress - Initial Stake Pool Offering Dashboard ===
Contributors: pbwebdev, gaft
Donate link: https://www.paypal.com/donate/?hosted_button_id=T8MR6AMVWWGK8
Tags: cardano, blockchain, web3, ada, token-gating
Requires at least: 5.9
Tested up to: 6.6.99
Stable tag: 1.8.0
Requires PHP: 7.4
License: GPLv3
License URI: https://www.gnu.org/licenses/licenses.html
Requires Plugins: cardanopress

Integrate the Cardano blockchain with your WordPress website. Merging Web2 and Web3.


== Description ==

The CardanoPress Initial Stake Pool Offering (ISPO) Dashboard allows any project to run their own ISPO. The plugin comes
 with a dashboard calculator layout and a delegation mechanism to allow wallets, that can connect to decentralised
 application websites, to delegate directly to the ISPO stake pools within a few clicks.

 These mechanisms and designed features were created to improve the user experience for anyone that is new to ISPOs and
 want to ensure that they are delegating to the correct stake pools in a safe manner.

 These layouts and user experiences have been inspired by other successful ISPOs in the Cardano ecosystem such as Genius
  Yield and Flac Finance.

Our plugin does the heavy lifting allowing users to easily connect their wallets and interact with the ISPO dashboard.

We are supporting various wallets including:

* Nami
* Eternl
* Typhon
* GeroWallet
* Flint
* Yoroi (to a point)
* NuFi
* Cardwallet


This plugin requires the parent plugin [CardanoPress](https://wordpress.org/plugins/cardanopress/) and a free account
with [Blockfrost](http://bit.ly/3W90KDd) to be able to talk to the Cardano blockchain.

The plugin is created by the team at [PB Web Development](https://pbwebdev.com).

You can find out more information about CardanoPress and our blockchain integrations at [CardanoPress.io](https://cardanopress.io).

= Example Use Cases =

One notable project that is using the ISPO plugin is GoKey.network, who have used it to allow users calculate how many
potential tokens they will earn in the ISPO by using their calculator.

They also use it to display the current stake pool delegation statistics and allow users to easily delegate to the ISPO
stake pool using the wallet connector within a few clicks.


== Screenshots ==
1. CardanoPress ISPO configuration screen
2. GoKey ISPO Rewards Calculator
3. GoKey ISPO Stake Pool Stats & Delegation


== Follow Us ==

Follow us on [Twitter](https://twitter.com/cardanopress)
View all of our repos on [GitHub](https://github.com/CardanoPress/)
View all of our documentation and resources on our [website](https://cardanopress.io)


== Installation ==

The CardanoPress ISPO Plugin requires the parent plugin [CardanoPress](https://wordpress.org/plugins/cardanopress/). The
CardanoPress plugin manages the communication with the Cardano blockchain and wallet integrations. Please ensure you
install and configure the core CardanoPress plugin before installing the ISPO plugin.

This plugin requires your own standalone WordPress installation and access to the web server to add a line of code to your htaccess file.

1. Installing the CardanoPress ISPO Plugin
Find the plugin in the list at the backend and click to install it. Or, upload the ZIP file through the admin backend. Or, upload the unzipped tag-groups folder to the /wp-content/plugins/ directory.

2. Activate the plugin
Navigate to Plugins from the WordPress admin area and activate the CardanoPress - ISPO plugin.
The plugin will create the base pages for all that you need.

3. Configure the plugin
Navigate to the configuration screen for the plugin. Here you can configure the stake pool details and certain variables
 around how your ISPO will work.

4. Customising the Template
You can now create a menu link from your website's main navigation to the ISPO dashboard page. You can also customised
the plugins template layout. We have create the template in a way that will allow you to override the template.

Navigate to the plugin folder in your WordPress installation and copy the template layout,

`/wp-content/plugin/plugin-ispo/templates/page/Dashboard.php`

Copy this to your child theme in the folder

`/wp-content/themes/<YOUR-THEME>/cardanopress/ispo/page/Dashboard.php`

Override and customise as needed.

For more detailed documentation and tutorials on how to use the plugin, please visit the [CardanoPress documentation website](https://cardanopress.io).


== Get Support ==

We have community support available on our website under the [CardanoPress forums](https://cardanopress.io/community/). We also have an online chat support via our [Discord server](https://discord.gg/CEX4aSfkXF). We encourage you to use the forums first though as it will help others that read through the forums for support.


== Frequently Asked Questions ==

= Can I Run This on My WordPress.com Website? =

No you can not. You need full access to your web server to be able to allow for the WASM file type to load. Without this access you will not be able to run the plugin.

= Can I Get Paid Support? =

Yes you can, we offer subscription to support for our plugins and consultation to help get your project started and to a professional level.

= Where Can I See Other Projects That Are Using CardanoPress? =

If you visit our main website, [CardanoPress.io](https://cardanopress.io), there will be a section dedicated to all the websites and projects that have built using CardanoPress.

= Can I customise the look and feel of the plugin? =

Yes, we've built the plugin and sub plugins with hooks and template layouts that can over overridden in a child theme. We've followed the same methods as WooCommerce where you simply need to copy the template files into your child theme to start overriding the layouts.

We've also taking into account page builders and created short codes for all the template parts of the theme. This will allow builders such as Divi, Elementor, WPBakery to be used with CardanoPress.


== Privacy ==
This plugin does not collect or process any personal user data unless you expressively opt-in.


== Changelog ==
You can follow our [GitHub release](https://github.com/CardanoPress/plugin-ispo/releases) for full details on updates
to the plugins.

= 1.8.0 =
- Add index.php to all folders
- Update dependencies

= 1.7.0 =
- Added export data to a CSV file; available hooks:
  - `cp-ispo-export_qualified_epoch`
  - `cp-ispo-export_csv_headers`
  - `cp-ispo-export_csv_data`

= 1.6.0 =
- Use and return the correct types
- Stop using `@`prefixed attributes
- Sample template for extra rewards

= 1.5.0 =
- Correctly render provided templates in block themes

= 1.4.1 =
- Corrected version requirements
- Add new `requires` plugins header

= 1.4.0 =
- extra reward data via filter `cp-ispo-extra_tracked_rewards`
- pass down more useful hook parameters
- show successful tracking rewards notice
- customizable messages; ajax, error, & script
- check for already delegated account
- minor code fixes and improvements

= 1.3.1 =
fix ration to be parsed as float

= 1.3.0 =
Customizable toUTC date format; cp-ispo-date_format
Re-use filterable core ajax error messages
Added cp-ispo_component and cp-ispo_template shortcodes
Support multi-pool with unique showcase pages

= 1.2.0 =
An updated framework with prefixed dependencies
Handle data for unprepared pool network
Sanitize input stake address

= 1.1.0 =
Official repository release (exact same version as 0.8.0)

= 1.0.0 =
First stable release (exact same version as 0.7.0)


== Upgrade Notice ==
Please ensure that you back up your website before upgrading or modifying any of the code.
