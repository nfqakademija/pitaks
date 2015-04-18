<?php

namespace pitaks\KickerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class pitaksKickerBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
