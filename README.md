# CardanoPress - Initial Stake Pool Offering (ISPO) Dashboard Plugin


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

## Example Use Cases

One notable project that is using the ISPO plugin is [GoKey.network](https://gokey.network), who have used it to allow
users calculate how many potential tokens they will earn in the ISPO by using their calculator.

They also use it to display the current stake pool delegation statistics and allow users to easily delegate to the ISPO
stake pool using the wallet connector within a few clicks.




## Installation

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

## Follow Us

Follow us on [Twitter](https://twitter.com/cardanopress)
View all of our repos on [GitHub](https://github.com/CardanoPress/)
View all of our documentation and resources on our [website](https://cardanopress.io)


## Feature Requests

Please submit an [issue](https://github.com/cardanopress/plugin-ispo/issues) on the GitHub repo to submit requests and
ideas for the project.

## Support

Join us on Discord to learn more about the project and get support on integrations.
[https://discord.gg/CEX4aSfkXF](https://discord.gg/CEX4aSfkXF)

You can find more documentation on our main website: https://cardanopress.io

> Support the development of our plugin by delegating to our Stake pool Ticker: *ADAOZ* - [https://cardanode.com.au](https://cardanode.com.au).
