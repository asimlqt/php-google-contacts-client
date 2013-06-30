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
     * Extended property
     * @var array \Google\Contacts\Entry\ExtendedProperty
     */
    private $extendedProperty = array();

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
            $this->expandEntry($entry);
        }
    }

    /**
     * Extracts all the group information form the json response
     * and sets all the class properties.
     * 
     * @param array $entry
     * 
     * @return null
     */
    private function expandEntry($entry)
    {
        $this->etag = $entry['gd$etag'];
        $this->id = $entry['id']['$t'];
        $this->updated = new \DateTime($entry['updated']['$t']);
        $this->category = $entry['category'];
        $this->title = $entry['title']['$t'];
        $this->content = $entry['content']['$t'];

        if(isset($entry['gContact$systemGroup'])) {
            //$this->systemGroup = $entry['gContact$systemGroup'];
            $this->systemGroup = true;
        } else {
            $this->systemGroup = false;
        }

        foreach($entry['link'] as $link) {
            $l = new Entry\Link();
            $l->setRel($link['rel'])
                ->setType($link['type'])
                ->setHref($link['href']);
            $this->links[] = $l;
        }

        if(isset($entry['gd$extendedProperty'])) {
            foreach($entry['gd$extendedProperty'] as $prop) {
                $ep = new \Google\Contacts\Entry\ExtendedProperty();
                $ep->setName($prop['name']);
                $ep->setValue($prop['value']);
                $this->extendedProperty[] = $ep;
            }
        }
    }

    public function isSystemGroup()
    {
        return $this->systemGroup;
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
     * Get the etag
     * 
     * @return string
     */
    public function setEtag($etag)
    {
        $this->etag = $etag;
        return $this;
    }

    /**
     * Get the group id
     * 
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }    

    /**
     * Set the group id
     * 
     * @return string
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getIdPart()
    {
        if(!is_string($this->id))
            throw new Exception('id is not set');

        return substr($this->id, strrpos($this->id, '/')+1);
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

    public function getExtendedProperty()
    {
        return $this->extendedProperty;
    }
    
    public function setExtendedProperty($extendedProperty)
    {
        $this->extendedProperty = $extendedProperty;
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
        //$adapter = new EntryToXmlAdapter();
        //$post = $adapter->adapt($this);
        $post = $this->toXml();
        //var_dump($post);
        //exit;

        $serviceRequest = ServiceRequestFactory::getInstance();
        $request = $serviceRequest->getRequest(); /* @var $request \Google\Contacts\Request */
        $headers = array(
            'Content-Type' => 'application/atom+xml; charset=UTF-8; type=feed',
        );

        if(!is_null($this->id)) {
            //var_dump($post);
            $request->setMethod('PUT');
            $request->setEndpoint("default/full/".$this->getIdPart());
            $headers['If-Match'] = $this->etag;
        } else {
            $request->setEndpoint("default/full");
            $request->setMethod('POST');
        }

        $request->setFeedType('groups');
        $request->addQueryParam('alt', 'json');
        $request->setPost($post);
        $request->setHeaders($headers);
        $res = json_decode($serviceRequest->execute(), true);
        //var_dump(json_decode($res, true));exit;
        $this->expandEntry($res['entry'], true);
        //var_dump($res);
    }

    public function toXml()
    {
        if(!is_array($this->extendedProperty) || count($this->extendedProperty) === 0) {
            throw new Exception('extendedProperty not set');
        }

        $xml = new \XmlWriter();
        $xml->openMemory();

        $xml->startElementNS('atom', 'entry', 'http://www.w3.org/2005/Atom'); 
        $xml->writeAttribute('xmlns:gd', 'http://schemas.google.com/g/2005'); 

        if(!is_null($this->getId())) {
            $xml->writeAttribute('gd:etag', $this->getEtag()); 
            $xml->writeElement('id', $this->getId());
            $xml->writeElement('updated', date('c', time()));
        }

        $xml->startElement('atom:category');
        $xml->writeAttribute('scheme', 'http://schemas.google.com/g/2005#kind'); 
        $xml->writeAttribute('term', 'http://schemas.google.com/contact/2008#group');
        $xml->endElement();

        $xml->startElement('atom:title');
        $xml->writeAttribute('type', 'text');
        $xml->text($this->title);
        $xml->endElement();

        foreach($this->extendedProperty as $ep) {
            $xml->startElement('gd:extendedProperty');
            $xml->writeAttribute('name', $ep->getName());
            $xml->writeAttribute('value', $ep->getValue());
            $xml->endElement();
        }

        $xml->endElement();
        return $xml->outputMemory();
    }
}