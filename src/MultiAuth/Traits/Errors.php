<?php

namespace Askedio\MultiAuth\Traits;

use Illuminate\Http\Request;

trait Errors
{

    /**
     * [$errors description]
     * @var [type]
     */
    protected $errors;

    /**
     * [setError description]
     * @param [type] $error [description]
     */
    protected function setError($error)
    {
        $this->errors = $error;

        return $this;
    }

    /**
     * [hasErrors description]
     * @return boolean [description]
     */
    protected function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * [getErrors description]
     * @return [type] [description]
     */
    protected function getErrors()
    {
        return $this->errors;
    }


    /**
     * [authenticationError description].
     *
     * @param [type] $error [description]
     *
     * @return [type] [description]
     */
    protected function authenticationError($error)
    {
        return ['authentication' => $error];
    }

    /**
     * [validationErrors description].
     *
     * @param [type] $request [description]
     *
     * @return [type] [description]
     */
    protected function hasValidationErrors()
    {
        $validator = $this->validator($this->request->all());

        if ($validator->fails()) {
            $this->setError($validator->errors());

            return true;
        }

        return false;
    }
}
