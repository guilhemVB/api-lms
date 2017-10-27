<?php

namespace AppBundle\Service\Tokenizer;

class TokenGeneratorMock implements TokenGeneratorInterface
{

    /** @return string */
    public function generate()
    {
        return "TOKEN_MOCK";
    }
}