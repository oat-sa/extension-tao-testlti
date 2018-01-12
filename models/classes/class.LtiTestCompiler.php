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
 * the LTI test consumer test-model
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoWfTest
 
 */
class ltiTestConsumer_models_classes_LtiTestCompiler
	extends taoTests_models_classes_TestCompiler
{

	const INSTANCE_CONSUMER_SERVICE = 'http://www.tao.lu/Ontologies/TAOLTI.rdf#ServiceLtiConsumer';

	const INSTANCE_FORMAL_PARAM_CONSUMER = 'http://www.tao.lu/Ontologies/TAOLTI.rdf#LtiConsumerUri';

	const INSTANCE_FORMAL_PARAM_LAUNCH_URL = 'http://www.tao.lu/Ontologies/TAOLTI.rdf#LtiLaunchUrl';

    function compile() {
        
        $content = $this->getResource()->getUniquePropertyValue(new core_kernel_classes_Property(TestService::PROPERTY_TEST_CONTENT));
        
        $ltiLaunchUrl = $content->getOnePropertyValue(new core_kernel_classes_Property(OntologyLink::PROPERTY_LAUNCH_URL));
        $ltiLinkConsumer = $content->getOnePropertyValue(new core_kernel_classes_Property(OntologyLink::PROPERTY_CONSUMER));
        
        if (empty($ltiLaunchUrl)) {
            throw new tao_models_classes_CompilationFailedException('Missing launch Url for test '.$this->getResource()->getUri());
        }
        if (empty($ltiLinkConsumer)) {
            throw new tao_models_classes_CompilationFailedException('Missing LTI consumer for test '.$this->getResource()->getUri());
        }
        
        // Build the service call.
        $service = new tao_models_classes_service_ServiceCall(new core_kernel_classes_Resource(self::INSTANCE_CONSUMER_SERVICE));
        $param = new tao_models_classes_service_ConstantParameter(
            // Test Definition URI passed to the QtiTestRunner service.
            new core_kernel_classes_Resource(self::INSTANCE_FORMAL_PARAM_LAUNCH_URL),
            $ltiLaunchUrl
        );
        $service->addInParameter($param);
        
        $param = new tao_models_classes_service_ConstantParameter(
            // Test Compilation URI passed to the QtiTestRunner service.
            new core_kernel_classes_Resource(self::INSTANCE_FORMAL_PARAM_CONSUMER),
            $ltiLinkConsumer->getUri()
        );
        $service->addInParameter($param);
        
        common_Logger::d("LTI Test successfully compiled.");
        
        return $service;
    }
}