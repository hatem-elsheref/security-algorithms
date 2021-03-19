<?php


namespace Elsheref\Security\Algorithms;


class PolyalphabeticSubstitution
{

    const SMALL = 'small_only';
    const CAPITAL = 'with_capital';

    private string $keyword;
    private bool $supportSpace = false;
    private bool $supportCapital = false;
    private array $domain = [];


    /**
     * PolyalphabeticSubstitution constructor.
     * @param string $keyword
     * @param bool $capital
     * @param bool $space
     */
    public function __construct(string $keyword , bool $capital = false , bool $space = false)
    {
        $this->keyword = $keyword;
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

    private function prepareKeyWord(int $length){
        if (empty($this->keyword))
            throw new \Exception('please enter valid keyword');

        if (!$this->supportSpace){
            $this->keyword = implode('',explode(' ',$this->keyword));
        }
        if (!$this->supportCapital)
            $this->keyword = strtolower($this->keyword);

        if (strlen($this->keyword) < $length){
            while (strlen($this->keyword) < $length)
            $this->keyword = $this->keyword . substr($this->keyword , 0 , $length - strlen($this->keyword));
        }elseif (strlen($this->keyword) > $length){
            $this->keyword = substr($this->keyword , 0 , $length);
        }
    }

    public function encrypt(string $plain){
        $result = [];

        try {
            $plain = $this->prepareText($plain);
            $this->prepareKeyWord(strlen($plain));
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }


        foreach (str_split($plain) as $index => $char){
            $cipher = ($this->domain[$char] + $this->domain[$this->keyword[$index]]) % count($this->domain);
            $cipher = array_flip($this->domain)[$cipher];
            array_push($result,$cipher);
        }
        return implode('',$result);
    }

    public function decrypt(string $cipher){
        $result = [];

        try {
            $cipher = $this->prepareText($cipher);
            $this->prepareKeyWord(strlen($cipher));
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }

        foreach (str_split($cipher) as $index => $char){
            $plain = ($this->domain[$char] - $this->domain[$this->keyword[$index]]) % count($this->domain);
            if ($plain < 0 )
                $plain = $plain + (ceil(abs($plain / count($this->domain)))) * count($this->domain);

            $plain = array_flip($this->domain)[$plain];
            array_push($result,$plain);
        }
        return implode('',$result);
    }
}