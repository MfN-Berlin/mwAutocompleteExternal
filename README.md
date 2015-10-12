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
 $sfgAutocompletionURLs['wikispecies'] = 'http://path-to-my/autocomplete.php?source=wikispecies&search=<substr>'; 
 
Done. A list of matching scientific names should appear when you start tiping in the input field.

## Usage: Wikipedia category
For example, to retrieve the a list of page titles from a categoryfrom Wikispedia into a text input field:
1. Add the "values from url" parameter to your input field:

 {{{field|Taxon|input type=text with autocomplete|values from url=wikipedia}}}
 
2. In Localsettings of your wiki, add a line naming the url of autocomplete.php:
 $sfgAutocompletionURLs['wikipedia'] = 'http://path-to-my/autocomplete.php?source=wikispediacategory&category=mycategory&lang=en&search=<substr>'; 
 
Done. A list of matching page names should appear when you start tiping in the input field.