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
            new \Twig_SimpleFilter('format_bytes', array($this, 'formatBytesFilter')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('percentage', array($this, 'getPercentage')),
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
        foreach (array('', 'k', 'M', 'G') as $k) {
            if ($bytes < 1024) {
                break;
            }
            $bytes /= 1024;
        }

        return round($bytes, 2)." {$k}B";
    }

    /**
     * @param         $amount
     * @param  int    $total
     * @return string
     */
    public function getPercentage($amount, $total = 100)
    {
        if (0 == $total) {
            return '00.00';
        }
        $percent = round(($amount / $total) * 100, 2);

        return $percent;
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
