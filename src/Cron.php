<?php

namespace ADB\ImmoSyncWhise;

use ADB\ImmoSyncWhise\Controller\EstateController;
use ADB\ImmoSyncWhise\Model\Estate;

class Cron
{
    public Estate $estateModel;

    public function __construct()
    {
        $this->estateModel = new Estate();
        $estates = (new EstateController)->list();

        foreach ($estates as $estate) {
            $estateExists = $this->estateModel->get_estate($estate->id);

            count($estateExists) ? $this->updateExistingEstate($estate) : $this->saveNewEstate($estate);
        }
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