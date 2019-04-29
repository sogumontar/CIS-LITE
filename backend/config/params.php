<?php
return [
    'adminEmail' => 'admin@example.com',
    'applicationID' => 'SYSTEMX-BACKEND',
    'authenticatedRoleName' => 'authenticated',
    'sidebarMenu' => [
                        [
                            'label' => 'System Administration',
                            'icon' => 'fa fa-dashboard',
                            'childs' => [
                                [
                                    'label' => 'User List',
                                    'url' => '/admin/user/index',
                                    'icon' => 'fa fa-users',
                                ],
                                [
                                    'label' => 'Add User',
                                    'url' => '/admin/user/add',
                                    'icon' => 'fa fa-plus',
                                ],
                            ]
                        ],
                        [
                            'label' => 'Contoh Menu',
                            'icon' => 'fa fa-dashboard',
                            'url' => '/test'
                        ],
                        [
                            'label' => 'Institusi',
                            'icon' => 'fa fa-institution',
                            'childs' => [
                                [
                                    'label' => 'Instansi & Jabatan',
                                    'url' => '/inst/inst-manager',
                                    'icon' => 'fa fa-building-o',
                                ],
                                [
                                    'label' => 'Unit',
                                    'url' => '/inst/unit',
                                    'icon' => 'fa fa-group',
                                ],
                                [
                                    'label' => 'Manajemen Pejabat',
                                    'url' => '/inst/pejabat',
                                    'icon' => 'fa fa-certificate',
                                ],
                                [
                                    'label' => 'Tree View',
                                    'url' => '/inst/inst-manager/tree-view',
                                    'icon' => 'fa fa-sitemap',
                                ],
                            ]
                        ],
                        [
                            'label' => 'Developer Tools',
                            'icon' => 'fa fa-wrench',
                            'childs' => [
                                [
                                    'label' => 'Gii',
                                    'url' => '/gii',
                                    'icon' => 'fa fa-bug',
                                ],
                                [
                                    'label' => 'UX Patterns',
                                    'url' => '/xdev/ux/patterns',
                                    'icon' => 'fa fa-desktop',
                                ]
                            ]
                        ],
                        
                	],
];
