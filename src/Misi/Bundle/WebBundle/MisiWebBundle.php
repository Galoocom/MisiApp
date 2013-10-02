<?php

namespace Misi\Bundle\WebBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MisiWebBundle extends Bundle
{
    public function getParent() {
        return 'SyliusWebBundle';
    }
}
