<?php


namespace Elsheref\Security\Algorithms;


class PlayFair
{

    private array $domain = [];
    private array $keyword;

    /**
     * PlayFair constructor.
     * @param string $keyword
     */
    public function __construct(string $keyword)
    {

        $this->keyword = str_split(str_replace('J','I',strtoupper($keyword)));
        $this->keyword = array_unique($this->keyword);
        $this->generateDomain();

    }

    private function generateDomain(){

        $allChars = range('A','Z');
        $this->domain = $this->keyword;
        $theRest = array_flip(array_diff($allChars , $this->keyword));
        unset($theRest['J']);
        $theRest = array_flip($theRest);
        $this->domain = array_merge($this->domain , $theRest);

    }

    private function prepareText(string $text){
        if (empty($text))
            throw new \Exception('please enter valid text');

        $text = implode('',explode(' ',strtoupper($text)));
        $result = [];

        $data = str_split($text);

        $tmp = str_split($text);
        for ($i = 0 ; $i < count($data) - 1 ; $i ++){
            if (ord($data[$i]) === ord($data[$i+1])){
                array_push($result , $data[$i]);
                array_push($result , 'X');
                unset($tmp[$i]);
            }else{
                array_push($result , $data[$i]);
                array_push($result , $data[$i + 1]);
                unset($tmp[$i]);
                unset($tmp[$i + 1]);
                $i++;
            }
        }

        if (!empty($tmp)){
            array_push($result , array_pop($tmp));
            array_push($result , 'X');
        }


        return str_split( implode('' , $result) , 2);
    }



    public function encrypt(string $plain , string $keyword = ''){


        if (!empty($keyword)){
            $this->keyword = str_split(str_replace('J','I',strtoupper($keyword)));
            $this->keyword = array_unique($this->keyword);
            $this->generateDomain();
        }


        try {
            $plain = $this->prepareText($plain);
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }

        $result = [];
        $rows = [];
        $columns = [];

        for ($i = 0 ; $i < 5 ; $i++){
            $rows[$i] = array_slice($this->domain , $i * 5 , 5);
            $columns[$i] = [];
            for ($j = 0 ; $j < 5 ; $j++){
              array_push($columns[$i] , $this->domain[($j * 5 ) + $i]);
            }
        }

        $array_flip = array_flip($this->domain);
        foreach ($plain as $block){
            $row_of_first  = floor($array_flip[$block[0]] / 5);
            $row_of_second = floor($array_flip[$block[1]] / 5);
            $column_of_first  = $array_flip[$block[0]] % 5;
            $column_of_second = $array_flip[$block[1]] % 5;

            if ($row_of_first !== $row_of_second && $column_of_first !== $column_of_second){

                $cipher_of_first_char  = $rows[$row_of_first][$column_of_second];
                $cipher_of_second_char = $rows[$row_of_second][$column_of_first];

                array_push($result ,$cipher_of_first_char);
                array_push($result ,$cipher_of_second_char);


                continue;
            }


            if ($row_of_first === $row_of_second){
                $current_list = array_flip($rows[$row_of_first]);
            }elseif ($column_of_first === $column_of_second){
                $current_list = array_flip($columns[$column_of_first]);
            }

            $position_of_first_char = $current_list[$block[0]];
            $position_of_second_char = $current_list[$block[1]];

            if ($position_of_first_char < 4){
                $position_of_first_char = $position_of_first_char + 1;
            }else{
                $position_of_first_char = 0;
            }

            if ($position_of_second_char < 4){
                $position_of_second_char = $position_of_second_char + 1;
            }else{
                $position_of_second_char = 0;
            }



            array_push($result , array_flip($current_list)[$position_of_first_char]);
            array_push($result , array_flip($current_list)[$position_of_second_char]);


        }

        return implode('',$result);
    }

    public function decrypt(string $cipher , string $keyword = ''){

        if (!empty($keyword)){
            $this->keyword = str_split(str_replace('J','I',strtoupper($keyword)));
            $this->keyword = array_unique($this->keyword);
            $this->generateDomain();
        }


        try {
            $cipher = $this->prepareText($cipher);
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }

        $result = [];
        $rows = [];
        $columns = [];

        for ($i = 0 ; $i < 5 ; $i++){
            $rows[$i] = array_slice($this->domain , $i * 5 , 5);
            $columns[$i] = [];
            for ($j = 0 ; $j < 5 ; $j++){
                array_push($columns[$i] , $this->domain[($j * 5 ) + $i]);
            }
        }

        $array_flip = array_flip($this->domain);
        foreach ($cipher as $block){
            $row_of_first  = floor($array_flip[$block[0]] / 5);
            $row_of_second = floor($array_flip[$block[1]] / 5);
            $column_of_first  = $array_flip[$block[0]] % 5;
            $column_of_second = $array_flip[$block[1]] % 5;

            if ($row_of_first !== $row_of_second && $column_of_first !== $column_of_second){

                $cipher_of_first_char  = $rows[$row_of_first][$column_of_second];
                $cipher_of_second_char = $rows[$row_of_second][$column_of_first];

                array_push($result ,$cipher_of_first_char);
                array_push($result ,$cipher_of_second_char);


                continue;
            }


            if ($row_of_first === $row_of_second){
                $current_list = array_flip($rows[$row_of_first]);
            }elseif ($column_of_first === $column_of_second){
                $current_list = array_flip($columns[$column_of_first]);
            }

            $position_of_first_char = $current_list[$block[0]];
            $position_of_second_char = $current_list[$block[1]];

            if ($position_of_first_char > 0){
                $position_of_first_char = $position_of_first_char - 1;
            }else{
                $position_of_first_char = 4;
            }

            if ($position_of_second_char > 0){
                $position_of_second_char = $position_of_second_char - 1;
            }else{
                $position_of_second_char = 4;
            }



            array_push($result , array_flip($current_list)[$position_of_first_char]);
            array_push($result , array_flip($current_list)[$position_of_second_char]);


        }

        return str_replace('X' , '' , implode('',$result));

    }
}