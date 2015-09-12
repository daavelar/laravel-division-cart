<?php namespace NovoTempo\Cart\Session;

use Session;

class Cart
{
    public function division($division)
    {
        if(!$this->divisionExists($division)) {
            Session::push('divisions', $division);
        }

        return new CartDivision($division);
    }

    public function count()
    {
        $total = 0;

        if(! $this->divisionsWasInitialized()) return 0;

        foreach(Session::get('divisions') as $division) {
            $total += $this->division($division)->count();
        }

        return $total;
    }

    public function frete($value = null)
    {
        if(!is_null($value)) {
            Session::put('cart.frete', $value);
        }

        return Session::get('cart.frete');
    }

    public function destroy()
    {
        Session::flush();
    }

    public function total()
    {
        $total = 0;

        if(Session::has('divisions')) {
            foreach(Session::get('divisions') as $division) {
                $total += $this->division($division)->total();
            }
        }

        return $total;
    }

    public function isEmpty()
    {
        foreach(Session::get('divisions') as $division) {
            if(! $this->division($division)->isEmpty()) {
                return false;
            }
        }

        return true;
    }

    public function removeUnit($identifier)
    {
        foreach(Session::get('divisions') as $division) {
            $this->division($division)->removeUnit($identifier);
        }
    }

    private function divisionExists($division)
    {
        if(!$this->divisionsWasInitialized()) {
            return false;
        }

        return in_array($division, Session::get('divisions'));
    }

    public function divisionsWasInitialized()
    {
        if(Session::has('divisions')) {
            return true;
        }

        return false;
    }

    public function remove($identifier)
    {
        foreach(Session::get('divisions') as $division) {
            $this->division($division)->remove($identifier);
        }
    }

}