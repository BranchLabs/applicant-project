<?php
namespace BruceCastro\BLApplicant\Block;
 
class Contact extends \Magento\Framework\View\Element\Template
{
	protected function _prepareLayout()
	{
	    $this->setMessage('Hello');
	    $this->setName($this->getRequest()->getParam('name'));
	}
	
    public function getContactList()
    {
        return 'This is the Contact List';
    }
}