<?php


namespace Elsheref\Security\Algorithms;


class Transposition
{


    private array $domain = [];
    private array $keyword;

    /**
     * Transposition constructor.
     * @param string $keyword
     */
    public function __construct(string $keyword)
    {
        $this->keyword = str_split(strtoupper($keyword));
    }

    private function generateDomain(string $text){


        $domain = str_split($text);
        $keyLength = count($this->keyword);
        $textLength = strlen($text);
        $reminder = $textLength % $keyLength;
        $difference = $keyLength - $reminder;
        if ($difference !== $keyLength){
            $temp = range(chr(65) , chr(65 + $difference - 1));
            $domain = array_merge(str_split($text) , $temp);
        }

        $this->domain = $domain;

    }

    private function prepareText(string $text){
        if (empty($text))
            throw new \Exception('please enter valid text');

        return implode('',explode(' ',strtoupper($text)));
    }



    public function encrypt(string $plain , string $keyword = ''){
        if (!empty($keyword))
            $this->keyword = str_split(strtoupper($keyword));

        try {
            $plain = $this->prepareText($plain);
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }

        $this->generateDomain($plain);

        $totalCharactersInRow =  count($this->keyword);
        $numbersOfRows = count($this->domain) / $totalCharactersInRow;


        asort($this->keyword);

        $result = [];
        foreach ($this->keyword as $index => $key){
            $temp = [];
            for ($i = 0 ; $i < $numbersOfRows ; $i++){
                $position = ($totalCharactersInRow * $i) + $index;
                array_push($temp , $this->domain[$position]);
            }
            array_push($result , implode('' , $temp));
        }

        return implode('',$result);
    }

    public function decrypt(string $cipher , string $keyword = ''){

        if (!empty($keyword))
            $this->keyword = str_split(strtoupper($keyword));

        try {
            $cipher = $this->prepareText($cipher);
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }

        $this->domain = str_split($cipher);

        $totalCharactersInRow =  count($this->keyword);
        $numbersOfRows = count($this->domain) / $totalCharactersInRow;

        $result = [];
        $parts = str_split($cipher,3);

        asort($this->keyword);

        $counter = 0;
        foreach ($this->keyword as $index => $key){
            for ($i = 0 ; $i < $numbersOfRows ; $i++){
                $position = ($totalCharactersInRow * $i) + $index;
                $result[$position] = $parts[$counter][$i];
            }
            $counter++;
        }

        ksort($result);

        return implode('',$result);

    }
}