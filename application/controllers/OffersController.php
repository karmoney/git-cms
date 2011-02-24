<?php

class OffersController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $stub = array(
            'wdw' => array(
                'content' => array(
                    0 => array(
                        'img' => 'http://parks1.wdpromedia.com/media/disneyparks_v0100/media/special-offers/WDWFY11_RoomSpringOffer_thumb.jpg',
                        'title' => 'Last Chance to Save on Early Spring Travel!',
                        'description' => 'Up to 30% off at Select Disney Resort hotels for stays most nights February 17 to April 16, 2011. Book by February 13, 2011.',
                        'link' => 'http://bookwdw.reservations.disney.go.com/ibcwdw/en_US/specialOfferDetails?name=Promo&promotionCode=fy11q2q4roomA&market=fy11q2q4roomA&CMP=ILC-DPSpecOffDPFY11Q2FY11Q2Q4DPRoomOnlyOfferA0001'
                    )
                ),
                'name' => 'Walt Disney World'
            )
        );
        
        $this->view->offers = $stub;
    }
}
