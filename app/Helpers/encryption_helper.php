<?php
function encrypt_id($plain)
{
    $config = new \Config\Encryption();
    $config->rawData = false;
    $encrypter = \Config\Services::encrypter($config);
    return $encrypter->encrypt($plain);
}

function decrypt_id($encrypted)
{
    $config = new \Config\Encryption();
    $config->rawData    = false;
    $encrypter = \Config\Services::encrypter($config);
    return $encrypter->decrypt($encrypted);
}

function hash_pass($plain)
{
    $salt = '07657ac48aee1504d21361d3c1a579127f36841abde019e9bc2e2741c8f2b157.gung.wxyz@gmail.com';
    return hash('WHIRLPOOL',bin2hex(base64_encode(base64_encode(base64_encode($plain).base64_encode($salt)))));
}