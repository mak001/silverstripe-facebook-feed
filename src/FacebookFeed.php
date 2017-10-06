<?php

namespace Mak001\FacebookFeed;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\View\ArrayData;

/**
 * Class FacebookFeed
 */
class FacebookFeed extends DataExtension
{
    private static $db = array(
        'FacebookPageID' => 'Varchar(255)'
    );

    public function updateCMSFields(FieldList $fields)
    {
        $pageField = TextField::create('FacebookPageID');
        $pageField->setRightTitle('The ID for the facebook page. Would be "foo" if the facebook url is "https://www.facebook.com/foo/"');
        $fields->addFieldToTab('Root.Main', $pageField, 'Content');
    }

    public function createFacebookHook()
    {
        $app_id = Config::inst()->get(FacebookFeed::class, 'app_id');
        $app_secret = Config::inst()->get(FacebookFeed::class, 'app_secret');
        $default_access_token = Config::inst()->get(FacebookFeed::class, 'default_access_token');

        return new Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_access_token' => $default_access_token,
            'default_graph_version' => 'v2.10',
        ]);
    }

    public function getFacebookFeed($limit = 0)
    {
        if ($limit === 0) {
            $limit = Config::inst()->get(FacebookFeed::class, 'defaultLimit');
        }

        $fb = $this->createFacebookHook();

        try {
            $response = $fb->get('/' . $this->owner->FacebookPageID . '/?fields=feed.limit(' . $limit . '){created_time,message,story,id,full_picture}');
            $list = new ArrayList();
            $edges = $response->getGraphNode()['feed'];

            // loop through all posts
            foreach ($edges as $post) {
                // generate the link
                $link = vsprintf('//facebook.com/%s/posts/%s', explode('_', $post->getField('id')));
                // generate the date
                $date = $post->getField('created_time')->format('F j');

                // make links into hrefs
                $message = preg_replace(
                    '"\b(https?://\S+)"',
                    '<a href="$1" target="_blank">$1</a>',
                    $post->getField('message')
                );
                $list->push(new ArrayData(array(
                    'Title' => $post->getField('story'),
                    'Content' => $message,
                    'Image' => $post->getField('full_picture'),
                    'Link' => $link,
                    'Date' => $date,
                )));
            }

            return new ArrayData(array('Posts' => $list));
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            return new ArrayData(array('Error' => 'Graph returned an error: ' . $e->getMessage()));
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            return new ArrayData(array('Error' => 'Facebook SDK returned an error: ' . $e->getMessage()));
        }
    }
}
