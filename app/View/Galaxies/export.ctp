<?php
echo $this->element('genericElements/Form/genericForm', array(
    'form' => $this->Form,
    'data' => array(
        'model' => 'Galaxy',
        'title' => sprintf(__('Export galaxy: %s'), h($galaxy['Galaxy']['name'])),
        'fields' => array(
            array(
                'field' => 'distribution',
                'label' => '<strong>' . __("Cluster's distribution:") . '</strong>',
                'options' => $distributionLevels,
                'selected' => array(1, 2, 3),
                'multiple' => 'checkbox', 
            ),
            '<br />',
            array(
                'field' => 'custom',
                'type' => 'checkbox',
                'label' => __("Include Custom Clusters"),
                'checked' => true
            ),
            array(
                'field' => 'default',
                'type' => 'checkbox',
                'label' => __("Include Default Clusters"),
                'checked' => true
            ),
            array(
                'field' => 'format',
                'type' => 'radio',
                'legend' => __('Export format'),
                'options' => array(
                    'misp' => sprintf('<b>%s</b>: %s', __('MISP Format'), __('To re-import to another MISP')),
                    'misp-galaxy' => sprintf('<b>%s</b>: %s', __('misp-galaxy format'), __('Usable to be integrated into the official repository')),
                ),
                'default' => 'raw',
            ),
            '<br />',
            array(
                'field' => 'download',
                'type' => 'radio',
                'legend' => __('Export type'),
                'options' => array(
                    'download' => __('Download'),
                    'raw' => __('Raw'),
                ),
                'default' => 'raw',
            ),
        )
    )
));

echo $this->element('/genericElements/SideMenu/side_menu', array('menuList' => 'galaxies', 'menuItem' => 'export'));
