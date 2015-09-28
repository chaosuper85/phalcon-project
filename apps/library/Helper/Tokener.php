<?php
namespace Library\Helper;

/**
 * random token generator class
 */
class Tokener
{

    // alphabet constants
    const LOWER_CASE_LETTERS = 'abcdefghijklmnopqrstuvwxyz';
    const UPPER_CASE_LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const NUMBERS = '0123456789';
    const SYMBOLS = '!$%*&/=?-.'; 

    /**
     * current alphabet variable
     * @var $alphabet
     */
    protected $alphabet;

    /**
     * constructor sets the alphabet to generate the codes
     */
    public function __construct($alphabet = null)
    {
        if ($alphabet === null) {
            $this->alphabet = self::LOWER_CASE_LETTERS . self::UPPER_CASE_LETTERS . self::NUMBERS;
        } else {
            $this->alphabet = $alphabet;
        }
    }

    /**
     * set the alphabet to generate the codes
     * @param string $alphabet
     */
    public function setAlphabet($alphabet)
    {
        $this->alphabet = $alphabet;
    }

    /**
     * return the current alphabet
     */
    public function getAlphabet() {
        return $this->alphabet;
    }

    /**
     * crypto random
     * return random number between $min and $max
     *
     * @param int $min
     * @param int $max
     */
    protected function cryptoRandSecure($min, $max)
    {
        $range = $max - $min;
        if ($range < 0) return $min;
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1;
        $bits = (int) $log + 1;
        $filter = (int) (1 << $bits) - 1;
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter;
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    /**
     * generate a token with the specified length and alphabet
     *
     * @param int $length
     * @param string $alphabet
     */
    public function getToken($length = 8)
    {
        $token = '';
        for ($i=0 ; $i<$length ; $i++) {
            $token .= $this->alphabet[$this->cryptoRandSecure(0, strlen($this->alphabet))];
        }
        return $token;
    }



    /**
     * test token
     */
    public function testTokenLength()
    {
        $length = 9;
        $tokener = new Tokener();
        $token = $tokener->getToken($length);
        $this->assertEquals(strlen($token), $length);
    }



}
