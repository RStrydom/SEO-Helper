<?php namespace Arcanedev\SeoHelper\Contracts\Entities;

use Arcanedev\SeoHelper\Contracts\Renderable;

/**
 * Interface  OpenGraphInterface
 *
 * @package   Arcanedev\SeoHelper\Contracts\Entities
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface OpenGraphInterface extends Renderable
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the open graph prefix.
     *
     * @param  string  $prefix
     *
     * @return self
     */
    public function setPrefix($prefix);

    /**
     * Set type property.
     *
     * @param  string  $type
     *
     * @return self
     */
    public function setType($type);

    /**
     * Set title property.
     *
     * @param  string  $title
     *
     * @return self
     */
    public function setTitle($title);

    /**
     * Set description property.
     *
     * @param  string  $description
     *
     * @return self
     */
    public function setDescription($description);

    /**
     * Set url property.
     *
     * @param  string  $url
     *
     * @return self
     */
    public function setUrl($url);

    /**
     * Set image property.
     *
     * @param  string  $image
     *
     * @return self
     */
    public function setImage($image);

    /**
     * Add an open graph property.
     *
     * @param  string  $property
     * @param  string  $content
     *
     * @return self
     */
    public function addProperty($property, $content);
}