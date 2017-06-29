<?php

namespace Sop\CryptoTypes\Asymmetric;

use Sop\CryptoEncoding\PEM;

/**
 * Base class for public keys.
 */
abstract class PublicKey
{
    /**
     * Get the public key algorithm identifier.
     *
     * @return \Sop\CryptoTypes\AlgorithmIdentifier\AlgorithmIdentifier
     */
    abstract public function algorithmIdentifier();
    
    /**
     * Get DER encoding of the public key.
     *
     * @return string
     */
    abstract public function toDER();
    
    /**
     * Get the public key data for subjectPublicKey in PublicKeyInfo.
     *
     * @return string
     */
    public function subjectPublicKeyData()
    {
        return $this->toDER();
    }
    
    /**
     * Get the public key as a PublicKeyInfo type.
     *
     * @return PublicKeyInfo
     */
    public function publicKeyInfo()
    {
        return PublicKeyInfo::fromPublicKey($this);
    }
    
    /**
     * Initialize public key from PEM.
     *
     * @param PEM $pem
     * @throws \UnexpectedValueException
     * @return self
     */
    public static function fromPEM(PEM $pem)
    {
        switch ($pem->type()) {
            case PEM::TYPE_RSA_PUBLIC_KEY:
                return RSA\RSAPublicKey::fromDER($pem->data());
            case PEM::TYPE_PUBLIC_KEY:
                return PublicKeyInfo::fromPEM($pem)->publicKey();
        }
        throw new \UnexpectedValueException(
            "PEM type " . $pem->type() . " is not a valid public key.");
    }
}