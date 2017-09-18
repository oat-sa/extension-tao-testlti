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
use taoTests_models_classes_TestsService as TestService;
/**
 * the LTI test consumer test-model
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoWfTest
 
 */
class ltiTestConsumer_models_classes_LtiTestModel
	implements taoTests_models_classes_TestModel
{
    private $extension;
    
	/**
     * (non-PHPdoc)
	 * @see taoTests_models_classes_TestModel::__construct()
	 */
	public function __construct() {
    	$this->extension = common_ext_ExtensionsManager::singleton()->getExtensionById('ltiTestConsumer');
	}
	
	/**
     * @deprecated
     * @see taoTests_models_classes_TestModel::getAuthoring()
     */
    public function getAuthoring( core_kernel_classes_Resource $test) {
    	$testService = taoTests_models_classes_TestsService::singleton();

    	$class = new core_kernel_classes_Class(CLASS_LTI_TESTCONTENT);
    	$content = $test->getOnePropertyValue(new core_kernel_classes_Property(TestService::PROPERTY_TEST_CONTENT));
    	
    	common_Logger::i('Generating form for '.$content->getUri());
    	$form = new ltiTestConsumer_actions_form_LtiLinkForm($content);
    	$form->getForm()->setActions(array());
    	
    	$widget = new Renderer($this->extension->getConstant('DIR_VIEWS').'templates'.DIRECTORY_SEPARATOR.'authoring.tpl');
    	$widget->setData('formContent', $form->getForm()->render());
    	$widget->setData('saveUrl', _url('save', 'Authoring', 'ltiTestConsumer'));
    	$widget->setData('formName', $form->getForm()->getName());
    	return $widget->render();
    }
    
    /**
     * @see taoTests_models_classes_TestModel::getAuthoringUrl()
     */
    public function getAuthoringUrl( core_kernel_classes_Resource $test) {
        return _url('index', 'Authoring', 'ltiTestConsumer', array('uri' => $test->getUri()));
    }

    /**
     * (non-PHPdoc)
     * @see taoTests_models_classes_TestModel::onTestModelSet()
     */
    public function prepareContent( core_kernel_classes_Resource $test, $items = array()) {
        $class = new core_kernel_classes_Class(CLASS_LTI_TESTCONTENT);
        $content = $class->createInstance();
        $test->setPropertyValue(new core_kernel_classes_Property(TestService::PROPERTY_TEST_CONTENT), $content);
    }
    
    /**
     * (non-PHPdoc)
     * @see taoTests_models_classes_TestModel::onTestModelSet()
     */
    public function deleteContent( core_kernel_classes_Resource $test) {
    	$content = $test->getOnePropertyValue(new core_kernel_classes_Property(TestService::PROPERTY_TEST_CONTENT));
    	if (!is_null($content)) {
    		$content->delete();
    		$test->removePropertyValue(new core_kernel_classes_Property(TestService::PROPERTY_TEST_CONTENT), $content);
    	}
    }
    
    public function getItems( core_kernel_classes_Resource $test) {
    	return array();
    }

    /**
     * (non-PHPdoc)
     * @see taoTests_models_classes_TestModel::cloneContent()
     */
    public function cloneContent( core_kernel_classes_Resource $source, core_kernel_classes_Resource $destination) {

		//clone the process:
		$propInstanceContent = new core_kernel_classes_Property(TestService::PROPERTY_TEST_CONTENT);
		try{
			$content = $source->getUniquePropertyValue($propInstanceContent);
			$destination->editPropertyValues($propInstanceContent, $content->duplicate());
		} catch(Exception $e) {
			throw new Exception("the test process cannot be found");
		}
    }
    
    /**
     * (non-PHPdoc)
     * @see taoTests_models_classes_TestModel::getCompiler()
     */
    public function getCompilerClass() {
        return 'ltiTestConsumer_models_classes_LtiTestCompiler';
    }
    
    
    /**
     * (non-PHPdoc)
     * @see taoTests_models_classes_TestModel::onChangeTestLabel()
     */
    public function onChangeTestLabel( core_kernel_classes_Resource $test) {
        // do nothing
    }

	/**
	 * Return the Packable implementation for the given test model.
	 * Packing is an alternative to Compilation. A Packer generates the
	 * data needed to run a test where the compiler creates a stand alone
	 * test.
	 * @return \oat\taoTests\model\pack\Packable the packer class to instantiate
	 * @throws common_exception_NotImplemented
	 */
	public function getPackerClass()
	{
		throw new common_exception_NotImplemented("The packer isn't yet implemented for LTI tests");
	}
}
