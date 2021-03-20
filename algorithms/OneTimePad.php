<?php


namespace Elsheref\Security\Algorithms;


class OneTimePad
{

    private array $keyword = [];


    /**
     * OneTimePad constructor.
     * @param string $keyword
     */
    public function __construct(string $keyword = '')
    {
        $this->keyword = $this->prepareKey($keyword);
    }

    private function prepareKey(string $keyword){
        $result = [];

        foreach(range(0,strlen($keyword) - 1) as $index){
            array_push($result , decbin(ord($keyword[$index])));
        }
        return $result;
    }
    public function generateKey(int $length){

        $result = [];

        foreach(range(1,$length) as $index){
            array_push($result , decbin(ord(chr(rand(0,130)))));
        }

        return $result;
    }

    private function prepareText(string $text){
        if (empty($text))
            throw new \Exception('please enter valid text');

        return $text;
    }


    public function encrypt(string $plain , string $keyword = ''){

        if (!empty($keyword))
         $this->prepareKey($keyword);


        try {
            $plain = $this->prepareText($plain);
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }



        $plain_text = [];
        foreach (str_split($plain) as $char){
            array_push($plain_text , decbin(ord($char)));
        }

        $result = [];

        if (count($plain_text) > count($this->keyword)){
            $temp = array_fill(count($this->keyword) , count($plain_text) - count($this->keyword),'0');
            $this->keyword = array_merge($this->keyword , $temp);

        }elseif (count($plain_text) < count($this->keyword)){
            $temp = array_fill(count($plain_text) , count($this->keyword) - count($plain_text),'0');
            $plain_text = array_merge($plain_text , $temp);
        }


        foreach ($plain_text as $index => $item){
            $this->keyword[$index] = str_pad($this->keyword[$index], 8 , '0' , STR_PAD_LEFT);
            $plain_text[$index] = str_pad($plain_text[$index], 8 , '0' , STR_PAD_LEFT);



            $tmp = '';
            foreach (str_split($plain_text[$index]) as $subIndex => $bit){
                $tmp.= (bool) $this->keyword[$index][$subIndex] ^ (bool) $bit;
            }

            array_push($result , bindec($tmp));
        }

        return implode(' ',$result);
//          return $result;
    }

    public function decrypt(string $cipher , string $keyword = ''){
        return $this->encrypt($cipher,$keyword);
    }
}