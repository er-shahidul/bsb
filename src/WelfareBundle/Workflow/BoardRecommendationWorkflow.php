<?php

namespace WelfareBundle\Workflow;


use BudgetBundle\Entity\BudgetExpense;
use Devnet\WorkflowBundle\Core\WorkflowDefinition;
use WelfareBundle\Entity\BoardRecommendation;
use WelfareBundle\Entity\BSCRApplication;
use WelfareBundle\Entity\RCELApplication;

class BoardRecommendationWorkflow extends WorkflowDefinition
{
    public static function getSupports()
    {
        return [BoardRecommendation::class];
    }

    public static function getName()
    {
        return 'welfare_board_recommendation';
    }

    public static function getTransitionsConfig()
    {
        return [
            'initialize' => [
                'from' => 'draft',
                'to' => 'draft',
                'label' => 'Approve',
                'role' => 'CHAIRMAN'
            ],
            'board_recommendation_approve_by_director' => [
                'from' => 'draft',
                'to' => 'completed',
                'label' => 'Approve',
                'role' => 'DIRECTOR'
            ]
        ];
    }
}