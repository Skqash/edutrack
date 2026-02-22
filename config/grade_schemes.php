<?php

return [
    // Predefined grading schemes tailored for Philippine schools
    'schemes' => [
        'ched_ksa' => [
            'label' => 'CHED KSA (Default)',
            'weights' => [
                'knowledge' => 40,
                'skills' => 50,
                'attitude' => 10,
            ],
            'term_weights' => [
                'midterm' => 40,
                'final' => 60,
            ],
        ],
        'rule_60_30_10' => [
            'label' => '60 / 30 / 10 (Knowledge / Skills / Attitude)',
            'weights' => [
                'knowledge' => 60,
                'skills' => 30,
                'attitude' => 10,
            ],
            'term_weights' => [
                'midterm' => 40,
                'final' => 60,
            ],
        ],
        'deped_basic' => [
            'label' => 'DepEd Basic (Common Public School)',
            'weights' => [
                'knowledge' => 50,
                'skills' => 40,
                'attitude' => 10,
            ],
            'term_weights' => [
                'midterm' => 40,
                'final' => 60,
            ],
        ],
        'private_school_variant' => [
            'label' => 'Private School Variant',
            'weights' => [
                'knowledge' => 45,
                'skills' => 45,
                'attitude' => 10,
            ],
            'term_weights' => [
                'midterm' => 30,
                'final' => 70,
            ],
        ],
        'university_custom' => [
            'label' => 'University / College (Customizable)',
            'weights' => [
                'knowledge' => 40,
                'skills' => 50,
                'attitude' => 10,
            ],
            'term_weights' => [
                'midterm' => 40,
                'final' => 60,
            ],
        ],
    ],
];
