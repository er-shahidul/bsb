<?php

namespace BoardMeetingBundle\Service;

use AppBundle\Utility\DateUtil;
use BoardMeetingBundle\Entity\BoardMember;
use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;

class BoardManager
{
    private $secret;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * BoardManager constructor.
     *
     * @param               $secret
     * @param EntityManager $em
     */
    public function __construct($secret, EntityManager $em)
    {
        $this->secret = $secret;
        $this->em = $em;
    }

    public function isValid(BoardMember $user, $token)
    {
        try {
            $payload = JWT::decode($token, $this->secret . $user->getId() . $user->getMeeting()->getId(), array('HS256'));

            if($payload->user->email !== $user->getEmail()) {
                return FALSE;
            }

            return $payload;
        } catch (\Exception $e) {
            return FALSE;
        }
    }

    public function generateToken(BoardMember $member)
    {
        $issuedAt = $member->getUpdatedAt()->getTimestamp();

        $meeting = $member->getMeeting();
        $key = $this->secret . $member->getId() . $meeting->getId();
        $data = array(
            "iat"  => $issuedAt,
            'nbf' => DateUtil::dateRoundDown($meeting->getDate())->getTimestamp(),
            "iss"  => md5($issuedAt . $key),
            'user' => [
                'id' => $member->getId(),
                'email' => $member->getEmail(),
            ]
        );

        return JWT::encode($data, $key, 'HS256');
    }

    public function getUser($token)
    {
        list($headb64, $bodyb64, $cryptob64) = explode('.', $token);
        $payload = JWT::jsonDecode(base64_decode($bodyb64));
        $repo = $this->em->getRepository('BoardMeetingBundle:BoardMember');

        $user = $repo->find($payload->user->id);

        if ($this->isValid($user, $token)) {
            return $user;
        }

        return $user;
    }
}