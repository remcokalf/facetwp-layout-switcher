# FacetWP - Layout Switcher Add-on

Add one or more layout switchers with a shortcode.

## Usage

A layout switcher switches the class of a listing template. A switcher can be added with a shortcode.

### Set layout modes

Add any number of layout modes, with any name, comma-separated, with the "layoutmodes" attribute:

```php
[facetwp layoutmodes="4 columns, 3 columns, 2 columns, list, compact list"]
```


#### Shortcode placement
Make sure the shortcode is placed outside the template that will have its layout switched, e.g. outside the element with the class "facetwp-template" when using a FacetWP listing.

#### Use built-in modes and CSS

The add-on comes with 7 built-in layout modes. These modes switch the number of columns of a FacetWP Listing Builder template:

- 6 columns
- 5 columns
- 4 columns
- 3 columns
- 2 columns
- list
- compact list

The above 7 mode names have built-in CSS when using a switcher with type="icons" (see below): 
- for the icons themselves, with svg background images.
- for post listings made with the Listing Builder, which have a CSS grid layout with a set number of columns. The built-in CSS _only_ switches the number of columns of the layout. All other desired layout changes, e.g. floating images to the left in "list" mode, or hiding images entirely in "compact list", you can add yourself.

You don't have to use these 5 redefined modes: you can set your own modes and write the corresponding layout listing CSS for it yourself.

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

The default label position is "outside": placed to the left of the dropdown or fselect. 
To use the label as the first dropdown/fselect option instead, use labelposition="inside":
```php
[facetwp layoutmodes="grid, list, compact" label="View as" type="fselect" labelposition="inside"]
```

### Active layout mode
When using type="text" or type="icons", the selected layout mode has the class "active". The first mode will have the "active" class on page load.

For type="dropdown" and type="fselect", with the label position "inside", there is no option selected on page loa. So it's best to style the first option not to be the default mode, and use the state in which no option is selected as the default layout mode.

With the label position "outside", it is more logical to style the first mode as the "active" mode because it is visibly selected on page load.

### Layout target
By default, the switcher changes the class of the post listing with class "facetwp-template". If you want to use a different target layout, you can set its class with the target attribute. The following example targets a listing with class "facetwp-template-static", which is the class of any static facetwp template. 

You can target any element by using its class, it does not have to be a Facetwp template.
However, Facetwp needs to be installed to make use of the fUtil and fSelect JS libraries.

```php
[facetwp layoutmodes="grid, list" target="facetwp-template-static"]
```

### Using multiple layout switchers, for one or more target layouts

You can use multiple switchers (of any type) to switch a layout target, for example above and below a post listing. The selected layout mode will be synced between the switchers that target the same layout. You can even have multiple layouts, each with their own (set of syncing) switcher(s).

### Remove the CSS styling
To remove the Layout Switcher's built-in CSS entirely, add the following code to your functions.php:

```php
add_filter( 'facetwp_layout_switcher_load_css', '__return_false' );
```