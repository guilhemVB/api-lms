<?php

namespace AppBundle\Service\Tokenizer;

class TokenGenerator implements TokenGeneratorInterface
{

    /** @return string */
    public function generate()
    {
        return md5(uniqid(time(), true));
    }
}