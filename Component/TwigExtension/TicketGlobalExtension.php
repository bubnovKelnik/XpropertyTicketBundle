<?php

namespace Hackzilla\Bundle\TicketBundle\Component\TwigExtension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class TicketGlobalExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     *
     * @access protected
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     *
     * @access public
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     *
     * @access public
     * @return array
     */
    public function getGlobals()
    {
        return array(
            'hackzilla_ticket' => array(
                'templates' => array(
                    'index' => $this->container->getParameter('hackzilla_ticket.templates.index'),
                    'new' => $this->container->getParameter('hackzilla_ticket.templates.new'),
                    'show' => $this->container->getParameter('hackzilla_ticket.templates.show'),
                    'prototype' => $this->container->getParameter('hackzilla_ticket.templates.prototype'),
                    'macros' => $this->container->getParameter('hackzilla_ticket.templates.macros'),
                ),
            )
        );
    }

    /**
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'ticketGlobal';
    }
}
