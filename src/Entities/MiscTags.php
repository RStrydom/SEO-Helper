<?php namespace Arcanedev\SeoHelper\Entities;

use Arcanedev\SeoHelper\Contracts\Entities\MetaCollectionInterface;
use Arcanedev\SeoHelper\Contracts\Entities\MiscTagsInterface;
use Arcanedev\Support\Traits\Configurable;

/**
 * Class     MiscTags
 *
 * @package  Arcanedev\SeoHelper\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MiscTags implements MiscTagsInterface
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
     * Current URL.
     *
     * @var string
     */
    protected $currentUrl = '';

    /**
     * Meta collection.
     *
     * @var MetaCollectionInterface
     */
    protected $metas;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make MiscTags instance.
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
     */
    private function init()
    {
        $this->addCanonical();
        $this->addRobotsMeta();
        $this->addMany($this->getConfig('default', []));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the current URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->currentUrl;
    }

    /**
     * Set the current URL.
     *
     * @param  string  $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->currentUrl = $url;
        $this->addCanonical();

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make MiscTags instance.
     *
     * @param  array  $defaults
     *
     * @return self
     */
    public static function make(array $defaults = [])
    {
        return new self([
            'default' => $defaults,
        ]);
    }

    /**
     * Add a meta tag.
     *
     * @param  string  $name
     * @param  string  $content
     *
     * @return self
     */
    public function add($name, $content)
    {
        $this->metas->add($name, $content);

        return $this;
    }

    /**
     * Add many meta tags.
     *
     * @param  array  $metas
     *
     * @return self
     */
    public function addMany(array $metas)
    {
        $this->metas->addMany($metas);

        return $this;
    }

    /**
     * Remove a meta from the meta collection by key.
     *
     * @param  array|string  $names
     *
     * @return self
     */
    public function remove($names)
    {
        $this->metas->remove($names);

        return $this;
    }

    /**
     * Reset the meta collection.
     *
     * @return self
     */
    public function reset()
    {
        $this->metas->reset();

        return $this;
    }

    /**
     * Render the tag.
     *
     * @return string
     */
    public function render()
    {
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
     * Check if has the current URL.
     *
     * @return bool
     */
    private function hasUrl()
    {
        return ! empty($this->getUrl());
    }

    /**
     * Check if canonical is enabled.
     *
     * @return bool
     */
    private function isCanonicalEnabled()
    {
        return (bool) $this->getConfig('canonical', false);
    }

    /**
     * Check if blocking robots is enabled.
     *
     * @return bool
     */
    private function isRobotsEnabled()
    {
        return (bool) $this->getConfig('robots', false);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add the robots meta.
     *
     * @return self
     */
    private function addRobotsMeta()
    {
        if ($this->isRobotsEnabled()) {
            $this->add('robots', 'noindex, nofollow');
        }

        return $this;
    }

    /**
     * Add the canonical link.
     *
     * @return self
     */
    private function addCanonical()
    {
        if ($this->isCanonicalEnabled() && $this->hasUrl()) {
            $this->add('canonical', $this->currentUrl);
        }
    }
}
