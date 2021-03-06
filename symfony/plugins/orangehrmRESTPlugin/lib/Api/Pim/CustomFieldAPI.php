<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

namespace Orangehrm\Rest\Api\Pim;


use Orangehrm\Rest\Api\EndPoint;
use Orangehrm\Rest\Api\Exception\RecordNotFoundException;
use Orangehrm\Rest\Api\Pim\Entity\CustomField;
use Orangehrm\Rest\Http\Response;

class CustomFieldAPI extends EndPoint
{
    protected $customFieldService;

    /**
     * Get CustomFieldsService
     *
     * @returns \CustomFieldsService
     */
    public function getCustomFieldService() {

        if (is_null($this->customFieldService)) {
            $this->customFieldService = new \CustomFieldConfigurationService();
            $this->customFieldService->setCustomFieldsDao(new \CustomFieldConfigurationDao());
        }
        return $this->customFieldService;
    }

    /**
     * Set Customer field Service
     */
    public function setCustomFieldService(\CustomFieldConfigurationService $customFieldsService) {

        $this->customFieldService = $customFieldsService;
    }

    /**
     * Get custom fields
     *
     * @return Response
     * @throws RecordNotFoundException
     */
    public function getCustomFields()
    {
        $customFieldList = $this->getCustomFieldService()->getCustomFieldList(null, 'name', 'ASC');
        $response = null;

        foreach ($customFieldList as $field) {
            $customField = new CustomField();
            $customField->build($field);
            $response[] = $customField->toArray();

        }
        if (count($response) > 0) {
            return new Response($response);

        } else {
            throw new RecordNotFoundException('No Custom Fields Found');
        }


    }

}
