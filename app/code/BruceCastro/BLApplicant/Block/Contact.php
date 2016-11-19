<?php
namespace BruceCastro\BLApplicant\Block;
 
class Contact extends \Magento\Framework\View\Element\Template
{
	protected $messageManager;
	protected $contactFactory;
	protected $contactId;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\BruceCastro\BLApplicant\Model\ContactFactory $contactFactory )
	{
		$this->messageManager = $messageManager;
		$this->contactFactory = $contactFactory;
		parent::__construct($context);

	}

	protected function _prepareLayout() {

	    $this->contactId = $this->getRequest()->getParam('id');

	}
	
    public function getContact() {

		if(isset($this->contactId)) {
    		$contact = $this->contactFactory->create();
	    	$contact->load($this->contactId);

	    	if(!empty($contact->getData())) {
	    		return $contact;
	    	}
	    	else {
	    		$this->messageManager->addErrorMessage('No contact found with ID: ' . $this->contactId);
	    	}
	    }
	    else {
	    	$this->messageManager->addErrorMessage('No ID specified.');
	    }

	    return false;

    }
}