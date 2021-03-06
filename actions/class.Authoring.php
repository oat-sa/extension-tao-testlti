<?php
/**  
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 * 
 */

use oat\taoLti\models\classes\ResourceLink\OntologyLink;
use taoTests_models_classes_TestsService as TestService;

/**
 * Controller for actions related to the authoring of the simple test model
 *
 * @author Joel Bout, <joel@taotesting.com>
 * @package taoTests
 
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 *
 */
class ltiTestConsumer_actions_Authoring extends tao_actions_SaSModule {

	/**
	 * (non-PHPdoc)
	 * @see tao_actions_SaSModule::getClassService()
	 */
	protected function getClassService() {
		return taoTests_models_classes_TestsService::singleton();
	}

    public function index()
    {
        $test = $this->getCurrentInstance();
    	$testService = taoTests_models_classes_TestsService::singleton();

    	$class = new core_kernel_classes_Class(CLASS_LTI_TESTCONTENT);
    	$content = $test->getOnePropertyValue(new core_kernel_classes_Property(TestService::PROPERTY_TEST_CONTENT));
    	
        common_Logger::i('Generating form for '.$content->getUri());
    	$form = new ltiTestConsumer_actions_form_LtiLinkForm($content);
    	
    	$this->setData('saveUrl', _url('save', 'Authoring', 'ltiTestConsumer'));
    	$this->setData('formContent', $form->getForm()->render());
        $this->setView('authoring.tpl');
    }
	
	/**
	 * save the related items from the checkbox tree or from the sequence box
	 * @return void
	 */
	public function save()
	{
	    $saved = false;
	    
	    $instance = $this->getCurrentInstance();
        $launchUrl = $this->getRequestParameter(tao_helpers_Uri::encode(OntologyLink::PROPERTY_LAUNCH_URL));
        $consumerUrl = $this->getRequestParameter(tao_helpers_Uri::encode(OntologyLink::PROPERTY_CONSUMER));
        if (empty($launchUrl)) {
            return $this->returnError('Launch URL is required');
        }
        if (empty($consumerUrl)) {
            return $this->returnError('Consumer is required');
        }
        $consumer = new core_kernel_classes_Resource(tao_helpers_Uri::decode($consumerUrl));
        
        $saved = $instance->setPropertiesValues(array(
            OntologyLink::PROPERTY_LAUNCH_URL => $launchUrl,
            OntologyLink::PROPERTY_CONSUMER => $consumer
        ));
	    
	    echo json_encode(array(
	    	'saved' => $saved
	    ));
	}
}
