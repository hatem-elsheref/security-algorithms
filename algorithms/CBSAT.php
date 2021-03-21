<?php


namespace Elsheref\Security\Algorithms;

class CBSAT
{
    private array $domain = [];
    private array $rows = [];

    /**
     * CBSAT constructor.
     */
    public function __construct()
    {
        $this->generateDomain();
    }

    private function generateDomain(){

        $all = range('A','Z');
        $all = array_flip($all);
        unset($all['Z']);
        $all = array_values(array_flip($all));

        $rows = ['A' , 'B' , 'C' , 'D' , 'E'];
        $columns = ['A' , 'B' , 'C' , 'D' , 'E'];

        $counter = 0;
        foreach ($rows as $index => $rowChar){
            foreach ($columns as $subIndex => $columnChar){
                $domain[$all[$counter]] = ['col' => $columnChar , 'row' => $rowChar];
                $this->rows[$rowChar][$columnChar] = $all[$counter];
                $counter++;
            }
        }

        $this->domain = $domain;

    }

    private function prepareText(string $text){
        if (empty($text))
            throw new \Exception('please enter valid text');

        return implode('',explode(' ',strtoupper($text)));
    }





    public function encrypt(string $plain){

        $result = [];

        try {
            $plain = $this->prepareText($plain);
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }

        $result = [];

        foreach (str_split($plain) as $index => $char){
            array_push($result , $this->domain[$char]['row']);
            array_push($result , $this->domain[$char]['col']);
        }




        $row = array_slice($result , 0 , count($result) / 2);
        $columns = array_slice($result , count($result) / 2 , count($result) / 2);



       $result = [];
       foreach ($row as $index => $char){
           $col = $columns[$index];
           $result[] = $this->rows[$char][$col];
       }


        return implode('',$result);
    }

    public function decrypt(string $cipher){

        $result = [];

        try {
            $cipher = $this->prepareText($cipher);
        }catch (\Exception $e) {
            echo $e->getMessage();
            exit(0);
        }

        $rows = [];
        $columns = [];

        foreach (str_split($cipher) as $index => $char){
            array_push($rows , $this->domain[$char]['row']);
            array_push($columns , $this->domain[$char]['col']);
        }
        $result = implode('',array_merge($rows , $columns));

        $parts = str_split($result , 2);

        $result = [];

        foreach ($parts as $part){
            array_push($result , $this->rows[$part[0]][$part[1]]);
        }

       return implode($result);

    }
}