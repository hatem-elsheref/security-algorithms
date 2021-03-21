<?php


namespace Elsheref\Security\Algorithms;


class RSA
{

    const MIN_PRIME = 2;
    const MAX_PRIME = 8000;
    private int $modular;
    private int $eular;
    private int $encryptionKey;
    private int $decryptionKey;
    private array $domain = [];


    private array $privateKey = [];
    private array $publicKey = [];
    public array $receiverPublicKey = [];


    public function __construct(int $p = null , int $q = null ,int $e = null, int $d = null , bool $generationMode = false)
    {

        $this->generateDomain();

        if ($generationMode){
            $this->generateTwoPrimeNumber();
        }else{
            $this->modular = $p * $q;
            $this->eular = ($p - 1) * ($q - 1);
        }

        if (!is_null($e)){
            $this->encryptionKey = $e;
        }else{
            $this->generateEncryptionKey();
        }

        if (!is_null($d)){
            $this->decryptionKey = $d;
        }else{
            $this->generateDecryptionKey();
        }



        $this->publicKey = ['n' => $this->modular , 'e' => $this->encryptionKey];
        $this->privateKey = ['n' => $this->modular , 'd' => $this->decryptionKey];

    }

    private function generateDomain(){

        $capital = range('A','Z');
        $small = range('a','z');
        $this->domain = array_merge($capital,$small);

        $this->domain = array_flip($this->domain);

    }
    private function generateEncryptionKey(){

        $founded = false;

        for ($i = 2 ; $i < $this->eular ; $i++){
            if ($this->isRelativelyPrime($i , $this->eular)){
                $this->encryptionKey = $i;
                $founded = true;
                break;
            }
        }

        if(! $founded)
            trigger_error("cant find any number to be relatively prime with z ({$this->eular})" , E_USER_ERROR);

    }

    private function generateDecryptionKey(){

        if (!$this->isRelativelyPrime($this->encryptionKey , $this->eular))
            trigger_error("e {$this->encryptionKey} must be relatively prime with z ({$this->eular})" , E_USER_ERROR);



        $this->decryptionKey = modInverseWithExtendedAlgorithm($this->encryptionKey , $this->eular);

        if ($this->decryptionKey === $this->encryptionKey)
            $this->decryptionKey = $this->decryptionKey + $this->eular;

    }

    private function generateTwoPrimeNumber(){

        $first  = rand(self::MIN_PRIME , self::MAX_PRIME);
        $second = rand(self::MIN_PRIME , self::MAX_PRIME);

        while (!$this->isPrime($first)){
            $first  = rand(self::MIN_PRIME , self::MAX_PRIME);
        }

        while (!$this->isPrime($second)){
            $second = rand(self::MIN_PRIME , self::MAX_PRIME);
        }

        $this->modular = $first * $second;
        $this->eular = ($first - 1) * ($second - 1);
    }

    private function isPrime(int $number){

        if ($number === 0 || $number === 1)
            return false;
        for ($i = 2 ; $i <= $number / 2 ; $i++){
            if ($number % $i === 0)
                return  false;
        }

        return true;
    }

    private function isRelativelyPrime(int $number_1 ,int $number_2 ){
        if ($number_1 === $number_2){
            $GCD = $number_1;
        }else{
            $max = $number_1 > $number_2 ? $number_1 : $number_2;
            $min = $number_1 < $number_2  ? $number_1 : $number_2;
            $GCD = $this->getGcd($min , $max);
        }

        return $GCD === 1;
    }

    private function getGcd(int $min , int $max){

        if ($max === 0 && $min === 0)
            return 0;
        if ($min === 0)
            return $max;

        $lastFactor = 1;

        for ($i = 1 ; $i <= $min ; $i++){
            if ($max % $i === 0 && $min % $i === 0)
                $lastFactor = $i;
        }
        return $lastFactor;
    }

    public function handShake(RSA $server){
        $this->receiverPublicKey = $server->getPublicKey();
    }

    public function getPublicKey(){
        return $this->publicKey;
    }

    public function encrypt(int $message){

        if (!is_numeric($message)){
            $message = $this->domain[$message];
        }


        return $this->process($message , $this->receiverPublicKey['e'] , $this->receiverPublicKey['n']);
        /*
        $result = 1;

        $message = $message % $this->receiverPublicKey['n'];

        if ($message == 0)
            return 0;

        while ($this->receiverPublicKey['e'] > 0)
        {
            if ($this->receiverPublicKey['e'] & 1)
                $result = ($result * $message) % $this->receiverPublicKey['n'];

            $this->receiverPublicKey['e'] = $this->receiverPublicKey['e'] >> 1;
            $message = ($message * $message) % $this->receiverPublicKey['n'];
        }

        return  $result;
*/
    }

    public function decrypt(int $message){

        return $this->process($message , $this->privateKey['d'] , $this->privateKey['n']);

    }

    private function process(int $message , int $exponent , int $modular){

        $result = 1;

        $message = $message % $modular;

        if ($message == 0)
            return 0;

        while ($exponent > 0)
        {
            if ($exponent & 1)
                $result = ($result * $message) % $modular;

            $exponent = $exponent >> 1;
            $message = ($message * $message) % $modular;
        }

        return $result;

    }
}