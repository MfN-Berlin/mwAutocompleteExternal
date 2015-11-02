#mwAutocompleteExternal

Adds external sources (e.g. Wikipedia categories, Wikispecies) to autocomplete in semantic forms.

## Dependencies
* Semantic MediaWiki
* Semantic Forms
* Snoopy

## Installation
1. Put the autocomplete.php file somwhere on your server
2. Make sure the Snoopy PHP class is installed, or download it
3. Copy example.ini to config.ini and edit it to your convenience

## Usage: Wikispecies
For example, to retrieve scientific names from Wikispecies into a text input field:
1. Add the "values from url" parameter to your input field:

 {{{field|Taxon|input type=text with autocomplete|values from url=wikispecies}}}
 
2. In Localsettings of your wiki, add a line naming the url of autocomplete.php:
```php
$sfgAutocompletionURLs['wikispecies'] = 'http://path-to-my/autocomplete.php?source=wikispecies&search=<substr>'; 
```
Done. A list of matching scientific names should appear when you start tiping in the input field.

## Usage: Wikipedia category
For example, to retrieve the a list of page titles from a category from Wikispedia into a text input field:

1. Add the "values from url" parameter to your input field:

 {{{field|Taxon|input type=text with autocomplete|values from url=wikipedia}}}
 
2. In Localsettings of your wiki, add a line naming the url of autocomplete.php:
```php
$sfgAutocompletionURLs['wikipedia'] = 'http://path-to-my/autocomplete.php?source=wikispediacategory&category=mycategory&lang=en&search=<substr>'; 
```
* You'll need to set the "category" and "lang" parameters. Make sure to replace spaces with underscores in the category name.
* To retrieve names from several categories, separate them with a pipe, like so: category=cat1|cat2|cat3
* A maximum of 500 names can be retrieved. This is a limitation of the wikipedia API.

Done. A list of matching page names should appear when you start tiping in the input field.

## Other data sources
RVK: Regensburger Verbundklassifikation (German library reference) 
* rvk: Queries the first two levels of the RVK tree for $query.
* rvkregister: Queries the entire register for $query.
* rvkfiltered: Will search for $filter in RVK and then search the results for $query

