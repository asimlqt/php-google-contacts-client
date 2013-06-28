<?php
/**
 * Copyright 2013 Asim Liaquat
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Google\Contacts;

/**
 * Contacts Entry.
 *
 * @package    Google
 * @subpackage Contacts
 * @version    0.1
 * @author     Asim Liaquat <asimlqt22@gmail.com>
 */
class GroupEntry
{
    /**
     * Etag
     * @var string
     */
    private $etag;

    /**
     * $id
     * @var string
     */
    private $id;

    /**
     * $updated
     * @var DateTime
     */
    private $updated;

    /**
     * Category
     * @var array
     */
    private $category = array();

    /**
     * $links
     * @var array
     */
    private $links = array();

    /**
     * $title
     * @var string
     */
    private $title;

    /**
     * $content
     * @var string
     */
    private $content;

    /**
     * System group
     * @var string
     */
    private $systemGroup;

    /**
     * The xml entry for a single contact
     * 
     * @var array
     */
    private $entry;

    /**
     * Constructor
     * @param array $entry 
     */
    public function __construct($entry=null)
    {
        if(!is_null($entry)) {
            //var_dump($entry);exit;
            //return;
            $this->etag = $entry['gd$etag'];
            $this->id = $entry['id']['$t'];
            $this->updated = new \DateTime($entry['updated']['$t']);
            $this->category = $entry['category'];
            $this->title = $entry['title']['$t'];
            $this->content = $entry['content']['$t'];

            if(isset($entry['gContact$systemGroup'])) {
                $this->systemGroup = $entry['gContact$systemGroup'];
            }

            foreach($entry['link'] as $link) {
                $l = new Entry\Link();
                $l->setRel($link['rel'])
                    ->setType($link['type'])
                    ->setHref($link['href']);
                $this->links[] = $l;
            }
        }
    }

    /**
     * Get the etag
     * 
     * @return string
     */
    public function getEtag()
    {
        return $this->etag;
    }

    /**
     * Get the contact id
     * 
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }    

    /**
     * Get the time contact was updated
     * 
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Get title
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the title
     * 
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get links
     * 
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * THIS IS PROBABLY NOT REQUIRED
     * 
     * @param [type] $links [description]
     */
    public function setLinks($links)
    {
        $this->links = $links;
        return $this;
    }

    /**
     * Get content
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the name components
     * 
     * @param Entry\Name $name
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Save this contact
     *
     * If the id of this entry is not null then anpu update will be performed
     * otherwise a new contact will be created
     * 
     * @return
     */
    public function save()
    {
        $adapter = new EntryToXmlAdapter();
        $post = $adapter->adapt($this);
        //var_dump($post);
        //exit;

        $serviceRequest = ServiceRequestFactory::getInstance();
        $request = $serviceRequest->getRequest(); /* @var $request \Google\Contacts\Request */
        $headers = array(
            'Content-Type' => 'application/atom+xml; charset=UTF-8; type=feed',
        );

        if(!is_null($this->id)) {
            $request->setMethod('PUT');
            $request->setFullUrl($this->id);
            $headers['If-Match'] = $this->etag;
        } else {
            $request->setEndpoint("default/full");
            $request->setMethod('POST');
        }

        $request->setPost($post);
        $request->setHeaders($headers);
        $res = $serviceRequest->execute();
        //var_dump($res);
    }
}