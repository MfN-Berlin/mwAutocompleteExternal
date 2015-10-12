#mwAutocompleteExternal

Adds external sources (e.g. Wikispecies) to autocomplete in semantic forms.

## Dependencies
* Semantic MediaWiki
* Semantic Forms
* Snoopy

## Installation
1. Put the autocomplete.php file somwhere on your server
2. Make sure the Snoopy PHP class is installed, or download it
3. Copy example.ini to config.ini and edit it to your convenience
4. In Localsettings of your wiki, add a line naming the url of autocomplete.php:
 $sfgAutocompletionURLs['wikispecies'] = 'http://path-to-my/autocomplete.php?source=wikispecies&search=<substr>'; 

## Usage
For example, to retrieve scientific names from Wikispecies into a text input field, add the "values from url" parameter to your input field:

 {{{field|Taxon|input type=text with autocomplete|values from url=wikispecies}}}
 
Done. A list of matching scientific names should appear when you start tiping in the input field.