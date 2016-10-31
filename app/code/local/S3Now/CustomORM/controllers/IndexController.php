<?php
class S3Now_CustomORM_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
	  $this->loadLayout();
	  $this->getLayout()->getBlock("head")->setTitle($this->__("ORM Test"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("orm test", array(
                "label" => $this->__("ORM Test"),
                "title" => $this->__("ORM Test")
		   ));

      $this->renderLayout(); 
    }


    /**
     * Views by an ?id= parameter
     *
     * @author David Cajio
     */
    public function ViewAction() {
	  $this->loadLayout();
	  $this->getLayout()->getBlock("head")->setTitle($this->__("ORM Test View"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("orm test", array(
                "label" => $this->__("ORM Test View"),
                "title" => $this->__("ORM Test View")
		   ));

      $this->renderLayout();
    }
}
