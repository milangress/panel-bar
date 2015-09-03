# Panel Bar for Kirby 2 CMS

This plugin enables you to include a panel bar on top of your site. The panel bar will only be visible to logged in users who are eligible to access the panel.

![Panel Bar](screen.png)

# Installation
1. Copy the whole folder to `site/plugins/panel-bar`
2. Include in your `site/snippets/header.php`:
```php
<?php echo panelbar::show(); ?>
```
