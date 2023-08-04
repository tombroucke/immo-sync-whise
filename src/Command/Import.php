<?php

namespace Bruno\ImmoSyncWhise\Command;

use Bruno\ImmoSyncWhise\Adapter\EstateAdapter;
use Bruno\ImmoSyncWhise\Container;
use Bruno\ImmoSyncWhise\Model\Estate;

class Import extends Command
{
    public const COMMAND_NAME = 'iws import';

    public Estate $estateModel;

    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->estateModel = new Estate();
    }

    /**
     *
     * wp iws import list
     */
    public function list()
    {
        $estates = (new EstateAdapter)->list();

        foreach ($estates as $estate) {
            $estateExists = $this->estateModel->get_estate_exists($estate->id);

            count($estateExists) ? $this->updateExistingEstate($estate) : $this->saveNewEstate($estate);
        }
    }

    private function saveNewEstate($estate)
    {
        $postId = $this->estateModel->save_estate($estate);

        $this->estateModel->save_meta($postId, $estate);
    }

    private function updateExistingEstate($estate)
    {
        $this->estateModel->update_meta($postId, $estate);
    }
}



// ^ Whise\Api\Response\ResponseObject {#1774 ▼
//   #_data: array:35 [▶]
//   id: 5210633
//   address: "Vijverstraat"
//   availabilityDateTime: DateTime @1575590400 {#1775 ▶}
//   canHaveChildren: true
//   city: "Zaventem"
//   client: "Bruno"
//   clientId: 9514
//   createDateTime: DateTime @1672920132 {#1776 ▶}
//   currency: "€"
//   displayPrice: true
//   displayStatusId: 2
//   longDescription: array:1 [▶]
//   maxArea: 136.0
//   minArea: 114.0
//   name: "DEMO New Project"
//   number: "5"
//   office: "Bruno (your WHISE testing office)"
//   officeId: 11959
//   price: 300000.0
//   priceChangeDateTime: DateTime @1527605167 {#1778 ▶}
//   referenceNumber: "3457874"
//   shortDescription: array:1 [▶]
//   sms: array:1 [▶]
//   updateDateTime: DateTime @1672920136 {#1781 ▶}
//   zip: "1930"
//   category: Whise\Api\Response\ResponseObject {#1782 ▶}
//   subCategory: Whise\Api\Response\ResponseObject {#1783 ▶}
//   country: Whise\Api\Response\ResponseObject {#1784 ▶}
//   status: Whise\Api\Response\ResponseObject {#1785 ▶}
//   availability: Whise\Api\Response\ResponseObject {#1786 ▶}
//   purpose: Whise\Api\Response\ResponseObject {#1787 ▶}
//   purposeStatus: Whise\Api\Response\ResponseObject {#1788 ▶}
//   state: Whise\Api\Response\ResponseObject {#1789 ▶}
//   pictures: array:3 [▶]
//   details: array:17 [▶]
