<?php
namespace common\web\exceptions;

use yii\base\UserException;

/**
 * custom exception
 */
class CommonException extends UserException
{
    public $name = '';
    /**
     * Constructor.
     * @param string $message error message
     * @param integer $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($name, $message = null, $code = 0, \Exception $previous = null)
    {
        $this->name = $name;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return $this->name;
    }
}
