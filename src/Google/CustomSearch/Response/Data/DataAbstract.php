<?php

/**
 * Google_CustomSearch_Response_DataAbstract defines the common functionality
 * for storing and handling response data from the Google Custom Search API
 *
 * @author Stephen Melrose <me@stephenmelrose.co.uk>
 */
abstract class Google_CustomSearch_Response_DataAbstract
{
    // ------------------------------------------------------
    // Static methods
    // ------------------------------------------------------

    /**
     * Gets the passed property from the passed response data if present
     *
     * @param string $property
     * @param stdClass $responseData
     * @return integer|string
     */
    public static function getPropertyFromResponseData($property, stdClass $responseData)
    {
        if (isset($responseData->$property) &&
            !(is_string($responseData->$property) && strlen(trim($responseData->$property)) < 1))
        {
            return $responseData->$property;
        }

        return null;
    }

    // ------------------------------------------------------
    // Constructor
    // ------------------------------------------------------

    /**
     * Creates a new Google_CustomSearch_Response_DataAbstract
     *
     * @param stdClass $responseData
     */
    public function __construct(stdClass $responseData)
    {
        $this->parse($responseData);
    }

    // ------------------------------------------------------
    // Methods
    // ------------------------------------------------------

    /**
     * Parses the raw response data for validity and then into formatted data
     * 
     * @param stdClass $responseData
     */
    abstract protected function parse(stdClass $responseData);

    /**
     * Parses standard properties straight from the response data onto the object
     * 
     * @param stdClass $responseData
     * @param array $properties
     */
    protected function parseStandardProperties(stdClass $responseData, $properties)
    {
        foreach($properties as $property)
        {
            if (!property_exists($this, $property))
            {
                throw new InvalidArgumentException(sprintf('Property "%s" does not exist on response data object.', $property));
            }

            $this->$property = self::getPropertyFromResponseData($property, $responseData);
        }
    }

    /**
     * Converts the object's data into a key/value array
     *
     * @param boolean $deep
     * @return array
     */
    public function toArray($deep = false)
    {
        $data = get_object_vars($this);

        if ($deep)
        {
            foreach($data as $key => $value)
            {
                $data[$key] = $this->convertToArray($value);
            }
        }

        return $data;
    }

    /**
     * Utility method for deep conversion in toArray()
     *
     * @param boolean $data
     * @return mixed
     * @see Google_CustomSearch_Response_DataAbstract::toArray()
     */
    protected function convertToArray($data)
    {
        if (is_array($data))
        {
            foreach($data as $key => $value)
            {
                $data[$key] = $this->convertToArray($value);
            }
        }
        else if (is_object($data) && method_exists($data, 'toArray'))
        {
            $data = $data->toArray(true);
        }

        return $data;
    }
}