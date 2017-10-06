<?php

namespace Mak001\FacebookFeed\Tests;

use Mak001\FacebookFeed\FacebookFeed;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

/**
 * Class FacebookFeedTest_Object
 */
class FacebookFeedTest_Object extends DataObject implements TestOnly
{

    /**
     * @var array
     */
    private static $extensions = array(
        FacebookFeed::class
    );
}
