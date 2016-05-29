<?php

namespace App\MultiAuth\Traits;

use Illuminate\Http\Request;

trait Response
{
    /**
     * [withResponse description]
     * @return [type] [description]
     */
    public function withResponse()
    {
        if ($this->isAnApi()) {
            return $this->getMessage();
        }

        if ($this->hasErrors()) {
            return $this->redirectWithErrors($this->getErrors());
        }

        if ($this->hasEmailLink()) {
            return $this->redirectWithErrors(['emailed' => $this->getToken()]);
        }

        if ($this->hasRedirect()) {
            return redirect($this->getMessage('url'));
        }

        return redirect($this->redirectTo)->with($this->getMessage());
    }

    /**
     * [redirectWithErrors description]
     * @param  [type] $errors [description]
     * @return [type]         [description]
     */
    protected function redirectWithErrors($errors)
    {
          return redirect($this->request->session()->get('redirectToOnError', config('multiauth.redirectToOnError')))
                ->withInput()
                ->withErrors($errors);
    }
}
