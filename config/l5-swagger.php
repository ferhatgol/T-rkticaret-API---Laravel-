<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'E-Ticaret API Documentation',
            ],
            'routes' => [
                'docs' => 'api/documentation',
                'oauth2_callback' => 'api/oauth2-callback',
                'middleware' => [
                    'api' => [],
                ],
            ],
            'paths' => [
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
                'annotations' => [
                    base_path('app'),
                ],
            ],
        ],
    ],
    'defaults' => [
        'routes' => [
            'docs' => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],
            'group_options' => [],
        ],
        'paths' => [
            'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
            'docs_json' => 'api-docs.json',
            'docs_yaml' => 'api-docs.yaml',
            'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
            'annotations' => [
                base_path('app'),
            ],
        ],
        'scanOptions' => [
            'exclude' => [],
            'pattern' => '*.php',
            'open_api_spec_version' => env('L5_SWAGGER_OPEN_API_SPEC_VERSION', '3.0.0'),
        ],
        'securityDefinitions' => [
            'securitySchemes' => [
                'bearerAuth' => [
                    'type' => 'http',
                    'scheme' => 'bearer',
                    'bearerFormat' => 'JWT',
                ],
            ],
            'security' => [
                [
                    'bearerAuth' => []
                ]
            ],
        ],
        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
        'proxy' => false,
        'additional_config_url' => null,
        'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),
        'validator_url' => null,
        'ui' => [
            'display' => [
                'default_models_expand_depth' => 1,
                'default_model_expand_depth' => 1,
                'default_model_rendering' => 'example',
                'display_operation_id' => false,
                'display_request_duration' => false,
                'doc_expansion' => 'none',
                'filter' => false,
                'max_displayed_tags' => null,
                'show_extensions' => false,
                'show_common_extensions' => false,
            ],
            'authorization' => [
                'persist_authorization' => false,
            ],
        ],
        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:8000'),
        ],
    ],
];
