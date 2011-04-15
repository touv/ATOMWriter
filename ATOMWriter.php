<?php
// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 fdm=marker encoding=utf8 :
/**
 * P3C
 *
 * Copyright (c) 2010, Nicolas Thouvenin
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the author nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE REGENTS AND CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  ATOMWriter
 * @package   ATOMWriter
 * @author    Nicolas Thouvenin <nthouvenin@gmail.com>
 * @copyright 2010 Nicolas Thouvenin
 * @license   http://opensource.org/licenses/bsd-license.php BSD Licence
 */


/**
 * Atom Response Class
 *
 * @category  ATOMWriter
 * @package   ATOMWriter
 * @author    Nicolas Thouvenin <nthouvenin@gmail.com>
 * @copyright 2010 Nicolas Thouvenin
 * @license   http://opensource.org/licenses/bsd-license.php BSD Licence
 */
class ATOMWriter
{
    /**
     * @const string
     */
    const NS_ATOM = 'http://www.w3.org/2005/Atom';
    /**
     * @const string
     */
    const NS_OSH  = 'http://a9.com/-/spec/opensearch/1.1/';
    /**
     * @var XMLWriter
     */
    protected $writer;
    /**
     * @var boolean
     */
    protected $namespaces = true;

    // {{{ __construct
    /**
     * Constructeur
     *
     * @param array $config
     */
    public function __construct($w, $ns = true)
    {
        $this->writer = $w;
        $this->namespaces = $ns;
    }
    // }}}
    // {{{ flush
    /**
     * flush the writer
     */
    public function flush()
    {
        $this->writer->flush();
        return $this;
    }
    // }}}
    // {{{ startFeed
    /**
     * startFeed
     * @param 
     */
    function startFeed($id, $updated = null, $created = null) 
    {
        $this->writer->startElement('feed');
        if ($this->namespaces) {
            $this->writer->writeAttribute('xmlns', self::NS_ATOM);
            $this->writer->writeAttributeNS('xmlns', 'opensearch', null, self::NS_OSH);
        }
        $this->writer->writeElement('id', $id);
        if (!is_null($created))
            $this->writer->writeElement('created', date(DATE_ATOM, $created));
        $this->writer->writeElement('updated', date(DATE_ATOM, is_null($updated) ? time() : $updated));
        return $this;
    }
    // }}}
    // {{{ writeTitle
    /**
     * Ajout du titre
     *
     * @param string
     * @param string
     */
    function writeTitle($value, $type = 'text', $lang = null)
    {
        $this->writer->startElement('title');
        $this->writer->writeAttribute('type', $type);
        if (!is_null($lang))
            $this->writer->writeAttributeNS('xml', 'lang', null, $lang);
        $this->writer->text($value);
        $this->writer->endElement();
        return $this;
    }
    // }}}
    // {{{ writeLink
    /**
     * Ajout d'un lien
     *
     * @param string
     * @param string
     */
    function writeLink($value, $type = 'text/html', $rel = null)
    {
        $this->writer->startElement('link');
        $this->writer->writeAttribute('type', $type);
        $this->writer->writeAttribute('href', $value);
        if (!is_null($rel))
            $this->writer->writeAttribute('rel', $rel);
        $this->writer->endElement();
        return $this;
    }
    // }}}
    // {{{ writeAuthor
    /**
     * Ajout d'un author
     *
     * @param string
     * @param string
     */
    function writeAuthor($name, $email = null, $uri = null)
    {
        $this->writer->startElement('author');
        if (!is_null($name))
            $this->writer->writeElement('name', $name);
        if (!is_null($email))
            $this->writer->writeElement('email', $email);
        if (!is_null($uri))
            $this->writer->writeElement('uri', $uri);
        $this->writer->endElement();
        return $this;
    }
    // }}}
    // {{{ writeAuthorRAW
    /**
     * Ajout d'un author
     *
     * @param string
     * @param string
     */
    function writeAuthorRAW($string)
    {
        if ( ($a = strpos($string, '@')) !== false) {
            $email = $string; // et si on avait truc bidule <bidule@exemple.fr>
            $author = ucwords(preg_replace(',[^\w]+,', ' ',substr($string, 0, $a)));
        }
        else {
            $email = null;
            $author = $string;
        }
        return $this->writeAuthor($author, $email);
    }
    // }}}
    // {{{ writeContributor
    /**
     * Ajout d'un contributeur
     *
     * @param string
     * @param string
     */
    function writeContributor($name, $email = null, $uri = null)
    {
        $this->writer->startElement('contributor');
        if (!is_null($name))
            $this->writer->writeElement('name', $name);
        if (!is_null($email))
            $this->writer->writeElement('email', $email);
        if (!is_null($uri))
            $this->writer->writeElement('uri', $uri);
        $this->writer->endElement();
        return $this;
    }
    // }}}
    // {{{ writeCategory
    /**
     * Ajout d'une categorie
     *
     * @param string
     * @param string
     * @param string
     */
    function writeCategory($term, $scheme = null, $label = null)
    {
        $this->writer->startElement('category');
        if (!is_null($term))
            $this->writer->writeAttribute('term', $term);
        if (!is_null($scheme))
            $this->writer->writeAttribute('scheme', $scheme);
        if (!is_null($label))
            $this->writer->writeAttribute('label', $label);
        $this->writer->endElement();
        return $this;
    }
    // }}}
    // {{{ startEntry
    /**
     * Ajout d'une entrée
     *
     * @param string
     * @param string
     * @param string
     */
    function startEntry($id, $updated = null, $created = null)
    {
        if (is_null($created)) $created = time();

        $this->writer->startElement('entry');
        $this->writer->writeElement('id', $id);
        $this->writer->writeElement('published', date(DATE_ATOM, $updated));
        $this->writer->writeElement('updated', date(DATE_ATOM, is_null($updated) ? $created : $updated));
        return $this;
    }
    // }}}
    // {{{ writeContent
    /**
     * Ajout d'un contenu
     *
     * @param string
     * @param string
     */
    function writeContent($value, $type = null, $lang = null)
    {
        $this->writer->startElement('content');
        if (!is_null($type))
            $this->writer->writeAttribute('type', $type);
        if (!is_null($lang))
            $this->writer->writeAttributeNS('xml', 'lang', null, $lang);
        $this->writer->text($value);
        $this->writer->endElement();
        return $this;
    }
    // }}}
    // {{{ endEntry
    /**
     * Fin d'une entrée
     *
     */
    function endEntry()
    {
        $this->writer->endElement();
    }
    // }}}
    // {{{ endFeed
    /**
     * Fin d'un flux
     *
     */
    function endFeed()
    {
        $this->writer->endElement();
    }
    // }}}
    // {{{ writeSearch
    /**
     * 
     * @param
     * @return boolean
     */
    public function writeSearch($url)
    {
        $this->writeLink($url, 'application/opensearchdescription+xml', 'search');
        return $this;
    }
    // }}}
    // {{{  writeTotalResults
    /**
     * OpenSearch TotalResults
     *
     * @param string
     */
    public function writeTotalResults($value)
    {
        $this->writer->startElementNS('opensearch', 'totalResults', null);
        $this->writer->text((string) ($value));
        $this->writer->endElement();
        return $this;
    }
    // }}}
    // {{{ writeStartIndex
    /**
     * OpenSearch startIndex
     *
     * @param string
     */
    public function writeStartIndex($value)
    {
        $this->writer->startElementNS('opensearch', 'startIndex', null);
        $this->writer->text((string) (is_null($value) ? 0 : $value));
        $this->writer->endElement();
        return $this;
    }
    // }}}
    // {{{  writeItemsPerPage
    /**
     * OpenSearch itemsPerPage
     *
     * @param string
     */
    public function writeItemsPerPage($value)
    {
        $this->writer->startElementNS('opensearch', 'itemsPerPage', null);
        $this->writer->text((string) (is_null($value) ? 20 : $value));
        $this->writer->endElement();
        return $this;
    }
    // }}}
    // {{{  writeQuery
    /**
     * OpenSearch Query
     *
     * @param string
     */
    public function writeQuery($searchTerms, $startPage = 1, $role = 'request')
    {
        $this->writer->startElementNS('opensearch', 'Query', null);
        $this->writer->startAttributeNS('opensearch', 'searchTerms', null);
        $this->writer->text($searchTerms);
        $this->writer->endAttribute();
        $this->writer->startAttributeNS('opensearch', 'startPage', null);
        $this->writer->text((string)$startPage);
        $this->writer->endAttribute();
        $this->writer->startAttributeNS('opensearch', 'role', null);
        $this->writer->text($role);
        $this->writer->endAttribute();
        $this->writer->endElement();
        return $this;
    }
    // }}}
    // {{{ writeGenerator
    /**
     * Ajout d'un generator
     *
     * @param string
     * @param string
     */
    function writeGenerator($value, $version = null, $uri = null)
    {
        $this->writer->startElement('generator');
        if (!is_null($generator))
            $this->writer->text($value);
        if (!is_null($version))
            $this->writer->writeAttribute('version', $version);
        if (!is_null($uri))
            $this->writer->writeAttribute('uri', $uri);
        $this->writer->endElement();
        return $this;
    }
    // }}}
}
