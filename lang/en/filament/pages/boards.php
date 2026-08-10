<?php

declare(strict_types=1);

return [
    'view_switcher' => [
        'label' => 'Switch view',
        'list' => 'List',
        'board' => 'Board',
    ],

    'leads' => [
        'title' => 'Leads',
        'actions' => [
            'add' => 'Add Lead',
            'edit' => 'Edit',
            'delete' => 'Delete',
        ],
        'filters' => [
            'company' => 'Company',
            'contact' => 'Contact',
        ],
        'form' => [
            'name_placeholder' => 'Enter lead title',
        ],
    ],

    'deals' => [
        'title' => 'Sales Pipeline',
        'actions' => [
            'add' => 'Add Opportunity',
            'edit' => 'Edit',
            'delete' => 'Delete',
        ],
        'filters' => [
            'company' => 'Company',
            'contact' => 'Contact',
        ],
        'form' => [
            'name_placeholder' => 'Enter deal title',
        ],
    ],

    'orders' => [
        'title' => 'Project Delivery',
        'actions' => [
            'add' => 'Add Project',
            'edit' => 'Edit',
            'delete' => 'Delete',
        ],
        'filters' => [
            'company' => 'Company',
            'contact' => 'Contact',
        ],
        'form' => [
            'name_placeholder' => 'Enter project title',
        ],
    ],

    'tasks' => [
        'title' => 'Tasks',
        'actions' => [
            'add' => 'Add Task',
            'edit' => 'Edit',
            'delete' => 'Delete',
        ],
        'filters' => [
            'assignee' => 'Assignee',
        ],
    ],
];
