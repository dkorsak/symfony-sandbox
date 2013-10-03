<?php

/**
 * FormatExtension class
 *
 *
 */
namespace App\GeneralBundle\Twig\Extension;

class FormatExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getFilters()
    {
        return array(
            'format_bytes' => new \Twig_Filter_Method($this, 'formatBytesFilter'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'percentage' => new \Twig_Function_Method($this, 'getPercentage')
        );
    }

    /**
     * Convert bytes into kB, MB, GB
     *
     * @param  number $bytes
     * @return string
     */
    public function formatBytesFilter($bytes)
    {
        foreach (array('','k','M','G') as $k) {
            if ($bytes < 1024) {
                break;
            }
            $bytes/=1024;
        }

        return round($bytes, 2) . " {$k}B";
    }

    /**
     * Get percentage value of 2 variables
     *
     * @param  number $amount
     * @param  number $total
     * @return string
     */
    public function getPercentage($amount, $total = 100)
    {
        if (0 == $total) {
            return '00.00%';
        }
        $percent = round(($amount / $total) * 100, 2);

        return $percent . '%';
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return 'app_general_format_extension';
    }
}
