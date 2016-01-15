<?php

/**
 * Avisota newsletter and mailing system
 *
 * PHP Version 5.3
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/recipient-fake
 * @license    LGPL-3.0+
 * @link       http://avisota.org
 */

namespace Avisota\Recipient\Fake;

use Avisota\Recipient\MutableRecipient;
use Avisota\Recipient\RecipientInterface;
use Faker\Factory;

/**
 * A fake recipient object.
 */
class FakeRecipient extends MutableRecipient
{

    /**
     * @var null
     */
    protected $locale = null;

    /**
     * The generators seed.
     *
     * @var null|string
     */
    protected $seed = null;

    /**
     * The fake data.
     *
     * @var array
     */
    protected $data = null;

    /**
     * @param null $locale
     * @param null $seed
     *
     * @internal param $email
     * @internal param array $details
     */
    public function __construct($locale = null, $seed = null)
    {
        $this->locale = $locale;
        $this->seed   = $seed;
    }

    protected function init()
    {
        if ($this->data !== null) {
            return;
        }

        $faker = Factory::create($this->locale ?: Factory::DEFAULT_LOCALE);

        if ($this->seed !== null) {
            $faker->seed($this->seed);
        }

        $gender = rand(0, 1) ? 'male' : 'female';

        $this->data = array(
            'email'    => $faker->email,
            'forename' => $faker->format('firstName', array($gender)),
            'surname'  => $faker->format('lastName', array($gender)),
            'gender'   => $gender,
            'company'  => $faker->company,
            'street'   => $faker->streetAddress,
            'postal'   => $faker->postcode,
            'city'     => $faker->city,
            'country'  => $faker->country,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        $this->init();
        return $this->data['email'];
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->init();
        parent::setEmail($email);
    }

    /**
     * {@inheritdoc}
     */
    public function hasDetails()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        $this->init();
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $this->init();
        parent::set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getDetails()
    {
        $this->init();
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setDetails(array $details)
    {
        $this->init();
        parent::setDetails($details);
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys()
    {
        $this->init();
        return array_keys($this->data);
    }
}
