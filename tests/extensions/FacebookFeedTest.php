<?php

namespace Mak001\FacebookFeed\Tests;

use Mak001\FacebookFeed\FacebookFeed;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;

/**
 * Class FacebookFeedTest
 */
class FacebookFeedTest extends SapphireTest
{

    protected $extraDataObjects = array(
        FacebookFeedTest_Object::class
    );

    public function setUp()
    {
        parent::setUp();
        $app_id = getenv('app_id');
        $app_secret = getenv('app_secret');
        $default_access_token = getenv('default_access_token');

        Config::modify()->set(FacebookFeed::class, 'app_id', $app_id);
        Config::modify()->set(FacebookFeed::class, 'app_secret', $app_secret);
        Config::modify()->set(FacebookFeed::class, 'default_access_token', $default_access_token);
    }

    /**
     * Tests updateCMSFields()
     */
    public function testUpdateCMSFields()
    {
        $object = Injector::inst()->get(FacebookFeedTest_Object::class);
        $fields = $object->getCMSFields();
        $this->assertInstanceOf(FieldList::class, $fields);
    }

    /**
     * Tests createFacebookHook()
     */
    public function testCreateFacebookHook()
    {
        $object = Injector::inst()->get(FacebookFeedTest_Object::class);
        $fb = $object->createFacebookHook();
        $this->assertInstanceOf('Facebook\Facebook', $fb);
    }

    /**
     * Tests getFacebookFeed()
     */
    public function testGetFacebookFeed()
    {
        $object = Injector::inst()->get(FacebookFeedTest_Object::class);
        $object->FacebookPageID = 'silverstripe';

        $posts = $object->getFacebookFeed()->toMap();
        $posts = $posts['Posts']->toArray();
        $this->assertEquals(2, count($posts));

        $posts = $object->getFacebookFeed(1)->toMap();
        $posts = $posts['Posts']->toArray();
        $this->assertEquals(1, count($posts));

        $posts = $object->getFacebookFeed(4)->toMap();
        $posts = $posts['Posts']->toArray();
        $this->assertEquals(4, count($posts));


        $object->FacebookPageID = 'asdsdadasdasdasdad';

        $error = $object->getFacebookFeed()->toMap();
        $this->assertArrayHasKey('Error', $error);

        Config::modify()->set(FacebookFeed::class, 'default_access_token', '');
        $error = $object->getFacebookFeed()->toMap();
        $this->assertArrayHasKey('Error', $error);
    }
}
