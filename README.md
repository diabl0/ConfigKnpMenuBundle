ConfigKnpMenuBundle
===================

[![Build Status](https://travis-ci.org/jbouzekri/ConfigKnpMenuBundle.svg?branch=master)](https://travis-ci.org/jbouzekri/ConfigKnpMenuBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0ba3e2e6-4b91-4886-aa8d-4620eb243845/mini.png)](https://insight.sensiolabs.com/projects/0ba3e2e6-4b91-4886-aa8d-4620eb243845)

Introduction
------------

This bundle provides a way to configure your knp menus via yaml configuration file.

For more information on knp menu, read :
* The [Knp Menu Documentation](https://github.com/KnpLabs/KnpMenu/blob/master/README.markdown)
* The [Knp Menu Bundle Documentation](https://github.com/KnpLabs/KnpMenuBundle/blob/master/README.md)

This bundle was inspired by the [OroNavigationBundle](https://github.com/orocrm/platform/tree/master/src/Oro/Bundle/NavigationBundle) and [jbouzekri/ConfigKnpMenuBundle](https://github.com/jbouzekri/ConfigKnpMenuBundle)

Installation
------------

You can use composer for installation.

```bash
composer require diabl0/config-knp-menu-bundle
```

Documentation
-------------

In order to use this bundle, you must define your menu configuration in a **navigation.yaml** file in your configs (usually /config/packages/navigation.yaml). If you are using Flex, this config is created for you.

format :
``` yml
# Default configuration for extension with alias: "config_knp_menu"
config_knp_menu:
    menu:
        name:
            tree:

                # Prototype
                name:
                    route:                ~
                    routeParameters:      []
                    uri:                  ~
                    label:                ~
                    display:              true
                    displayChildren:      true
                    order:                ~
                    attributes:           []
                    linkAttributes:       []
                    childrenAttributes:   []
                    labelAttributes:      []
                    roles:                []
                    extras:               []
                    children:

                        # Prototype
                        name:
                            route:                ~
                            routeParameters:      []
                            uri:                  ~
                            label:                ~
                            display:              true
                            displayChildren:      true
                            order:                ~
                            attributes:           []
                            linkAttributes:       []
                            childrenAttributes:   []
                            labelAttributes:      []
                            roles:                []
                            extras:               []
                            children:

```

Example :
``` yml
config_knp_menu:
  menu:
    # main menu
    my_mega_menu:
      tree:
        home:
          label: Home
          route: index
        one:
          label: One
          attributes:
            icon: fa-award
          children:
            oo:
              label: Eleven
              uri: "#11"
            ot:
              label: Twelfe
              uri: "#12"

```

It will configure a provider for knp menu factory. You can then use your my_mega_menu in twig as a classic knp menu :

``` twig
{{ knp_menu_render('my_mega_menu') }}
```

Configuration
-------------

This is the available configuration definition for an item.

``` yml
        menu_key:
            uri: "An uri. Use it if you do not define route parameter"
            route: "A sf2 route without @"
            routeParameters: "an array of parameters to pass to the route"
            label: "My first label"
            order: An integer to sort the item in his level.
            attributes: An array of attributes passed to the knp item. 
                        You can use it to pass additional data to twig template like icons, styles etc.
            linkAttributes: An array of attributes passed to the a tag
            childrenAttributes: An array of attributes passed to the children block
            labelAttributes: An array of attributes passed to the label tag
            display: boolean to hide the item
            displayChildren: boolean to hide the children
            roles: array of item (string roles) passed to isGranted securityContext method to check if user has rights in menu items
            extras: An array of extra parameters (for example icon img, additional content etc.)
            children: # An array of subitems
                second_level_item:
                    label: My second level
```

This configuration matches the methods available in the Knp Menu Item class

Menu security
-------------

Security context is injected in menu item provider.

For root menu item, display or hide it in your twig template.
For children items, if you didn't add the roles key, they will be displayed.
Else it will passed the array of key to the isGranted method and check if you have rights on the the item.

Breadcrumbs
-----------

Simple example:
```twig
                        <ul>

                            {% for breadcrumb_item in knp_menu_get_breadcrumbs_array(knp_menu_get_current_item('main')) %}
                                <li class="m-nav__item">
                                    {% if  breadcrumb_item.uri is not empty %}
                                        <a href="{{ breadcrumb_item.uri }}">
                                    {% endif %}
                                    <span class="m-nav__link-text">{{ breadcrumb_item.label }}</span>
                                    {% if  breadcrumb_item.uri is not empty %}
                                        </a>
                                    {% endif %}
                                </li>
                            {% endfor %}
                        </ul>
```
