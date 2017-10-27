<?php

namespace AppBundle\Service\Tokenizer;


interface TokenGeneratorInterface
{
    /** @return string */
    public function generate();
}