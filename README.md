# Free integration between Magento 2 and WordPress
WWM Blog Magento 2 Extension by Walk with Magento

<kbd>![Overview slideshow](https://raw.githubusercontent.com/vdevx86/wwm-blog/doc/doc/images/main.gif "Overview slideshow")</kbd>

## System Requirements

* [Magento 2](http://devdocs.magento.com/guides/v2.1/install-gde/system-requirements-tech.html): **>=2.1.x**
* [WordPress](https://wordpress.org/about/requirements/): **>=4.7.x**

## How to Install and Configure

* install WordPress at the same directory as where you have installed Magento 2;
* leave the installation directory name as is: wordpress (you can change it later). It is only for internal use, do not change it to "public" names such as "blog";
* place the extension into this directory: _app/code/Wwm/Blog_ and create last two directories manually;
* [enable](http://devdocs.magento.com/guides/v2.1/install-gde/install/cli/install-cli-subcommands-enable.html) the extension;
* copy the "functions.php" file from the extension directory into the _app_ directory, overwrite the old file. You can always get the original file from the [official repositiory](https://github.com/magento/magento2);
* login to the Magento 2 admin panel and go to: **Stores -> Configuration -> WWM -> WordPress Integration**.
Change your settings and press "Save Settings";
* install the WordPress theme by pressing the "Install Theme" button, - now it will be available from the WordPress admin panel;
* go to the WordPress admin panel and [enable](https://codex.wordpress.org/Using_Themes) the WordPress "wwm" theme

That's it!

## Linked resources
* [LinkedIn](https://www.linkedin.com/in/vdevx86/) (my profile)
* [Screenshots](http://wwm-integrations.in.ua/screenshots.html) (fullscreen): [Index](http://wwm-integrations.in.ua/i/index.png), [Single](http://wwm-integrations.in.ua/i/single.png), [Page](http://wwm-integrations.in.ua/i/page.png)
* [Website](http://wwm-integrations.in.ua/)
