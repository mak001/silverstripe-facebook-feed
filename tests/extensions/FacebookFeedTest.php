<?php

use Facebook\Exceptions\FacebookSDKException;

/**
 * Class FacebookFeedTest
 */
class FacebookFeedTest extends SapphireTest
{

    protected $extraDataObjects = array(
        'FacebookFeedTest_Object'
    );

    public function setUp()
    {
        parent::setUp();
        $app_id = getenv('app_id');
        $app_secret = getenv('app_secret');
        $default_access_token = getenv('default_access_token');

        Config::inst()->update('FacebookFeed', 'app_id', $app_id);
        Config::inst()->update('FacebookFeed', 'app_secret', $app_secret);
        Config::inst()->update('FacebookFeed', 'default_access_token', $default_access_token);
    }

    /**
     * Tests updateCMSFields()
     */
    public function testUpdateCMSFields()
    {
        $object = Injector::inst()->get('FacebookFeedTest_Object');
        $fields = $object->getCMSFields();
        $this->assertInstanceOf('FieldList', $fields);
    }

    /**
     * Tests createFacebookHook()
     */
    public function testCreateFacebookHook()
    {
        $object = Injector::inst()->get('FacebookFeedTest_Object');
        $fb = $object->createFacebookHook();
        $this->assertInstanceOf('Facebook\Facebook', $fb);
    }

    /**
     * Tests getFacebookFeed()
     */
    public function testGetFacebookFeed()
    {
        $object = Injector::inst()->get('FacebookFeedTest_Object');
        $object->FacebookPageID = 'silverstripe';
        $object->write();

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
        $object->write();

        $error = $object->getFacebookFeed()->toMap();
        $this->assertArrayHasKey('Error', $error);

        Config::inst()->update('FacebookFeed', 'default_access_token', '');
        $error = $object->getFacebookFeed()->toMap();
        $this->assertArrayHasKey('Error', $error);
    }
}
