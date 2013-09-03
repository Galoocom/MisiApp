<?php

namespace Misi\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MisiUserBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }    
}
