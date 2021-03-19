<?php


namespace Elsheref\Security\Algorithms;


class FullViginer
{

    private array $domain = [];
    private string $keyword;
    private array $keyRange;

    /**
     * FullViginer constructor.
     * @param string $keyword
     */
    public function __construct(string $keyword = '')
    {
        $this->keyword = $keyword;
        $this->keyRange = array_flip(range('A','Z'));
        $this->generateDomain();
    }

    private function generateDomain(){

        $domain = [];
        foreach (range('A','Z') as $char){
            $domain[$char] = [];
            $start =  ord($char);
            $domain[$char] = range($char,'Z');
            if ($start > 65){
                $difference = $start - 65;
                $domain[$char] = array_merge($domain[$char] , range('A',chr(65 + $difference - 1)));
            }
        }

        $this->domain = $domain;

    }

    private function prepareText(string $text){
        if (empty($text))
            throw new \Exception('please enter valid text');

        return strtoupper($text);
    }


    private function prepareKeyWord(int $length){
        if (empty($this->keyword))
            throw new \Exception('please enter valid keyword');

        $this->keyword = strtoupper($this->keyword);

        if (strlen($this->keyword) < $length){
            while (strlen($this->keyword) < $length)
                $this->keyword = $this->keyword . substr($this->keyword , 0 , $length - strlen($this->keyword));
        }elseif (strlen($this->keyword) > $length){
            $this->keyword = substr($this->keyword , 0 , $length);
        }
    }



    public function encrypt(string $plain , string $keyword = ''){
       if (!empty($keyword))
           $this->keyword = $keyword;

        $result = [];
        try {
            $plain = $this->prepareText($plain);
            $this->prepareKeyWord(strlen($plain));
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }


        foreach (str_split($plain) as $index => $char){
            $list = $this->domain[$char];
            $keyIndex = $this->keyRange[$this->keyword[$index]];
            array_push($result,$list[$keyIndex]);
        }
        return implode('',$result);
    }

    public function decrypt(string $cipher , string $keyword = ''){

        $list = [];
        foreach (range(0,25) as $parent){
            $list[$parent] = [];
            foreach (range(0,25) as $child){
                array_push($list[$parent] , $this->domain[chr($child + 65)][$parent]);
            }
        }


        if (!empty($keyword))
            $this->keyword = $keyword;

        $result = [];
        try {
            $cipher = $this->prepareText($cipher);
            $this->prepareKeyWord(strlen($cipher));
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }


        foreach (str_split($cipher) as $index => $char){
            $keyIndex = $this->keyRange[$this->keyword[$index]];
            $parent = array_flip($list[$keyIndex])[$char];
            $parent = chr($parent + 65);
            array_push($result , $parent);
        }
        return implode('',$result);
    }
}