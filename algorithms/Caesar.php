<?php

namespace Elsheref\Security\Algorithms;

class Caesar
{

    const SMALL = 'small_only';
    const CAPITAL = 'with_capital';

    private int $key;
    private bool $supportSpace = false;
    private bool $supportCapital = false;
    private array $domain = [];

    /**
     * Caesar constructor.
     * @param int $key
     * @param bool $capital
     * @param bool $space
     */
    public function __construct(int $key , bool $capital = false , bool $space = false)
    {
        $this->key = $key;
        $this->supportSpace = $space;
        $this->supportCapital = $capital;
        $this->generateDomain($capital ? self::CAPITAL : self::SMALL , $space);

    }

    private function generateDomain(string $domain , bool $space = false){

        switch ($domain){
            case self::SMALL:
                $this->domain  = array_merge($this->domain , range('a','z'));
                break;
            case self::CAPITAL:
                $capital = range('A','Z');
                $small = range('a','z');
                $this->domain = array_merge($capital,$small);
                break;
            default:
                $this->domain  = array_merge($this->domain , range('a','z'));
        }

        if ($space)
            array_unshift($this->domain , ' ');

        $this->domain = array_flip($this->domain);

    }

    private function prepareText(string $text){
        if (empty($text))
            throw new \Exception('please enter valid text');

        if (!$this->supportSpace){
            $text = implode('',explode(' ',$text));
        }
        if (!$this->supportCapital)
            $text = strtolower($text);

        return $text;
    }

    public function encrypt(string $plain , string $keyword = ''){

        if (!empty($keyword))
            $this->keyword = $keyword;

        $result = [];
        try {
            $plain = $this->prepareText($plain);
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }

        foreach (str_split($plain) as $index => $char){
            $cipher = ($this->domain[$char] + $this->key) % count($this->domain);
            $cipher = array_flip($this->domain)[$cipher];
            array_push($result,$cipher);
        }
        return implode('',$result);
    }

    public function decrypt(string $cipher , string $keyword = ''){

        if (!empty($keyword))
            $this->keyword = $keyword;

        $result = [];

        try {
            $cipher = $this->prepareText($cipher);
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }
        foreach (str_split($cipher) as $index => $char){
            $plain = ($this->domain[$char] - $this->key) % count($this->domain);
            if ($plain < 0 )
                $plain = $plain + (ceil($plain / count($this->domain))) * count($this->domain);

            $plain = array_flip($this->domain)[$plain];
            array_push($result,$plain);
        }
        return implode('',$result);
    }
}