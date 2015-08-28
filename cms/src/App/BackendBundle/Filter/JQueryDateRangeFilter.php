<?php

/**
 * JQueryDateRangeFilter class.
 */
namespace App\BackendBundle\Filter;

use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;

/**
 * Datagrid filter for displaying date range with jquery datepickers.
 */
class JQueryDateRangeFilter extends DateRangeFilter
{
    /**
     * (non-PHPdoc).
     *
     * @see \Sonata\DoctrineORMAdminBundle\Filter\AbstractDateFilter::getRenderSettings()
     *
     * @return array
     */
    public function getRenderSettings()
    {
        return array('app_backend_form_filter_filter_date_range_type', array(
            'field_type' => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'label' => $this->getLabel(),
        ));
    }
}
