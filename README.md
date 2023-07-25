# FacetWP - Layout Switcher Add-on

Add one or more layout switchers with a shortcode.

## Usage

A layout switcher sets and switches the class of one or more targeted items on the page.
A switcher can be added with a shortcode.

A single-target switcher can be used to switch the layout (e.g. number of columns) in the results listing. A multi-target switcher can be used to show/hide a map and the results listing. 

The add-on comes with a range of built-in modes with accompanying CSS, but you can also build your own.

### Set layout modes

Add any number of layout modes, with any name, comma-separated, with the "layoutmodes" attribute. The layout modes are the options in the switcher, and the class (sanitized and with dashes) that is set on the targeted items.

```php
[facetwp layoutmodes="4 columns, 3 columns, 2 columns, list, compact list"]
```

#### Shortcode placement
Make sure the shortcode is placed outside the template that will have its layout switched, e.g. outside the element with the class "facetwp-template" when using a FacetWP listing.

### Set layout target
A switcher can be single-target or multi-target:

#### Single-target switchers
By default, the switcher changes the class of the post listing div which has the class "facetwp-template". If you want to use a different target layout, you can set its class with the target attribute. The following example targets a listing with class "facetwp-template-static", which is the class of any static facetwp template.

You can target any element by using its class, it does not have to be a Facetwp template.
However, Facetwp needs to be installed to make use of the fUtil and fSelect JS libraries.

```php
[facetwp layoutmodes="grid, list" target="facetwp-template-static"]
```

Note: makes sure to only use single classnames, not advanced CSS selectors like dots between classes etc. 

If you need to target (other) switchers themselves (for example to hide them together with the targeted listing), you can use "singletarget" for all single-target switchers on the page, or "multitarget" for all multi-target switchers.
To target specific switchers, you can give switchers a custom class with the "class" attribute (see below).


#### Multi-target switchers

A switcher can also target multiple items on the page. This can be used to show hide multiple elements. For example a map and a results listing. This switcher (with 3 modes) will target those two items, by setting their class as target:

```php
[facetwp layoutmodes="map, results, both" target="facetwp-template, facetwp-type-map"]
```

#### Use built-in modes and CSS

The add-on comes with 5 built-in layout modes for single-target switchers. The CSS for these built-in modes switches the number of columns of a FacetWP Listing Builder template:

- 4 columns
- 3 columns
- 2 columns
- list
- compact list

For multi-target switchers, there are 3 built-in layout modes. The CSS for these 3 modes show (and hide) a Map facet and a FacetWP listing template, or both:
- map
- results
- both


The above mode names have built-in CSS: 
- for the svg icons when using type=icons (see below).
- for post listings made with the Listing Builder that have a CSS grid layout with a set number of columns. The built-in CSS _only_ switches the number of columns of the layout. All other desired layout changes, e.g. floating images to the left in "list" mode, or hiding images entirely in "compact list", you can add yourself.
- Show/hide CSS for Map facets and FacetWP listings (of any type, not only Listing Builder listing templates).

You don't have to use these predefined modes: you can set your own modes and write the corresponding CSS for it yourself. The default CSS can be removed, see below.

### Set a layout switcher type
The layout switcher can have four output types:
- "text" (=default)
- "icons"
- "dropdown"
- "fselect"

When no type is specified, the default type is "text". 

When using type="icons", the icons are pre-styled if you use any of the above mentioned 5 built-in modes.

To set the output type, use the "type" attribute:
```php
[facetwp layoutmodes="grid, list, compact" type="icons"]
```
```php
[facetwp layoutmodes="grid, list, compact" type="dropdown"]
```
```php
[facetwp layoutmodes="grid, list, compact" type="fselect"]
```

### Add a label
Without the attribute "label", there is no label.

To use the default label, add the label attribute and set it to "true". The default label text is "Show as:".
```php
[facetwp layoutmodes="grid, list, compact" label="true"]
```

#### Customize the label text:
To customize the label text, set the label attribute something else than "true":
```php
[facetwp layoutmodes="grid, list, compact" label="View as:"]
```

### Label position for dropdown and fselect type

The default label position is "outside": placed to the left of the switcher. 
To use the label as the first Dropdown or fSelect option instead, use labelposition="inside":
```php
[facetwp layoutmodes="grid, list, compact" label="View as" type="fselect" labelposition="inside"]
```

### Inital layout mode

The add-on automatically detects the first layout mode in a switcher, and sets (and syncs) this mode as active on page load. For example, a listing may be styled as 3 columns, but if the first layout mode is 4 columns, the listing will show as 4 columns on page load.

This behaviour can be turned off by using the attribute setinitial="false":

```php
[facetwp layoutmodes="grid, list, compact" setinitial="false"]
```

This can be useful if you are using the attribute labelposition="inside" with type="dropdown" or type="fselect". In this case the switcher has the "Show as" label as first item in the switcher. Or you may want the initial CSS styling to be different from the styling set by layout modes.

The initial layout mode is detected on the first switcher on the page, if you have more. This works independently for single-target and multi-target switchers. So both for single-target and multi-target, the first mode of the first switcher of its type determines the initial layout mode on page load. 


### Using multiple layout switchers, for one or more target layouts

You can use multiple switchers (of any type) to switch a layout target, for example above and below a post listing. The selected layout mode will be synced between the switchers that target the same item(s). You can even have multiple layouts, each with their own (set of syncing) switcher(s).

You can also have (multiple) single-target switchers and (multiple) multi-target switchers on the page. So for example one (or more) switcher(s) to show/hide a map and a listing, and another one (or more) for switching the listing layout.

### Add a custom class
With the optional attribute "class", you can set one or more custom classes for a switcher for styling purposes. These custom classes can also be used to target specific switchers with the "target" attribute, for example if you want to hide them when hiding a listing with a switcher. 

To add a single class: 
```php
[facetwp layoutmodes="grid, list, compact" class="myclass"]
```

To add multiple classes:
```php
[facetwp layoutmodes="grid, list, compact" class="myclass myotherclass"]
```

### Remove the CSS styling
To remove the Layout Switcher's built-in CSS entirely (so you can add your own), add the following code to your functions.php:

```php
add_filter( 'facetwp_layout_switcher_load_css', '__return_false' );
```