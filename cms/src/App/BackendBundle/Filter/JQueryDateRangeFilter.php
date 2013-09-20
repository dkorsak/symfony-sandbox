<?php

namespace App\BackendBundle\Filter;

use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;

class JQueryDateRangeFilter extends DateRangeFilter
{
    /**
     * (non-PHPdoc)
     * @see \Sonata\DoctrineORMAdminBundle\Filter\AbstractDateFilter::getRenderSettings()
     */
    public function getRenderSettings()
    {
        return array('app_backend_form_filter_filter_date_range_type', array(
            'field_type'    => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'label'         => $this->getLabel(),
        ));
    }
}
