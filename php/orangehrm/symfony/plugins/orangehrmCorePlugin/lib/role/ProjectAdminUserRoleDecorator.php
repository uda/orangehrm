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
class ProjectAdminUserRoleDecorator extends UserRoleDecorator {
    const PROJECT_ADMIN_USER = "PROJECT ADMIN";
    const PROJECT_REPORT_LINK="./symfony/web/index.php/time/displayProjectReportCriteria?reportId=1";

    private $user;
    private $projectService;

    public function __construct(User $user) {

        $this->user = $user;
        parent::setEmployeeNumber($user->getEmployeeNumber());
        parent::setUserId($user->getUserId());
        parent::setUserTimeZoneOffset($user->getUserTimeZoneOffset());
    }

    public function getProjectService() {

        if (is_null($this->projectService)) {

            $this->projectService = new ProjectService();
        }

        return $this->projectService;
    }

    /**
     * Set Project Data Access Object
     * @param ProjectService $projectService
     * @return void
     */
    public function setProjectService(ProjectService $projectService) {

        $this->projectService = $projectService;
    }

    public function getAccessibleTimeMenus() {

        $topMenuItemArray = $this->user->getAccessibleTimeMenus();

        $topMenuItem = new TopMenuItem();
        $topMenuItem->setDisplayName(__("Reports"));
        $topMenuItem->setLink(ProjectAdminUserRoleDecorator::PROJECT_REPORT_LINK);

        if (!in_array($topMenuItem, $topMenuItemArray)) {
            array_push($topMenuItemArray, $topMenuItem);
        }
        return $topMenuItemArray;
    }

    public function getAccessibleConfigurationSubMenus() {

        $tempArray = $this->user->getAccessibleConfigurationSubMenus();

        return $tempArray;
    }

    public function getAccessibleReportSubMenus() {

        $topMenuItemArray = $this->user->getAccessibleReportSubMenus();

        $topMenuItem = new TopMenuItem();
        $topMenuItem->setDisplayName(__(" Project Reports"));
        $topMenuItem->setLink(AdminUserRoleDecorator::PROJECT_REPORT_LINK);


        if (!in_array($topMenuItem, $topMenuItemArray)) {
            array_push($topMenuItemArray, $topMenuItem);
        }

        return $topMenuItemArray;
    }

    /**
     * Get actions that this user can perform on a perticular workflow with the current state
     * @param int $workFlow
     * @param string $state
     * @return string[]
     */
    public function getAllowedActions($workFlow, $state) {

        $accessFlowStateMachineService = new AccessFlowStateMachineService();
        $allowedActionsForEssUser = $accessFlowStateMachineService->getAllowedActions($workFlow, $state, ProjectAdminUserRoleDecorator::PROJECT_ADMIN_USER);

        $existingAllowedActions = $this->user->getAllowedActions($workFlow, $state);

        if (is_null($allowedActionsForEssUser)) {
            return $existingAllowedActions;
        }

        $allowedActionsList = array_unique(array_merge($allowedActionsForEssUser, $existingAllowedActions));

        return $allowedActionsList;
    }

    /**
     * Get next state given workflow, state and action for this user
     * @param int $workFlow
     * @param string $state
     * @param int $action
     * @return string
     */
    public function getNextState($workFlow, $state, $action) {

        $accessFlowStateMachineService = new AccessFlowStateMachineService();
        $tempNextState = $accessFlowStateMachineService->getNextState($workFlow, $state, ProjecctAdminUserRoleDecorator::PROJECT_ADMIN_USER, $action);

        $temp = $this->user->getNextState($workFlow, $state, $action);

        if (is_null($tempNextState)) {
            return $temp;
        }

        return $tempNextState;
    }

    public function isAllowedToDefineTimeheetPeriod() {

        $isAllowed = $this->user->isAllowedToDefineTimeheetPeriod();

        return $isAllowed;
    }

    public function getActiveProjectList() {

        $activeProjectList = $this->getProjectService()->getActiveProjectListRelatedToProjectAdmin($this->user->getEmployeeNumber());
        return $activeProjectList;
    }

    public function getActionableStates() {

        return $this->user->getActionableStates();
    }

}