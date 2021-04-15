<?php 
use Cake\Routing\Router;
$config = ['Users' => 
                ['Social' => 
                        ['login' => true]
                ],
                'OAuth' => [
                        'providers' => [
                                'twitter' => [
                                        'service' => 'CakeDC\Auth\Social\Service\OAuth1Service',
                                        'className' => 'League\OAuth1\Client\Server\Twitter',
                                        'mapper' => 'CakeDC\Auth\Social\Mapper\Twitter',
                                        'options' => [
                                                'redirectUri' => Router::fullBaseUrl() . '/auth/twitter',
                                                'linkSocialUri' => Router::fullBaseUrl() . '/link-social/twitter',
                                                'callbackLinkSocialUri' => Router::fullBaseUrl() . '/callback-link-social/twitter',
                                        ]
                                ],
                                // 'azuread' => [
                                //         'service' => 'CakeDC\Auth\Social\Service\OAuth2Service', // noooo
                                //         'className' => 'TheNetworg\OAuth2\Azure\Server\AzureAD', // noooo
                                //         'mapper' => 'CakeDC\Auth\Social\Mapper\Azuread',         // noooo
                                //         'options' => [
                                //                 'redirectUri' => Router::fullBaseUrl() . '/auth/azuread',
                                //                 'linkSocialUri' => Router::fullBaseUrl() . '/link-social/azuread',
                                //                 'callbackLinkSocialUri' => Router::fullBaseUrl() . '/callback-link-social/azuread',
                                //         ]
                                // ]
                        ]
                        ],
            ];
return $config;