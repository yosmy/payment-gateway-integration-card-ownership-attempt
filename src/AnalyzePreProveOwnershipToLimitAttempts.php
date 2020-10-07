<?php

namespace Yosmy\Payment\Card;

use Yosmy\Payment;
use Yosmy;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.payment.card.pre_prove_ownership',
 *     ]
 * })
 */
class AnalyzePreProveOwnershipToLimitAttempts implements AnalyzePreProveOwnership
{
    /**
     * @var Yosmy\IncreaseAttempt
     */
    private $increaseAttempt;

    /**
     * @param Yosmy\IncreaseAttempt $increaseAttempt
     */
    public function __construct(
        Yosmy\IncreaseAttempt $increaseAttempt
    ) {
        $this->increaseAttempt = $increaseAttempt;
    }

    /**
     * {@inheritDoc}
     */
    public function analyze(
        Payment\Card $card,
        int $amount
    ) {
        try {
            $this->increaseAttempt->increase(
                'yosmy.payment.card.prove_ownership',
                sprintf('payment-card-%s', $card->getId()),
                3,
                '1 week'
            );
        } catch (Yosmy\ExceededAttemptException $e) {
            throw new Payment\KnownException('Has excedido el número de intentos');
        }
    }
}