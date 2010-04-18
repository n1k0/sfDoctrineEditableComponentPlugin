sfDoctrineEditableComponent plugin
==================================

**WARNING:** This plugin is currently in an alpha state. Use with caution, feedback warmly welcome.

This plugin allows to set up edit-in-place, i18n-ready components (plain or html), typically to enable simple contents administration directly from the a frontend application. For achieving this goal, it uses some cool javascript libs like jQuery, facebox, CKEditor and the Doctrine ORM for persistence.

![capture](http://files.droplr.com/files/6619162/NMP8V.cap.png "Capture")

It's compatible with symfony 1.3 and 1.4.

Installation
------------

Using `git`, the standard way:

    $ cd /path/to/symfony/project
    $ git clone git://github.com/n1k0/sfDoctrineEditableComponentPlugin.git plugins/sfDoctrineEditableComponentPlugin

If your project already uses `git`, you can add the plugin as one of its submodule:

    $ cd /path/to/symfony/project
    $ git submodule add git://github.com/n1k0/sfDoctrineEditableComponentPlugin.git plugins/sfDoctrineEditableComponentPlugin
    $ git submodule update --init
    $ git commit -a -m"added sfDoctrineEditableComponentPlugin submodule"

If you're an old school `svn` junkie, here's the way to go:

    $ svn co http://svn.github.com/n1k0/sfDoctrineEditableComponentPlugin.git plugins/sfDoctrineEditableComponentPlugin

Configuration
-------------

First, enable the plugin in your `ProjectConfiguration.class.php` file:

    <?php
    class ProjectConfiguration extends sfProjectConfiguration
    {
      public function setup()
      {
        $this->enablePlugins(array(
          'sfDoctrinePlugin',
          'sfDoctrineEditableComponentPlugin',
        ));
      }
    }

Then, add the `sfEditableComponentAdminFilter` filter in your application's `filter.yml` file:

    sfEditableComponentAdmin:
      class: sfEditableComponentAdminFilter

Now, you're ready to run the following tasks:

    $ php symfony cache:clear
    $ php symfony doctrine:build-all

Publish the assets used by the plugin:

    $ php symfony plugin:publish-assets

**Note**: plugin helpers and modules will be enabled and loaded automatically.

Usage
-----

### Basic usage

You can display editable components whithin any template, even if they don't exist yet in the database, this way:

    <?php echo editable_component('content1')) ?>

Components become editable when the current user is authenticated and has the `editable_content_admin` credential (you can change this by editing the `app.yml` configuration, check the *Advanced configuration* section further in this document).

There are two types of editable component: `html` and `plain` ones; By default, editable components are of the `html` one, allowing rich text contents. But you can manage plain text components by calling the helper this way:

    <?php echo editable_component('content2', 'plain')) ?>

By default, editable components create a `<div/>` tag to embed contents; you can change this by caling the `editable_component()` helper this way:

    <?php echo editable_component('about-page-header-title', 'plain', 'h2')) ?>

Last, you can pass some options to the helper, for example let's add some supplementary CSS class names to the generated component html element:

    <?php echo editable_component('about-page-header-title', 'plain', 'h2', array(
      'class' => 'fancy-css-rule-name-here',
    ))) ?>

### Caching editable components

Don't bother with components cache invalidation, it's already handled automatically on update, just ensure the `cache` configuration flag is set to true in your application `settings.yml` file. 

You can override the `cache.yml` of the `sfEditableComponent` module if you want to tweak its cache TTL though.

### Note regarding authentication

The plugin doesn't provide any authentication nor credential persistence features. You can use the [sfDoctrineGuardPlugin](http://www.symfony-project.org/plugins/sfDoctrineGuardPlugin) plugin in order to easily obtain them.

Advanced usage
--------------

The plugin provides two convenient events to easily allow hooks on the component creation and update workflow:

### The `editable_component.filter_contents` filter event

This event is notified whenever the contents of a component is about to be saved in the database. You can catch this event in order to filter these contents:

    public function configure()
    {
      $this->dispatcher->connect('editable_component.filter_contents', array($this, 'filterEditableComponentContents'));
    }

    public function filterEditableComponentContents(sfEvent $event, $componentContents)
    {
      return strtoupper($componentContents);
    }

In this dumb example, all component contents will be uppercased on save.

### The `editable_component.updated` notification event

This filter event is dispatched whenever component contents are updated and saved in the database.

As an example, this is this event the plugin uses for invalidating components cache on update:

    public function configure()
    {
      $this->dispatcher->connect('editable_component.updated', array($this, 'listenToComponentUpdated'));
    }
    
    public function listenToComponentUpdated(sfEvent $event)
    {
      if ($event['view_cache'] instanceof sfViewCacheManager)
      {
        $event['view_cache']->remove(sprintf('@sf_cache_partial?module=sfEditableComponent&action=_show&sf_cache_key=%s', $event->getSubject()->getCacheKey($event['culture'])));
      }
    }

Advanced configuration
----------------------

You can configure the plugin in your `app.yml` file. You can look at the `app.yml` file bundled with the plugin, which contains all default values. 

Here are the main options available:

 - `admin_credential`: the name of the required credential for editing editable components
 - `assets_web_root`: The plugin assets web root (in case you want to move them or rename their directory)
 - `component_css_class_name`: The name of the css classname to use for editable content divs
 - `default_content`: The default caption text for an empty component, in editing mode.
 - `load_ckeditor`: Shall the plugin add CKEditor web assets to the response?
 - `load_facebox`: Shall the plugin add jQuery javascript file to the response?
 - `load_jquery`: Shall the plugin add Facebox web assets to the response?
 - `use_rich_editor`: Enable the CKEditor powered rich text editing feature for HTML components

License
-------

This plugin is licensed under the terms of the [MIT license](http://en.wikipedia.org/wiki/MIT_License).

About the author
----------------

This plugin has been created and is currently maintened by [Nicolas Perriault](http://github.com/n1k0). Feel free to contribute, I'll examine every patch, issue and pull request.