<?php declare(strict_types=1); 

if (!function_exists('format_currency')) {
    /**
     * Formater un montant en FCFA
     * 
     * @param float|int|string|null $amount
     * @param int $decimals
     * @return string
     */
    function format_currency($amount, $decimals = 0)
    {
        // Convertir en float si c'est une string ou null
        $amount = is_numeric($amount) ? (float) $amount : 0;
        
        return number_format($amount, $decimals, ',', ' ') . ' FCFA';
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Retourner le symbole de la devise
     * 
     * @return string
     */
    function currency_symbol()
    {
        return 'FCFA';
    }
}

