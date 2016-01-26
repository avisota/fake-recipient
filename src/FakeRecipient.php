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
     * Get the recipient email address.
     *
     * @return string
     */
    public function getEmail()
    {
        $this->init();
        return $this->data['email'];
    }

    /**
     * Set the email address.
     *
     * @param $email
     *
     * @return void
     * @throws MutableRecipientDataException
     */
    public function setEmail($email)
    {
        $this->init();
        parent::setEmail($email);
    }

    /**
     * Check if this recipient has personal data.
     *
     * @return bool
     */
    public function hasDetails()
    {
        return true;
    }

    /**
     * Get a single personal data field value.
     * Return null if the field does not exists.
     *
     * @param string $name
     *
     * @return mixed
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
     * Set a personal data field.
     *
     * @param string $name  The name of the field.
     * @param mixed  $value The value of the field. A value of
     *                      <code>null</code> delete the field.
     *
     * @return void
     */
    public function set($name, $value)
    {
        $this->init();
        parent::set($name, $value);
    }

    /**
     * Get all personal data values as associative array.
     *
     * The personal data must have a key 'email', that contains the email address.
     * <pre>
     * array (
     *     'email' => '...',
     *     ...
     * )
     * </pre>
     *
     * @return array
     */
    public function getDetails()
    {
        $this->init();
        return $this->data;
    }

    /**
     * Set multiple personal data fields.
     *
     * @param array $details
     *
     * @return void
     */
    public function setDetails(array $details)
    {
        $this->init();
        parent::setDetails($details);
    }

    /**
     * Get all personal data keys.
     *
     * The keys must contain 'email'.
     * <pre>
     * array (
     *     'email',
     *     ...
     * )
     * </pre>
     *
     * @return array
     */
    public function getKeys()
    {
        $this->init();
        return array_keys($this->data);
    }
}
