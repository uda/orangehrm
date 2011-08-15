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
class addJobVacancyAction extends sfAction {

    private $vacancyService;
    private $jobFeedService;

    /**
     * Get VacancyService
     * @returns VacncyService
     */
    public function getVacancyService() {
        if (is_null($this->vacancyService)) {
            $this->vacancyService = new VacancyService();
            $this->vacancyService->setVacancyDao(new VacancyDao());
        }
        return $this->vacancyService;
    }

    /**
     * Set VacancyService
     * @param VacancyService $vacancyService
     */
    public function setVacancyService(VacancyService $vacancyService) {
        $this->vacancyService = $vacancyService;
    }
    
    /**
     * Get JobFeedService
     * @returns JobFeedService Object
     */
    public function getJobFeedService() {
        
        if (is_null($this->jobFeedService)) {
            $this->jobFeedService = new JobFeedService();
        }
        
        return $this->jobFeedService;       
    }

    /**
     * Set JobFeedService
     * @param JobFeedService $jobFeedService
     */
    public function setJobFeedService(JobFeedService $jobFeedService) {
        $this->vacancyService = $jobFeedService;
    }
    
    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    /**
     *
     * @param <type> $request
     */
    public function execute($request) {

        $this->vacancyId = $request->getParameter('Id');
        $values = array('vacancyId' => $this->vacancyId);
        $this->setForm(new AddJobVacancyForm(array(), $values));

        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }

        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->vacancyId = $this->form->save();
                $this->getUser()->setFlash('templateMessage', array('success', __('Job Vacancy Saved Successfully')));
                
                $this->getJobFeedService()->updateJobFeed();
                $this->redirect('recruitment/addJobVacancy?Id='.$this->vacancyId);
            }
        }
    }

}
