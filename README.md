![Panel Bar for Kirby CMS](http://distantnative.com/remote/github/kirby-panelbar-github.png)  

[![Release](https://img.shields.io/github/release/distantnative/panel-bar.svg)](https://github.com/distantnative/panel-bar/releases)  [![Issues](https://img.shields.io/github/issues/distantnative/panel-bar.svg)](https://github.com/distantnative/panel-bar/issues) [![License](https://img.shields.io/badge/license-GPLv3-blue.svg)](https://raw.githubusercontent.com/distantnative/panel-bar/master/LICENSE)
[![Moral License](https://img.shields.io/badge/buy-moral_license-8dae28.svg)](https://gumroad.com/l/kirby-panelbar)


This plugin enables you to include a panel bar on top of your site which gives you direct access to some administrative functions. The panel bar will only be visible to logged in users who are eligible to access the panel.

![Panel Bar in action](screen.png)

**The plugin is free. However, I would really appreciate if you could support me with a [moral license](https://gumroad.com/l/kirby-panelbar)!**


# Table of Contents
1. [Installation & Update](#Installation)
2. [Usage](#Usage)
3. [Options](#Options)
4. [Help & Improve](#Help)
5. [Version History](#VersionHistory)



# Installation & Update <a id="Installation"></a>
1. Download [Panel Bar](https://github.com/distantnative/panel-bar/zipball/master/)
2. Copy the whole folder to `site/plugins/panel-bar`



# Usage <a id="Usage"></a>
Include in your `site/snippets/header.php`:
```php
<?php echo panelbar::show(); ?>
```



# Options <a id="Options"></a>

## Output CSS separately
If you want to output the CSS not with the panel bar, but separately e.g. in the `<head>` section, you first have to use (first parameter is `true` to get all default elements):

```php
<?php echo panelbar::show($elements = true, $css = false); ?>
```

Then you can add the following code where you want to output the CSS:

```php
<?php echo panelbar::css(); ?>
```


## Custom elements

Panel Bar is ready to include custom elements. Those should be set as config option:

```php
c::set('panelbar.elements', array());
```

This option overrides all default elements. You can either include them by naming them:

```php
c::set('panelbar.elements', array(
  'panel', 
  'edit', 
  'toggle', 
  'languages', 
  'logout', 
  'user'
));
```

Or you can merge the custom array with all default elements:

```php
c::set('panelbar.elements', a::merge(array(
  'custom1',
  'custom 2'
), panelbar::defaults()));
```

You can also pass an array with all elements as first parameter when calling `panelbar::show()`.

For custom elements you can either pass the HTML directly in the array or use the name of a callable function in the array which then returns the HTML code.

Moreover, there are currently two helpers available to create elements:

**Link elements**
```php
panelbar::link(array(
  'id'   => 'panel',
  'icon' => 'cogs',
  'url'  => site()->url().'/panel',
  'text' => 'Panel'
));
```

**Dropdown elements**
```php
panelbar::dropdown(array(
  'id'    => 'lang',
  'icon'  => 'flag',
  'label' => 'Language',
  'items' => array(
               0 => array(
                     'url' => …,
                     'text' => …
                    ),
               1 => array(
                     'url' => …,
                     'text' => …
                    ),
               …
             )
));
```

**Example**
```php
c::set('panelbar.elements', array(
  'panel', 
  'edit',
  'custom-link' => panelbar::link(array(
                    'id'   => 'mum',
                    'icon' => 'heart',
                    'url'  => 'http://mydomain.com/pictureofmum.jpg',
                    'text' => 'Mum'
                  )),
  'custom-dropdown' => 'dropitpanelbar',
  'logout', 
));

function dropitpanelbar() {
  return panelbar::dropdown(array(
    'id'    => 'songs',
    'icon'  => 'headphones',
    'label' => 'Songs',
    'items' => array(
                 0 => array(
                       'url' => 'https://www.youtube.com/watch?v=BIp_Y28qyZc',
                       'text' => 'Como Soy'
                      ),
                 1 => array(
                       'url' => 'https://www.youtube.com/watch?v=gdby5w5rseo',
                       'text' => 'Me Gusta'
                      ),
               )
  ));
}
```
*Be aware of the [current problem](https://github.com/distantnative/panel-bar/issues/4) with `config.php`.*

## Position of Panel Bar
You can switch the position of the panel bar from the top to the bottom browser window border (in your `site/config/config.php`):

```php
c::set('panelbar.position', 'bottom');
```



# Help & Improve <a id="Help"></a>
*If you have any suggestions for new elements or further configuration options, [please let me know](https://github.com/distantnative/panel-bar/issues/new).*




# Version history <a id="VersionHistory"></a>
Check out the more or less complete [changelog](https://github.com/distantnative/panel-bar/blob/master/CHANGELOG.md).
