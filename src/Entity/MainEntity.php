<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

trait MainEntity
{
    public function getProps(){
        return get_object_vars($this);
    }

}