# Free integration between Magento 2 and WordPress
WWM Blog Magento 2 Extension by Walk with Magento

<kbd>![Overview slideshow](https://raw.githubusercontent.com/vdevx86/wwm-blog/doc/doc/images/main.gif "Overview slideshow")</kbd>

## System Requirements

* [Magento 2](http://devdocs.magento.com/guides/v2.1/install-gde/system-requirements-tech.html): **>=2.1.x**
* [WordPress](https://wordpress.org/about/requirements/): **>=4.7.x**

At this moment these system requirements in some points are pretty similar (but, of course, not identical).
You have to install WordPress in the same directory as where you have installed Magento 2.

## How to Install and Configure

Let's assume you have already installed Magento 2 and WordPress.

After WordPress setup you may live the installation directory name as is (wordpress) or rename it to something very simple. It considered only for internal use, do not change it to public names such as "blog". Remember this directory name, it will be needed later. Let's say we are leaving this name as is: wordpress.

Place the Magento 2 extension into this directory: _app/code/Wwm/Blog_. Create last two directories manually. Then enable the extension. If you don't know how to enable it follow [this official guide](http://devdocs.magento.com/guides/v2.0/install-gde/install/cli/install-cli-subcommands-enable.html#instgde-cli-subcommands-enable-disable). Don't forget about the Magento 2 cache – flush it if needed.

Copy the "functions.php" file from the extension directory into the _app_ directory. Overwrite the old file. Don't worry, you can always get the original file from the [official repositiory](https://github.com/magento/magento2).

Almost done. Login to the Magento 2 admin panel and go to: **Stores -> Configuration -> WWM -> WordPress Integration**. Enter your WordPress installation directory name (I told you earlier to remember it) and other settings and press "Save Settings".
Don't forget to install the WordPress theme, press the "Install Theme" button.
Now the WordPress theme will be available from the WordPress admin panel.

Go to the WordPress admin panel and enable the WordPress "wwm" theme. If you don't know how to enable it follow [this official guide](https://codex.wordpress.org/Using_Themes).

That's it!

## Linked resources
* [LinkedIn](https://www.linkedin.com/in/vdevx86/) (my profile)
* [Screenshots](http://wwm-integrations.in.ua/screenshots.html) (fullscreen): [Index](http://wwm-integrations.in.ua/i/index.png), [Single](http://wwm-integrations.in.ua/i/single.png), [Page](http://wwm-integrations.in.ua/i/page.png)
* [Website](http://wwm-integrations.in.ua/)
