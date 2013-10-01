<?php

namespace Misi\Bundle\UserBundle\Fee;

use Sylius\Bundle\CoreBundle\Model\ProductInterface;
use Sylius\Bundle\MoneyBundle\Converter\CurrencyConverterInterface;
use Misi\Bundle\UserBundle\Repository\UserFeeRuleRepository;

class ProductFeeCalculator implements ProductFeeCalculatorInterface
{
    protected $enabled;
    
    protected $feeAmount;
    
    protected $feeCurrency;
    
    /**
     *
     * @var UserFeeRuleRepository 
     */
    protected $userFeeRuleRepository;
    
    /**
     * Currency converter
     * 
     * @var CurrencyConverterInterface
     */
    protected $currencyConverter;
    
    public function __construct($enabled, $feeAmount, $feeCurrency, CurrencyConverterInterface $currencyConverter, UserFeeRuleRepository $userFeeRuleRepository)
    {
        $this->enabled = $enabled;
        $this->feeAmount = $feeAmount;
        $this->feeCurrency = $feeCurrency;
        $this->currencyConverter = $currencyConverter;
        $this->userFeeRuleRepository = $userFeeRuleRepository;
    }
    
    /**
     * Calculates the fee price (lowest) according to rules
     * 
     * @param ProductInterface $product
     * @return double
     */
    public function calculateFee(ProductInterface $product) 
    {
        if (!$this->enabled) {
            return 0;
        }
        
        $feeRules = $this->userFeeRuleRepository->findFeesByProduct($product);
        
        $fee = $this->feeAmount;
        
        if (!empty($feeRules)) {
            foreach ($feeRules as $feeRule) {
                $currentFee = $feeRule->calculateFee($product->getPrice());
                if ($currentFee < $fee) {
                    $fee = $currentFee;
                }
            }
        }
        
        if (!is_numeric($fee)) {
            $fee = $this->feeAmount;
        }
        
        return $fee;
    }
}