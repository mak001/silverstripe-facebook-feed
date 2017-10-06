# SilverStripe Facebook feed

## Overview

The Facebook feed module uses Facebook's php sdk to get a page's feed.

## Requirements

* [silverstripe/silverstripe-framework](https://github.com/silverstripe/silverstripe-framework) ^3.4
* [facebook/graph-sdk](https://github.com/facebook/php-graph-sdk) ^5.6

## Installation

`composer require dynamic/facebook-feed`

Add app_id, app_secret, and default_access_token to config.yml.
```yml
FacebookFeed:
  app_id: XXX
  app_secret: YYY
  default_access_token: ZZZ
```
The default limit can also be specified in the config.yml. 
```yml
FacebookFeed:
  defaultLimit: 2
```

## Maintainer Contact

 *  [mak001](http://www.matthewkoerber.com) (<mak001001@gmail.com>)
