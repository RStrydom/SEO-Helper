<?php namespace Arcanedev\SeoHelper\Entities\Twitter;

use Arcanedev\SeoHelper\Contracts\Entities\TwitterCardInterface;
use Arcanedev\SeoHelper\Exceptions\InvalidTwitterCardException;
use Arcanedev\Support\Traits\Configurable;

/**
 * Class     Card
 *
 * @package  Arcanedev\SeoHelper\Entities\Twitter
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Card implements TwitterCardInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Traits
     | ------------------------------------------------------------------------------------------------
     */
    use Configurable;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Card type.
     *
     * @var string
     */
    protected $type   = 'summary';

    /**
     * Card meta collection.
     *
     * @var MetaCollection
     */
    protected $metas;

    /**
     * Card images.
     *
     * @var array
     */
    protected $images  = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make the twitter card instance.
     *
     * @param  array  $configs
     */
    public function __construct(array $configs = [])
    {
        $this->setConfigs($configs);
        $this->metas   = new MetaCollection;

        $this->init();
    }

    /**
     * Start the engine.
     *
     * @return self
     */
    private function init()
    {
        $this->setPrefix($this->getConfig('prefix', 'twitter:'));
        $this->setType($this->getConfig('card', ''));
        $this->setSite($this->getConfig('site', ''));
        $this->setTitle($this->getConfig('title', ''));
        $this->addMetas($this->getConfig('metas', []));

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set meta prefix name.
     *
     * @param  string  $prefix
     *
     * @return self
     */
    private function setPrefix($prefix)
    {
        $this->metas->setPrefix($prefix);

        return $this;
    }

    /**
     *
     * @param  string  $type
     *
     * @return self
     */
    public function setType($type)
    {
        if ( ! empty($type)) {
            $this->checkType($type);

            $this->type = $type;
            $this->addMeta('card', $type);
        }

        return $this;
    }

    /**
     * Set card site.
     *
     * @param  string  $site
     *
     * @return self
     */
    public function setSite($site)
    {
        if ( ! empty($site)) {
            $this->checkSite($site);
            $this->addMeta('site', $site);
        }

        return $this;
    }

    /**
     * Set card title.
     *
     * @param  string  $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->addMeta('title', $title);

        return $this;
    }

    /**
     * Set card description.
     *
     * @param  string  $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->addMeta('description', $description);

        return $this;
    }

    /**
     * Add image to the card.
     *
     * @param  string  $url
     *
     * @return self
     */
    public function addImage($url)
    {
        if (count($this->images) < 4) {
            $this->images[] = $url;
        }

        return $this;
    }

    /**
     * Add many metas to the card.
     *
     * @param  array  $metas
     *
     * @return self
     */
    public function addMetas(array $metas)
    {
        foreach ($metas as $name => $content) {
            $this->addMeta($name, $content);
        }

        return $this;
    }

    /**
     * Add a meta to the card.
     *
     * @param  string  $name
     * @param  string  $content
     *
     * @return self
     */
    public function addMeta($name, $content)
    {
        $this->metas->add($name, $content);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Render card images.
     */
    private function loadImages()
    {
        if (count($this->images) == 1) {
            $this->addMeta('image', $this->images[0]);

            return;
        }

        foreach ($this->images as $number => $url) {
            $this->addMeta("image{$number}", $url);
        }
    }

    /**
     * Reset the card.
     *
     * @return self
     */
    public function reset()
    {
        $this->metas->reset();
        $this->images = [];

        return $this->init();
    }

    /**
     * Render the twitter card.
     *
     * @return string
     */
    public function render()
    {
        if ( ! empty($this->images)) {
            $this->loadImages();
        }

        return $this->metas->render();
    }

    /**
     * Render the tag.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if type is supported.
     *
     * @param  string  $type
     *
     * @return bool
     */
    private function isSupportedType($type)
    {
        return in_array($type, [
            'app', 'gallery', 'photo', 'player', 'product', 'summary', 'summary_large_image'
        ]);
    }

    /**
     * Check the card type.
     *
     * @param  string  $type
     *
     * @throws \Arcanedev\SeoHelper\Exceptions\InvalidTwitterCardException
     */
    private function checkType(&$type)
    {
        if ( ! is_string($type)) {
            throw new InvalidTwitterCardException(
                'The Twitter card type must be a string value, [' . gettype($type) . '] was given.'
            );
        }

        $type = strtolower(trim($type));

        if ( ! $this->isSupportedType($type)) {
            throw new InvalidTwitterCardException(
                "The Twitter card type [$type] is not supported."
            );
        }
    }

    /**
     * Check the card site.
     *
     * @param  string  $site
     */
    private function checkSite(&$site)
    {
        $site = $this->prepareUsername($site);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Prepare username.
     *
     * @param  string  $username
     *
     * @return string
     */
    private function prepareUsername($username)
    {
        if ( ! starts_with($username, '@')) {
            $username = '@' . $username;
        }

        return $username;
    }
}
