## Chrome Bookmarks

[![Build Status](https://travis-ci.org/blainesch/alfred-chrome-bookmarks.svg?branch=master)](https://travis-ci.org/blainesch/alfred-chrome-bookmarks)

Searches through your current Chrome bookmarks. This does not cache them in a
database, but reads directly from the JSON bookmarks file. This makes it
drastically faster.

This will search trough the name of your bookmark and the URL. It will split your
query into grams for inclusive matching and a ranking system to show relevant
results first.

## Multiple or Single Profiles

By default we will search *all of your profiles*, if you wish to just search one
profile you can modify the profile path. If you open the workflow in Alfred you
can double click the "Script Filter" to edit it. You can see a `PROFILE` item
that points to the path of your profile. By default it looks like:

~~~
PROFILE="~/Library/Application Support/Google/Chrome/**/Bookmarks" php
bookmarks.php {query}
~~~

Simply change `~/Library/Application Support/Google/Chrome/**/Bookmarks` to the
single path. For example:
* `~/Library/Application Support/Google/Chrome/Default/Bookmarks`
* `~/Library/Application Support/Google/Chrome/Profile 2/Bookmarks`

## Screen shot

![screenshot](screenshot.png)

## Installing

* Download [Chrome Bookmarks](https://github.com/blainesch/alfred-chrome-bookmarks/raw/master/Chrome%20Bookmarks.alfredworkflow)
* Open and follow instructions!
