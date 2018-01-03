<?php
namespace AccountBundle\EventListener;

use AccountBundle\Entity\ReceiveVoucher;
use AccountBundle\Entity\SanctionEntry;
use AccountBundle\Entity\Voucher;
use Doctrine\ORM\Event\LifecycleEventArgs;

class GenerateVoucherNumber
{
    public function prePersist(LifecycleEventArgs $args)
    {
        return;
        /** @var Voucher $object */
        $object = $args->getObject();

        if (!$object instanceof Voucher) {
            return;
        }
        $objectManager = $args->getObjectManager();

        if ($object instanceof ReceiveVoucher) {
            $prefix = 'RV';
            $repo = $objectManager->getRepository('AccountBundle:ReceiveVoucher');
        } else {
            $repo = $objectManager->getRepository('AccountBundle:PaymentVoucher');
            $prefix = 'PV';
        }
        $nexVoucherNumber = $repo->getNextVoucherNumber($object);

        $voucherNumber = str_pad($nexVoucherNumber, 3, 0, STR_PAD_LEFT);

        $object->setVoucherNumber(sprintf('%s-%s', $prefix, $voucherNumber));
        $object->setVoucherDate(new \DateTime());
        $object->setGeneratedVoucherNumber($nexVoucherNumber);
    }
}