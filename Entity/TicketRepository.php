<?php

namespace Hackzilla\Bundle\TicketBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Hackzilla\Bundle\TicketBundle\Entity\TicketMessage;

/**
 * TicketRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TicketRepository extends EntityRepository
{
    public function getTicketList($userManager, $ticketStatus, $ticketPriority = null)
    {
        $query = $this->createQueryBuilder('t')
            ->orderBy('t.lastMessage', 'DESC');

        switch($ticketStatus)
        {
            case TicketMessage::STATUS_CLOSED:
                $query
                    ->andWhere('t.status = :status')
                    ->setParameter('status', TicketMessage::STATUS_CLOSED);
                break;

            case TicketMessage::STATUS_OPEN:
            default:
                $query
                    ->andWhere('t.status != :status')
                    ->setParameter('status', TicketMessage::STATUS_CLOSED);
        }
        
        if ($ticketPriority) {
            $query
                ->andWhere('t.priority = :priority')
                ->setParameter('priority', $ticketPriority);
        }
        
        $user = $userManager->getCurrentUser();

        if (\is_object($user)) {
            if (!$userManager->isGranted($user, 'ROLE_TICKET_ADMIN')) {
                $query
                    ->andWhere('t.userCreated = :userId')
                    ->setParameter('userId', $user->getId());
            }
        } else {
            # anonymous user
            $query
                ->andWhere('t.userCreated = :userId')
                ->setParameter('userId', 0);
        }

        return $query;
    }

    public function getResolvedTicketOlderThan($days)
    {
        $closeBeforeDate = new \DateTime();
        $closeBeforeDate->sub(new \DateInterval('P' . $days . 'D'));

        $query = $this->createQueryBuilder('t')
            ->where('t.status = :status')
            ->andWhere('t.lastMessage < :closeBeforeDate')
            ->setParameter('status', TicketMessage::STATUS_RESOLVED)
            ->setParameter('closeBeforeDate', $closeBeforeDate)
        ;

        return $query->getQuery()->getResult();
    }
}
