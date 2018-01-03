<?php

namespace AppBundle\Utility;

use UserBundle\Entity\User;

class BlameableListener extends \Gedmo\Blameable\BlameableListener
{
    /**
     * Get the user value to set on a blameable field
     *
     * @param object $meta
     * @param string $field
     *
     * @return mixed
     */
    public function getFieldValue($meta, $field, $eventAdapter)
    {
        try {
            $user = parent::getFieldValue($meta, $field, $eventAdapter);
        } catch (\Exception $e) {
            $user = NULL;
        }
        return $user;
    }

    protected function updateField($object, $eventAdapter, $meta, $field)
    {
        if (!$this->user || ! $this->user instanceof User) {
            return;
        }

        parent::updateField($object, $eventAdapter, $meta, $field);
    }
}