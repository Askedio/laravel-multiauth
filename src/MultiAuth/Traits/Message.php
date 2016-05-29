<?php

namespace Askedio\MultiAuth\Traits;

trait Message
{
    /**
     * [getMessage description].
     *
     * @return [type] [description]
     */
    protected function getMessage($message = null)
    {
        if (!empty($message)) {
            if (!isset($this->message[$message])) {
                return null;
            }

            return $this->message[$message];
        }

        return $this->message;
    }

    /**
     * [getToken description]
     * @return [type] [description]
     */
    protected function getToken()
    {
        return $this->message['oauth']['token'];
    }

    /**
     * [setSuccess description].
     *
     * @param [type] $user    [description]
     * @param [type] $message [description]
     * @param [type] $data    [description]
     */
    protected function setSuccess($user, $message, $data = [])
    {
        $this->message = array_filter(
            array_merge([
                'success' => $message,
                'user'    => $user,
            ], $data)
        );

        return $this;
    }

    /**
     * [hasRedirect description]
     * @return boolean [description]
     */
    protected function hasRedirect()
    {
        return isset($this->message['success']) && $this->message['success'] == 'redirect';
    }

    /**
     * [hasLink description]
     * @return boolean [description]
     */
    protected function hasEmailLink()
    {
        return isset($this->message['success']) && $this->message['success'] == 'emailed';
    }
}
